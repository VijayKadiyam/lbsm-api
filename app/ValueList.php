<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValueList extends Model
{
  protected $fillable = [
    'value_id', 'site_id', 'description', 'code', 'is_active'
  ];

  public function value()
  {
    return $this->belongsTo(Value::class);
  }
}
