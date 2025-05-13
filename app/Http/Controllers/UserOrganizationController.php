<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\UserInvitationMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserOrganizationController extends Controller
{
    public function indexUsers()
    {
        $users = User::where('org_id', Auth::guard('web')->user()->org_id)->get();
        return view('organization.users.index', compact('users'));
    }

    public function inviteUserForm()
    {
        $roles = Role::all();
        return view('organization.users.invite', compact('roles'));
    }

    public function sendUserInvite(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required'],
        ]);

        try
        {
            DB::beginTransaction();

            $token = Str::random(40);
            $orgId = Auth::guard('web')->user()->org_id;

            $org = Organization::find($orgId);

            $invitation = Invitation::create([
                'email' => $request->email,
                'org_id' => $orgId,
                'role_id' => $request->role_id,
                'token' => $token,
                'is_accepted' => false,
                'designation' => $request->designation,
            ]);

            $inviteUrl = route('invitation.accept', ['token' => $token]);

            Mail::to($request->email)->send(
                new UserInvitationMail($inviteUrl, $org->name, $request->designation)
            );

            DB::commit();
            return redirect()->route('organization.users.index')->with('success', 'Invitation sent successfully!');
        }
        catch(Exception $e)
        {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->first();
        $organization = Organization::find($invitation->org_id);
        $role = Role::find($invitation->role_id);

        if (!$invitation) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }

        return view('organization.users.enroll', compact('invitation', 'organization', 'role'));
    }

    public function storeUserFromInvite(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_no' => ['required', 'string', 'max:10',],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try
        {
            DB::beginTransaction();
            $invitation = Invitation::where('token', $request->token)->first();

            $role = Role::find($invitation->role_id);
            $organization = Organization::find($invitation->org_id);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'short_name' => $request->short_name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'org_id' => $organization->id,
                'role_id' => $role->id,
                'designation' => $invitation->designation,
                'is_active' => true,
                'password' => Hash::make($request->password),
            ]);

            $invitation->is_accepted = true;
            $invitation->save();

            DB::commit();

            Auth::login($user);
            return redirect()->route('user.dashboard')->with('success', 'User Registered Successfully!');
        }
        catch(Exception $e)
        {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
