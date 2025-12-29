<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AgentController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()->organization_id;
        $agents = User::where('organization_id', $orgId)->get();
        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $password = $data['password'] ?? \Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'organization_id' => auth()->user()->organization_id,
        ]);

        // ensure role exists
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        }

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('agent');
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent created.');
    }

    public function destroy(User $user)
    {
        // ensure user belongs to same org
        if (auth()->user()->organization_id !== $user->organization_id && !auth()->user()->hasRole('super_admin')) {
            abort(403);
        }

        $user->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent removed.');
    }
}
