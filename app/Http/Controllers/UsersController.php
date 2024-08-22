<?php

namespace App\Http\Controllers;

use App\KarcoTask;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\UserProgramTask;
use App\UserRankLog;
use App\Value;
use App\VideotelTask;

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
    if ($request->search) {
      $users = $request->site->users()->with('roles')
        ->whereHas('roles',  function ($q) {
          $q->where('name', '!=', 'Admin');
        })
        ->where('first_name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('middle_name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
        ->orWhere('user_name', 'LIKE', '%' . $request->search . '%')
        ->Where('active', true)
        ->latest()->get();
    }
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
    if ($user->rank_id) {
      $user_rank = [
        'rank_id' => $user->rank_id
      ];
      $rank = new UserRankLog($user_rank);
      $user->user_rank_logs()->save($rank);
    }
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
    // return $user->rank_id;
    if (isset($user->rank_id)) {
      $user_rank_logs = $user->user_rank_logs()
        ->each(function ($log) {
          $log->update([
            'status' => false,
          ]);
        });
      $user->user_rank_logs()->updateOrInsert([
        'status' => true,
        'user_id' => $user->id,
        'rank_id' => $user->rank_id,
        'site_id' => $user->sites[0]->id
      ]);
    }
    return response()->json([
      'data'  =>  $user,
      'success' =>  true
    ], 200);
  }

  public function userReports(Request $request)
  {
    // $from_date = '2022-02-08';
    // $to_date = '2022-02-10';
    $from_date = request()->from_date;
    $to_date = request()->to_date;
    $users = [];
    $users = $request->site->user_Reports()->where('users.id', '=', request()->user_id)
      ->with('roles')
      ->whereHas('roles',  function ($q) {
        $q->where('name', '!=', 'Admin');
        $q->where('name', '!=', 'Main Admin');
      });

    $users = $users->latest()->get();
    $completed_tasks = request()->site->user_program_posts()->with('program_post')
      ->where('user_id', '=', request()->user_id)
      ->where('program_post_id', '=', request()->program_post_id)
      ->latest()->get();
    $All_program_tasks = $completed_tasks[0]['program_post']['program_tasks'];
    $kpi_cpp_tasks = UserProgramTask::with('ship', 'program_task')->where('is_completed', true)
      ->where('user_id', request()->user_id);

    $kpi_karco_tasks = KarcoTask::with('ship')->where('assessment_status', '=', 'Completed')
      ->where('user_id', request()->user_id);
    $kpi_videotel_tasks = VideotelTask::with('ship')->where('score', '=', '100%')
      ->where('user_id', request()->user_id);
    if ($from_date && $to_date) {
      $kpi_cpp_tasks = $kpi_cpp_tasks->whereBetween('completion_date', [$from_date, $to_date]);
      $kpi_karco_tasks = $kpi_karco_tasks->whereBetween('done_on', [$from_date, $to_date]);
      $kpi_videotel_tasks = $kpi_videotel_tasks->whereBetween('date', [$from_date, $to_date]);
    }
    $kpi_cpp_tasks = $kpi_cpp_tasks->get();
    $users[0]['cpp_tasks'] = $kpi_cpp_tasks;

    $kpi_karco_tasks = $kpi_karco_tasks->get();
    $users[0]['karco_tasks'] = $kpi_karco_tasks;

    $kpi_videotel_tasks = $kpi_videotel_tasks->get();
    $users[0]['videotel_tasks'] = $kpi_videotel_tasks;

    $completed_ppt = [];
    foreach ($kpi_cpp_tasks as $key => $value) {
      // return $value;
      if ($value->active == true) {
        if ($value->is_completed == 1) {
          $completed_ppt[] = $value->program_task_id;
        }
      }
    }
    $pending_program_task = [];
    foreach ($All_program_tasks as $key => $pt) {
      $task_id = $pt->id;
      if (!in_array($task_id, $completed_ppt)) {
        $pending_program_task[] = $pt;
      }
    }

    $users[0]['pending_program_task'] = $pending_program_task;
    $pending_user_program_task_arrays = $users[0]['pending_program_task'];
    // return $pending_user_program_task_arrays;
    usort($pending_user_program_task_arrays, function ($a, $b) {
      return $a['program_task_id'] - $b['program_task_id'];
    });

    $users[0]['cpp_tasks'] = $kpi_cpp_tasks->toArray();
    $user_program_task_arrays = $users[0]['cpp_tasks'];
    usort($user_program_task_arrays, function ($c, $d) {
      return $c['program_task_id'] - $d['program_task_id'];
    });
    // return $user_program_task_arrays;

    $users[0]['pending_user_program_task'] = $pending_user_program_task_arrays;
    $users[0]['cpp_tasks'] = $user_program_task_arrays;
    // return $pending_program_task;



    return response()->json([
      'data'  =>  $users
    ], 200);
  }
}
