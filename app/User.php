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

  public function user_educations()
  {
    return $this->hasMany(UserEducation::class)
      ->with('qualification');
  }

  public function user_passports()
  {
    return $this->hasMany(UserPassport::class);
  }
  public function user_salaries()
  {
    return $this->hasMany(UserSalary::class)
      ->latest();
  }
  public function user_family_details()
  {
    return $this->hasMany(UserFamilyDetail::class);
  }

  public function user_assets()
  {
    return $this->hasMany(UserAsset::class)
      ->with('asset_type', 'asset');
  }

  public function user_visas()
  {
    return $this->hasMany(UserVisa::class)
      ->with('country', 'visa_type');
  }

  public function user_addresses()
  {
    return $this->hasMany(UserAddress::class)
      ->with('address_type', 'state', 'country');
  }

  public function user_identities()
  {
    return $this->hasMany(UserIdentity::class)
      ->with('document_type');
  }

  public function user_categories()
  {
    return $this->hasMany(UserCategory::class)
      ->with('category', 'category_list')
      ->latest();
  }

  public function user_experiences()
  {
    return $this->hasMany(UserExperience::class);
  }
  public function user_access_cards()
  {
    return $this->hasMany(UserAccessCard::class);
  }

  public function user_documents()
  {
    return $this->hasMany(UserDocument::class);
  }

  public function user_punches()
  {
    return $this->hasMany(UserPunch::class);
  }

  public function user_daily_summaries()
  {
    return $this->hasMany(UserDailySummary::class)
      ->with('daily_task');
  }

  public function blood_group()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function marital_status()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function nationality()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function residential_status()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function country_origin()
  {
    return $this->belongsTo(ValueList::class);
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

  public function bank_name()
  {
    return $this->belongsTo(Bank::class);
  }

  public function bank_branch()
  {
    return $this->belongsTo(BankBranch::class);
  }

  public function bank_acc_type()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function bank_payment_type()
  {
    return $this->belongsTo(ValueList::class);
  }

  public function user_programs()
  {
    return $this->hasMany(UserProgram::class);
  }
  public function user_program_posts()
  {
    return $this->hasMany(UserProgramPost::class);
  }
  public function karco_tasks()
  {
    return $this->hasMany(KarcoTask::class);
  }
  public function videotel_tasks()
  {
    return $this->hasMany(VideotelTask::class);
  }
  public function user_ships()
  {
    return $this->hasMany(UserShip::class);
  }
}
