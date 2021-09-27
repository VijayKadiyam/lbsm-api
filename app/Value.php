<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
  protected $fillable = [
    'name'
  ];

  public function site()
  {
    return $this->belongsTo(Site::class);
  }

  public function value_lists()
  {
    return $this->hasMany(ValueList::class);
  }

  public function active_value_lists()
  {
    return $this->hasMany(ValueList::class)
      ->where('is_active', '=', 1);
  }
}
