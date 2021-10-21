<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramPost extends Model
{
    protected $fillable = [
        'site_id',
        'program_id',
        'serial_no',
        'post_id',
    ];

    public function site() {
        return $this->belongsTo(Site::class);
    }
    public function program()
  {
    return $this->belongsTo(Program::class);
  }
  public function post()
    {
      return $this->belongsTo(ValueList::class, 'post_id');
    }   
}
