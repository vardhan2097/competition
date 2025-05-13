<?php

namespace App\Http\Controllers;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->where('is_accepted', false)->FirstOrFail();
        return view('auth.invited-register', compact('invitation'));
    }

    public function register(Request $request, $token)
    {
        $invitation = Invitation::where('token',$token)->where('is_accepted', false)->FirstOrFail();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['required', 'string', 'max:10',],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'org_id' => $request->org_id,
            'role_id' => $request->role_id,
            'designation' => $request->designation,
            'is_active' => true,
            'password' => Hash::make($request->password),
        ]);

        $invitation->is_accepted = true;
        $invitation->save();

        auth()->login($user);
        return redirect()->route('dashboard');
    }
}
