<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    public function auditLogs()
    {
        $stats = [
            'successful_logins' => 0,
            'failed_logins' => 0,
            'blocked_ips' => 0,
            'active_sessions' => DB::table('sessions')->count(),
        ];

        $auditLogs = DB::table('tenant_activities')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('super-admin.security.audit-logs', compact('stats', 'auditLogs'));
    }
}





