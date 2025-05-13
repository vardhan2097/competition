<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrganizationSetupController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return view('admin.organization.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organization.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'org_name' => ['required', 'string', 'max:255'],
            'org_address' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255',],
            // 'mobile_no' => ['required', 'string', 'max:10', 'unique:users'],
            'mobile_no' => ['required', 'string', 'max:10',],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        try
        {
            DB::beginTransaction();

            if($request->hasFile('org_logo')) {
                $logoPath = $request->file('org_logo')->store('Organization_logos', 'public');
                // stores in storage/app/public/Organization_logos and returns path like logos/xyz.jpg
            }

            $organization = Organization::create([
                'name' => $request->org_name,
                'address' => $request->org_address,
                'logo' => $logoPath ?? null,
                'is_active' => true,
            ]);

            $orgAdminRoleId = Role::where('name', 'Organization Admin')->first()->id;

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'org_id' => $organization->id,
                'role_id' => $orgAdminRoleId,
                'designation' => 'Organization Admin',
                'is_active' => true,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return redirect()->route('user.dashboard')->with('success', 'Organization and Organization Admin created successfully');
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            // dd('error:->'.$e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();

        }

    }
}
