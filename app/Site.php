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
      ->with('roles', 'sites');
  }

  public function programs()
  {
    return $this->hasMany(Program::class)->with('program_posts');
  }

  public function program_posts()
  {
    return $this->hasMany(ProgramPost::class);
  }

  public function user_programs()
  {
    return $this->hasMany(UserProgram::class)
      ->with('user', 'program');
  }

  public function user_program_posts()
  {
    return $this->hasMany(UserProgramPost::class)
      ->with('user', 'program', 'program_post');
  }

  public function program_tasks()
  {
    return $this->hasMany(ProgramTask::class)
      ->with('program', 'program_post');
  }

  public function user_program_tasks()
  {
    return $this->hasMany(UserProgramTask::class);
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
    return $this->hasMany(KarcoTask::class)->with('ship','user');
  }
}
