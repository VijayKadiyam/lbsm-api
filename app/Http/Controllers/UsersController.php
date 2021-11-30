<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
// use App\Value;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'site']);
  }

  // public function masters(Request $request)
  // {
  //   $bloodGroupValue = Value::where('name', '=', 'BLOOD GROUP')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $bloodGroups = [];
  //   if ($bloodGroupValue)
  //     $bloodGroups = $bloodGroupValue->active_value_lists;

  //   $maritalStatusValue = Value::where('name', '=', 'MARITAL STATUS')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $maritalStatuses = [];
  //   if ($maritalStatusValue)
  //     $maritalStatuses = $maritalStatusValue->active_value_lists;

  //   $nationalityValue = Value::where('name', '=', 'NATIONALITY')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $nationalities = [];
  //   if ($nationalityValue)
  //     $nationalities = $nationalityValue->active_value_lists;

  //   $residentialStatusValue = Value::where('name', '=', 'RESIDENTIAL STATUS')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $residentialStatuses = [];
  //   if ($residentialStatusValue)
  //     $residentialStatuses = $residentialStatusValue->active_value_lists;

  //   $countryValue = Value::where('name', '=', 'COUNTRY')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $countries = [];
  //   if ($countryValue)
  //     $countries = $countryValue->active_value_lists;

  //   $religionValue = Value::where('name', '=', 'RELIGION')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $religions = [];
  //   if ($religionValue)
  //     $religions = $religionValue->active_value_lists;

  //   $verificationStatusValue = Value::where('name', '=', 'VERIFICATION STATUS')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $verificationStatuses = [];
  //   if ($verificationStatusValue)
  //     $verificationStatuses = $verificationStatusValue->active_value_lists;

  //   $employeeStatusValue = Value::where('name', '=', 'EMPLOYEE STATUS')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $employeeStatuses = [];
  //   if ($employeeStatusValue)
  //     $employeeStatuses = $employeeStatusValue->active_value_lists;

  //   $addressTypeValue = Value::where('name', '=', 'ADDRESS TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $addressTypes = [];
  //   if ($addressTypeValue)
  //     $addressTypes = $addressTypeValue->active_value_lists;

  //   $stateValue = Value::where('name', '=', 'STATE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $states = [];
  //   if ($stateValue)
  //     $states = $stateValue->active_value_lists;

  //   $countryValue = Value::where('name', '=', 'COUNTRY')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $countries = [];
  //   if ($countryValue)
  //     $countries = $countryValue->active_value_lists;


  //   $accountTypeValue = Value::where('name', '=', 'ACCOUNT TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $accountTypes = [];
  //   if ($accountTypeValue)
  //     $accountTypes = $accountTypeValue->active_value_lists;

  //   $paymentTypeValue = Value::where('name', '=', 'PAYMENT TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $paymentTypes = [];
  //   if ($paymentTypeValue)
  //     $paymentTypes = $paymentTypeValue->active_value_lists;

  //   $documentTypeValue = Value::where('name', '=', 'DOCUMENT TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $documentTypes = [];
  //   if ($documentTypeValue)
  //     $documentTypes = $documentTypeValue->active_value_lists;

  //   $qualificationValue = Value::where('name', '=', 'QUALIFICATION')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $qualifications = [];
  //   if ($qualificationValue)
  //     $qualifications = $qualificationValue->active_value_lists;

  //   $qualificationAreaValue = Value::where('name', '=', 'QUALIFICATION AREA')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $qualificationAreas = [];
  //   if ($qualificationAreaValue)
  //     $qualificationAreas = $qualificationAreaValue->active_value_lists;


  //   $passportTypeValue = Value::where('name', '=', 'PASSPORT TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $passportTypes = [];
  //   if ($passportTypeValue)
  //     $passportTypes = $passportTypeValue->active_value_lists;

  //   $visaTypeValue = Value::where('name', '=', 'VISA TYPE')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $visaTypes = [];
  //   if ($visaTypeValue)
  //     $visaTypes = $visaTypeValue->active_value_lists;

  //   $genderValue = Value::where('name', '=', 'GENDER')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $genders = [];
  //   if ($genderValue)
  //     $genders = $genderValue->active_value_lists;

  //   $relationValue = Value::where('name', '=', 'RELATION')
  //     ->where('site_id', '=', $request->site->id)
  //     ->first();
  //   $relations = [];
  //   if ($relationValue)
  //     $relations = $relationValue->active_value_lists;


  //   $rolesController = new RolesController();
  //   $rolesResponse = $rolesController->index($request);

  //   $configurations = [];
  //   // PF
  //   $pf_configurations = request()->site->pfconfigurations;
  //   foreach ($pf_configurations as $pf_configuration) {
  //     $configurations['pf_configuration'] = $pf_configuration;
  //   }
  //   // PT
  //   $pt_configurations = request()->site->pt_configurations()
  //     ->where('employee_value', '!=', '300')
  //     ->get();
  //   $configurations['pt_configurations'] = $pt_configurations;
  //   // ESIC
  //   $esic_configurations = request()->site->esic_configurations;
  //   foreach ($esic_configurations as $esic_configuration) {
  //     $configurations['esic_configuration'] = $esic_configuration;
  //   }

  //   return response()->json([
  //     'roles'         =>  $rolesResponse->getData()->data,
  //     'genders'               =>  $genders,
  //   ], 200);
  // }

  /*
   * To get all the users
   *
   *@
   */
  public function index(Request $request)
  {
    $role = 3;
    $users = [];
    $users = $users = $request->site->users()->with('roles')
    ->whereHas('roles',  function ($q) {
      $q->where('name', '!=', 'Admin');
      $q->where('name', '!=', 'Main Admin');
    })->latest()->get();
    // if ($request->search == 'all')
    //   $users = $request->site->users()->with('roles')
    //     ->whereHas('roles',  function ($q) {
    //       $q->where('name', '!=', 'Admin');
    //     })->latest()->get();
    // else if ($request->role_id) {
    //   $role = Role::find($request->role_id);
    //   $users = $request->site->users()
    //     ->whereHas('roles', function ($q) use ($role) {
    //       $q->where('name', '=', $role->name);
    //     })->latest()->get();
    // }

    return response()->json([
      'data'  =>  $users
    ], 200);
  }

  /*
   * To store a new site user
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'first_name' =>  ['required', 'string', 'max:255'],
      // 'email'     =>  ['required', 'string', 'email', 'max:255', 'unique:users'],
      'email'     =>  ['required', 'string', 'email', 'max:255'],
      'active'    =>  ['required'],
      'role_id'   =>  ['required'],
      'site_id'   =>  ['required'],
    ]);

    // Save User
    $user  = $request->all();
    $user['password'] = bcrypt('123456');
    $user = new User($user);
    $user->save();


    if ($request->role_id)
      $user->assignRole($request->role_id);
    if ($request->site_id)
      $user->assignSite($request->site_id);

    $user->roles = $user->roles;
    $user->sites = $user->sites;

    return response()->json([
      'data'      =>  $user,
      'success'   =>  true
    ], 201);
  }

  /*
   * To show particular user
   *
   *@
   */
  public function show($id)
  {
    $user = User::where('id', '=', $id)
      ->first();

    $user->roles = $user->roles;
    $user->sites = $user->sites;

    return response()->json([
      'data'  =>  $user,
      'success' =>  true
    ], 200);
  }

  /*
   * To update user details
   *
   *@
   */
  public function update(Request $request, User $user)
  {
    $user->update($request->all());
    if ($request->role_id)
      $user->assignRole($request->role_id);

    $user->roles = $user->roles;
    $user->sites = $user->sites;

    return response()->json([
      'data'  =>  $user,
      'success' =>  true
    ], 200);
  }
}
