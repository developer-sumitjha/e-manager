<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get the current tenant ID from authenticated user
     */
    protected function getTenantId()
    {
        return auth()->user()->tenant_id ?? null;
    }

    /**
     * Check if user belongs to the current tenant
     */
    protected function belongsToTenant(User $user)
    {
        $tenantId = $this->getTenantId();
        
        // If no tenant_id, allow access (for super admin or non-tenant users)
        if ($tenantId === null) {
            return true;
        }
        
        return $user->tenant_id === $tenantId;
    }

    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by tenant_id if user has one (vendor/admin)
        $tenantId = $this->getTenantId();
        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        }
        
        // Search functionality
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->has('active') && $request->active != '') {
            $query->where('is_active', $request->active == '1');
        }
        
        // Load orders count for display
        $users = $query->withCount('orders')->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,employee,delivery_boy,customer',
            'permissions' => 'array',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Only employees can have permissions set, others ignore
        if (($validated['role'] ?? null) !== 'employee') {
            unset($validated['permissions']);
        }

        // Automatically set tenant_id if user has one (vendor/admin creating users)
        $tenantId = $this->getTenantId();
        if ($tenantId !== null) {
            $validated['tenant_id'] = $tenantId;
        }

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        // Check if user belongs to current tenant
        if (!$this->belongsToTenant($user)) {
            abort(403, 'You do not have permission to view this user.');
        }
        
        $user->load('orders');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Check if user belongs to current tenant
        if (!$this->belongsToTenant($user)) {
            abort(403, 'You do not have permission to edit this user.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Check if user belongs to current tenant
        if (!$this->belongsToTenant($user)) {
            abort(403, 'You do not have permission to update this user.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,employee,delivery_boy,customer',
            'permissions' => 'array',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Only employees can have permissions set, others clear
        if (($validated['role'] ?? $user->role) !== 'employee') {
            $validated['permissions'] = [];
        }
        
        // Ensure tenant_id is not changed
        unset($validated['tenant_id']);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Check if user belongs to current tenant
        if (!$this->belongsToTenant($user)) {
            abort(403, 'You do not have permission to delete this user.');
        }
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        // Check if user belongs to current tenant
        if (!$this->belongsToTenant($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this user.'
            ], 403);
        }
        
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully',
            'is_active' => $user->is_active
        ]);
    }

    public function export(Request $request)
    {
        $users = User::query();
        
        // Filter by tenant_id if user has one (vendor/admin)
        $tenantId = $this->getTenantId();
        if ($tenantId !== null) {
            $users->where('tenant_id', $tenantId);
        }
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $users->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $users->where('role', $request->role);
        }

        if ($request->has('status') && $request->status) {
            $users->where('is_active', $request->status === 'active');
        }

        $users = $users->get();

        $filename = 'users_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At']);
            
            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
