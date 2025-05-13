<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitationMail;
use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class UserInvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::where('org_id', Auth::guard('users')->user()->org_id)->get();
        return view('admin.people.index', compact('invitations'));
    }

    public function create()
    {
        return view('admin.people.invite');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        try
        {
            DB::beginTransaction();

            $token = Str::random(40);
            $orgId = Auth::guard('users')->user()->org_id;
            $roleId = $request->roleId;

            $org = Organization::find($orgId);

            $invitation = Invitation::create([
                'email' => $request->email,
                'org_id' => $orgId,
                'role_id' => $roleId,
                'token' => $token,
                'is_accepted' => false,
                'designation' => $request->designation,
            ]);

            $inviteUrl = route('invitation.accept', ['token' => $token]);

            Mail::to($request->email)->send(
                new UserInvitationMail($inviteUrl, $org->name, $request->designation)
            );
            DB::commit();
            return redirect()->route('admin.people.manage')->with('success', 'Invitation sent successfully!');

        }
        catch(Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
