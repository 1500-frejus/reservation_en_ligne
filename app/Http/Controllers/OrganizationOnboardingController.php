<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrganizationOnboardingController extends Controller
{
    public function create()
    {
        return view('organizations.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:organizations,slug',
            'contact_email' => 'nullable|email',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        // If slug was not provided (e.g., JS disabled), generate one from the name
        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $i = 2;
            while (Organization::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        DB::beginTransaction();
        try {
            $org = Organization::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'contact_email' => $data['contact_email'] ?? null,
                'status' => 'pending',
            ]);

            $user = User::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'organization_id' => $org->id,
            ]);

            // assign organization_admin role if Spatie available; ensure role exists
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'organization_admin', 'guard_name' => 'web']);
            }

            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('organization_admin');
                } catch (\Throwable $e) {
                    // ignore assignment failure but proceed
                }
            }

            DB::commit();

            // Log the newly created admin in and redirect to the admin dashboard so they can manage clients
            Auth::login($user);

            return redirect()->route('admin.dashboard')->with('success', 'Organisation créée. Vous êtes connecté en tant que ' . $user->name . '.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Unable to create organization: '.$e->getMessage()]);
        }
    }

    public function success()
    {
        return view('organizations.success');
    }
}
