<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentInvitation;
use App\Models\User;
use App\Mail\AgentInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AgentInvitationController extends Controller
{
    public function create()
    {
        return view('admin.agents.invite');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:agent_invitations,email',
            'name' => 'nullable|string|max:255',
        ]);

        $inv = AgentInvitation::create([
            'organization_id' => Auth::user()->organization_id,
            'email' => $data['email'],
            'name' => $data['name'] ?? null,
            'token' => AgentInvitation::generateToken(),
        ]);

        // send mail
        Mail::to($inv->email)->send(new AgentInvitationMail($inv));

        return redirect()->route('admin.agents.index')->with('success', 'Invitation sent.');
    }
}
