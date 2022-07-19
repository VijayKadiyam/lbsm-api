<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'user_name',
    'gender',
    'dob',
    'email',
    'active',
    'password',
    'image_path',
    'relation_id',
    'relation_with_id',
    'nationality',
    'rank',
    'unique_id',
    'rank_id',

  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /*
   * To generate api token
   *
   *@
   */
  public function generateToken()
  {
    if ($this->api_token == null)
      $this->api_token = str_random(60);
    $this->save();
    return $this;
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class)
      ->with('permissions');
  }
  public function assignRole($role)
  {
    return $this->roles()->sync([$role]);
  }

  public function hasRole($roles)
  {
    return $this->roles ? in_array($roles, $this->roles->pluck('id')->toArray()) : false;
  }

  public function sites()
  {
    return $this->belongsToMany(Site::class);
  }

  /**
   * Assign company to user
   *
   * @ 
   */
  public function assignSite($company)
  {
    return $this->sites()->sync([$company]);
  }

  /**
   * Check if the user has company
   *
   * @ 
   */
  public function hasSite($company)
  {
    return $this->sites ? in_array($company, $this->sites->pluck('id')->toArray()) : false;
  }

  public function permissions()
  {
    return $this->belongsToMany(Permission::class);
  }

  public function assignPermission($permission)
  {
    $this->permissions()->syncWithoutDetaching([$permission]);
    $this->refresh();

    return $this;
  }

  public function unassignPermission($permission)
  {
    $this->permissions()->detach([$permission]);
    $this->refresh();

    return $this;
  }

  public function hasPermission($permission)
  {
    return $this->permissions ? in_array($permission, $this->permissions->pluck('id')->toArray()) : false;
  }


  public function rank()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function religion()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function verification_status()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function employee_status()
  {
    return $this->belongsTo(ValueList::class);
  }


  public function user_programs()
  {
    return $this->hasMany(UserProgram::class)->with('program')->where('active', true);;
  }

  public function user_program_posts()
  {
    return $this->hasMany(UserProgramPost::class)->with('program_post.post')->where('active', true);;
  }

  public function user_program_tasks()
  {
    return $this->hasMany(UserProgramTask::class)->with('program_task', 'ship')->where('active', true);;
  }

  public function user_ships()
  {
    return $this->hasMany(UserShip::class)->with('ship');
  }

  public function user_i_tests()
  {
    return $this->hasMany(UserITest::class);
  }

  public function karco_tasks()
  {
    return $this->hasMany(KarcoTask::class);
  }

  public function videotel_tasks()
  {
    return $this->hasMany(VideotelTask::class);
  }
  public function karco_tasks_report()
  {
    return $this->hasMany(KarcoTask::class)->where('assessment_status', '=', 'Completed')->with('ship');
  }

  public function videotel_tasks_report()
  {
    return $this->hasMany(VideotelTask::class)->where('score', '=', '100%')->with('ship');
  }
}
