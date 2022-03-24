<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Value;

// use App\Value;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'site']);
  }

  public function masters(Request $request)
  {
    $rankValue = Value::where('name', '=', 'POST')
      ->where('site_id', '=', $request->site->id)
      ->first();
    $ranks = [];

    if ($rankValue)
      $ranks = $rankValue->active_value_lists;

    $nationalityValue = Value::where('name', '=', 'NATIONALITY')
      ->where('site_id', '=', $request->site->id)
      ->first();
    $nationalities = [];
    if ($nationalityValue)
      $nationalities = $nationalityValue->active_value_lists;
    return response()->json([
      'ranks'               =>  $ranks,
      'nationalities'               =>  $nationalities,
    ], 200);
  }

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
      // 'email'     =>  ['email', 'max:255'],
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
    $user->rank = $user->rank;
    $user->user_program_posts = $user->user_program_posts;

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

  public function userReports(Request $request)
  {
    $role = 3;
    $users = [];
    $users = $request->site->user_Reports()->where('users.id', '=', request()->user_id)
      ->with('roles')
      ->whereHas('roles',  function ($q) {
        $q->where('name', '!=', 'Admin');
        $q->where('name', '!=', 'Main Admin');
      });
    $users = $users->latest()->get();
    
    return response()->json([
      'data'  =>  $users
    ], 200);
  }
}
