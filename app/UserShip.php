<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShip extends Model
{
    protected $fillable = [
        'site_id',
        'user_id',
        'ship_id',
        'from_date',
        'to_date',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class)->with('rank','user_programs');
    }

    public function ship()
    {
        return $this->belongsTo(ValueList::class);
    }
}
