<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
  protected $fillable = [
    'name', 'phone', 'email',
  ];

  public function users()
  {
    return $this->belongsToMany(User::class)
      ->with('roles', 'sites', 'rank', 'user_program_posts')->where('active', '=', true);
  }

  public function user_Reports()
  {
    return $this->belongsToMany(User::class)
      ->with('roles', 'sites', 'rank', 'user_programs', 'karco_tasks_report', 'user_program_tasks', 'videotel_tasks_report');
  }

  public function programs()
  {
    return $this->hasMany(Program::class)->with('program_posts')->where('active', '=', true);
  }

  public function program_posts()
  {
    return $this->hasMany(ProgramPost::class)->with('program_tasks');
  }

  public function user_programs()
  {
    return $this->hasMany(UserProgram::class)
      ->with('user', 'program')->where('active',true);;
  }

  public function user_program_posts()
  {
    return $this->hasMany(UserProgramPost::class)
      ->with('user', 'program', 'program_post')->where('active',true);;
  }

  public function program_tasks()
  {
    return $this->hasMany(ProgramTask::class)
      ->with('program', 'program_post');
  }

  public function user_program_tasks()
  {
    return $this->hasMany(UserProgramTask::class)->where('active',true);;
  }

  public function user_program_task_documents()
  {
    return $this->hasMany(UserProgramTaskDocument::class);
  }

  public function values()
  {
    return $this->hasMany(Value::class);
  }
  public function crude_karco_tasks()
  {
    return $this->hasMany(CrudeKarcoTask::class);
  }
  public function karco_tasks()
  {
    return $this->hasMany(KarcoTask::class)->with('ship', 'user')->where('is_deleted', '=', false);
  }
  public function crude_videotel_tasks()
  {
    return $this->hasMany(CrudeVideotelTask::class);
  }
  public function videotel_tasks()
  {
    return $this->hasMany(VideotelTask::class)->with('ship', 'user')->where('is_deleted', '=', false);
  }
  public function user_ships()
  {
    return $this->hasMany(UserShip::class)->with('ship', 'user');
  }
  public function user_i_tests()
  {
    return $this->hasMany(UserITest::class)->with('user');
  }
}
