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
    return $this->hasMany(Program::class);
  }
  
  public function program_posts()
  {
    return $this->hasMany(ProgramPost::class);
  }
  
  public function user_programs()
  {
    return $this->hasMany(UserProgram::class);
  }
  
  public function user_program_posts()
  {
    return $this->hasMany(UserProgramPost::class);
  }

  public function program_tasks()
  {
    return $this->hasMany(ProgramTask::class);
  }
  public function user_program_tasks()
  {
    return $this->hasMany(UserProgramTask::class);
  }
}
