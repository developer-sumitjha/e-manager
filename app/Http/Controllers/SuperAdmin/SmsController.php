<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use App\Models\SmsCampaign;
use App\Models\SmsMessage;
use App\Models\SmsCredit;
use App\Models\Tenant;
use App\Services\FirebaseSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(FirebaseSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * SMS Dashboard
     */
    public function index()
    {
        $stats = [
            'total_sent' => SmsMessage::where('status', 'sent')->count(),
            'total_delivered' => SmsMessage::where('status', 'delivered')->count(),
            'total_failed' => SmsMessage::where('status', 'failed')->count(),
            'total_campaigns' => SmsCampaign::count(),
            'active_campaigns' => SmsCampaign::where('status', 'sending')->count(),
            'credits_balance' => SmsCredit::getInstance()->balance,
            'total_cost' => SmsMessage::sum('cost'),
        ];

        // Recent messages
        $recentMessages = SmsMessage::with(['tenant', 'campaign'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly stats (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'sent' => SmsMessage::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return view('super-admin.sms.index', compact('stats', 'recentMessages', 'monthlyStats'));
    }

    /**
     * Templates Management
     */
    public function templates()
    {
        $templates = SmsTemplate::orderBy('created_at', 'desc')->get();
        return view('super-admin.sms.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:marketing,notification,transactional,otp',
        ]);

        SmsTemplate::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'content' => $request->content,
            'type' => $request->type,
            'variables' => $this->extractVariables($request->content),
            'is_active' => true,
        ]);

        return back()->with('success', 'SMS template created successfully!');
    }

    public function updateTemplate(Request $request, SmsTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:marketing,notification,transactional,otp',
        ]);

        $template->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'content' => $request->content,
            'type' => $request->type,
            'variables' => $this->extractVariables($request->content),
        ]);

        return back()->with('success', 'Template updated successfully!');
    }

    public function deleteTemplate(SmsTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template deleted successfully!');
    }

    /**
     * Campaigns Management
     */
    public function campaigns()
    {
        $campaigns = SmsCampaign::orderBy('created_at', 'desc')->paginate(20);
        return view('super-admin.sms.campaigns', compact('campaigns'));
    }

    public function createCampaign()
    {
        $templates = SmsTemplate::where('is_active', true)->get();
        $tenants = Tenant::where('status', 'active')->get();
        
        return view('super-admin.sms.create-campaign', compact('templates', 'tenants'));
    }

    public function storeCampaign(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,active,trial,pending,custom',
            'custom_phones' => 'required_if:recipient_type,custom',
            'schedule' => 'nullable|in:now,later',
            'scheduled_at' => 'required_if:schedule,later|date|after:now',
        ]);

        // Get recipients
        $recipients = $this->getRecipients($request->recipient_type, $request->custom_phones);

        $campaign = SmsCampaign::create([
            'name' => $request->name,
            'message' => $request->message,
            'recipient_type' => $request->recipient_type,
            'custom_recipients' => $request->recipient_type === 'custom' 
                ? explode(',', $request->custom_phones) 
                : null,
            'status' => $request->schedule === 'now' ? 'sending' : 'scheduled',
            'scheduled_at' => $request->schedule === 'later' ? $request->scheduled_at : null,
            'total_recipients' => count($recipients),
        ]);

        // Send immediately if "now"
        if ($request->schedule === 'now') {
            $this->sendCampaign($campaign, $recipients);
        }

        return redirect()->route('super.sms.campaigns')
            ->with('success', 'Campaign created successfully!');
    }

    public function showCampaign(SmsCampaign $campaign)
    {
        $campaign->load('messages');
        return view('super-admin.sms.show-campaign', compact('campaign'));
    }

    /**
     * Send Single SMS
     */
    public function sendSingle()
    {
        $templates = SmsTemplate::where('is_active', true)->get();
        return view('super-admin.sms.send-single', compact('templates'));
    }

    public function sendSingleSms(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $result = $this->smsService->sendSms($request->phone_number, $request->message);

        if ($result['success']) {
            SmsMessage::create([
                'phone_number' => $request->phone_number,
                'message' => $request->message,
                'type' => 'notification',
                'status' => 'sent',
                'provider_message_id' => $result['message_id'],
                'cost' => $result['cost'],
                'sent_at' => now(),
            ]);

            return back()->with('success', 'SMS sent successfully!');
        }

        return back()->with('error', 'Failed to send SMS: ' . $result['error']);
    }

    /**
     * SMS Logs
     */
    public function logs()
    {
        $messages = SmsMessage::with(['tenant', 'campaign'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('super-admin.sms.logs', compact('messages'));
    }

    /**
     * Credits Management
     */
    public function credits()
    {
        $credits = SmsCredit::getInstance();
        return view('super-admin.sms.credits', compact('credits'));
    }

    public function addCredits(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $credits = SmsCredit::getInstance();
        $credits->add($request->amount);

        return back()->with('success', $request->amount . ' SMS credits added successfully!');
    }

    /**
     * Helper Methods
     */
    protected function getRecipients(string $recipientType, ?string $customPhones = null): array
    {
        if ($recipientType === 'custom' && $customPhones) {
            return array_map('trim', explode(',', $customPhones));
        }

        $query = Tenant::query();
        
        if ($recipientType !== 'all') {
            $query->where('status', $recipientType);
        }

        return $query->pluck('phone')->filter()->toArray();
    }

    protected function sendCampaign(SmsCampaign $campaign, array $recipients)
    {
        $sentCount = 0;
        $failedCount = 0;
        $totalCost = 0;

        foreach ($recipients as $phone) {
            $result = $this->smsService->sendSms($phone, $campaign->message);

            $smsMessage = SmsMessage::create([
                'campaign_id' => $campaign->id,
                'phone_number' => $phone,
                'message' => $campaign->message,
                'type' => 'marketing',
                'status' => $result['success'] ? 'sent' : 'failed',
                'provider_message_id' => $result['message_id'] ?? null,
                'error_message' => $result['error'] ?? null,
                'cost' => $result['cost'] ?? 0,
                'sent_at' => $result['success'] ? now() : null,
            ]);

            if ($result['success']) {
                $sentCount++;
                $totalCost += $result['cost'];
            } else {
                $failedCount++;
            }
        }

        $campaign->update([
            'status' => 'completed',
            'sent_at' => now(),
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'cost' => $totalCost,
        ]);
    }

    protected function extractVariables(string $content): array
    {
        preg_match_all('/\{([^}]+)\}/', $content, $matches);
        return $matches[1] ?? [];
    }
}
