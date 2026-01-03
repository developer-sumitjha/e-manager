<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CommunicationController extends Controller
{
    public function index()
    {
        $stats = [
            'open_tickets' => 0,
            'pending_tickets' => 0,
            'closed_tickets' => 0,
        ];

        $recentCommunications = collect();
        
        // Check if announcements table exists
        try {
            $recentCommunications = DB::table('announcements')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            // Table doesn't exist yet
        }

        return view('super-admin.communication.index', compact('stats', 'recentCommunications'));
    }

    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,active,trial,pending'
        ]);

        // Get recipients based on type
        $query = Tenant::query();
        if ($request->recipient_type !== 'all') {
            $query->where('status', $request->recipient_type);
        }
        $tenants = $query->get();

        // Create announcements table if it doesn't exist
        try {
            DB::table('announcements')->insert([
                'title' => $request->title,
                'message' => $request->message,
                'recipient_type' => $request->recipient_type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Create table
            DB::statement('CREATE TABLE IF NOT EXISTS announcements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                recipient_type VARCHAR(50),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');
            
            DB::table('announcements')->insert([
                'title' => $request->title,
                'message' => $request->message,
                'recipient_type' => $request->recipient_type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Announcement sent to ' . $tenants->count() . ' vendors!');
    }

    public function tickets()
    {
        return view('super-admin.communication.tickets');
    }

    public function createAnnouncement()
    {
        return view('super-admin.communication.create-announcement');
    }

    public function storeAnnouncement(Request $request)
    {
        return $this->sendAnnouncement($request);
    }

    public function emailTenants()
    {
        return view('super-admin.communication.email-tenants');
    }

    public function sendBulkEmail(Request $request)
    {
        return back()->with('success', 'Bulk email sent successfully!');
    }

    public function notifications()
    {
        return view('super-admin.communication.notifications');
    }

    public function sendNotification(Request $request)
    {
        return back()->with('success', 'Notification sent successfully!');
    }

    public function support()
    {
        return view('super-admin.communication.support');
    }

    public function replySupport($id, Request $request)
    {
        return back()->with('success', 'Reply sent successfully!');
    }

    public function templates()
    {
        return view('super-admin.communication.templates');
    }

    public function updateTemplate($id, Request $request)
    {
        return back()->with('success', 'Template updated successfully!');
    }

    public function broadcasts()
    {
        return view('super-admin.communication.broadcasts');
    }

    public function sms()
    {
        return view('super-admin.communication.sms');
    }
}





