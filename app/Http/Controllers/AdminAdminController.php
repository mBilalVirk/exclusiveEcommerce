<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminAdminController extends Controller
{
    /**
     * Get list of admins with filtering and search
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'super-admin']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $admins = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $admins,
        ]);
    }

    /**
     * Get a specific admin by ID
     */
    public function show($id)
    {
        $admin = User::whereIn('role', ['admin', 'super-admin'])
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [
                'admin' => $admin,
            ],
        ]);
    }

    /**
     * Update admin information
     */
    public function update(Request $request, $id)
    {
        $admin = User::whereIn('role', ['admin', 'super-admin'])
            ->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $admin->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Admin updated successfully',
            'data' => $admin,
        ]);
    }

    /**
     * Create a new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,super-admin',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $admin = User::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Admin created successfully',
            'data' => $admin,
        ]);
    }

    /**
     * Delete an admin
     */
    public function destroy($id)
    {
        $admin = User::whereIn('role', ['admin', 'super-admin'])
            ->findOrFail($id);

        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Admin deleted successfully',
        ]);
    }
}
