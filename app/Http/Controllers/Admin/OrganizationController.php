<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function edit()
    {
        $org = Auth::user()->organization;
        return view('admin.organizations.edit', compact('org'));
    }

    public function update(Request $request)
    {
        $org = Auth::user()->organization;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
        ]);

        $org->update($data);

        return redirect()->route('admin.organization.edit')->with('success', 'Organization updated.');
    }
}
