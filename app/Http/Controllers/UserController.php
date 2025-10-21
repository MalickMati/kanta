<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function fetch(Request $request)
    {
        $perPage = (int) $request->integer('perPage', 10);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 10;

        $search  = trim((string) $request->get('search', ''));
        $role    = trim((string) $request->get('role', ''));
        $status  = trim((string) $request->get('status', ''));

        $query = User::query()->whereNull('deleted_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $paginator = $query->orderByDesc('created_at')->paginate($perPage)->appends($request->query());

        $users = $paginator->getCollection()->map(function (User $u) {
            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'username'   => $u->username,
                'phone'      => $u->phone,
                'role'       => $u->role,               // 'operator' or 'admin'
                'status'     => $u->status,             // 'active' or 'inactive'
                'created_at' => optional($u->created_at)->toIso8601String(),
            ];
        });

        $pagination = [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'from'         => $paginator->firstItem() ?? 0,
            'to'           => $paginator->lastItem() ?? 0,
            'total'        => $paginator->total(),
        ];

        return response()->json([
            'users'      => $users,
            'pagination' => $pagination,
        ]);
    }

    // Toggle active/inactive
    public function toggle(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Optional protection: avoid self deactivation
        if ($user->id === $request->user()->id && $validated['status'] === 'inactive') {
            return response()->json(['message' => 'You cannot set your own status to inactive'], 422);
        }

        $user->status = $validated['status'];
        $user->save();

        return response()->json([
            'message' => 'Status updated',
            'status'  => $user->status,
        ]);
    }

    // Update via Edit modal
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'phone'    => ['required', 'string', 'max:50'],
            'role'     => ['required', Rule::in(['operator', 'admin'])],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        // Optional protection: prevent removing your own admin role
        if ($user->id === $request->user()->id && $user->role === 'admin' && $validated['role'] !== 'admin') {
            return response()->json([
                'errors' => ['role' => ['You cannot change your own role from admin']],
            ], 422);
        }

        $update = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'phone'    => $validated['phone'],
            'role'     => $validated['role'],
            'status'   => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $update['password'] = Hash::make($validated['password']);
        }

        $user->fill($update)->save();

        return response()->json(['message' => 'User updated']);
    }

    // Soft delete
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot delete your own account'], 422);
        }

        $user->delete(); // soft delete

        return response()->json(['message' => 'User deleted']);
    }
}
