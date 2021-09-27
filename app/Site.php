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
  public function posts()
  {
    return $this->hasMany(Post::class);
  }

  public function timezones()
  {
    return $this->hasMany(Timezone::class);
  }

  public function countries()
  {
    return $this->hasMany(Country::class);
  }

  public function banks()
  {
    return $this->hasMany(Bank::class);
  }

  public function pf_codes()
  {
    return $this->hasMany(PfCode::class);
  }

  public function frequencies()
  {
    return $this->hasMany(Frequency::class);
  }

  public function service_providers()
  {
    return $this->hasMany(ServiceProvider::class);
  }

  public function employee_statuses()
  {
    return $this->hasMany(EmployeeStatus::class);
  }

  public function months()
  {
    return $this->hasMany(Month::class);
  }

  public function values()
  {
    return $this->hasMany(Value::class);
  }

  public function employee_no_series()
  {
    return $this->hasMany(EmployeeNoSeries::class);
  }

  public function categories()
  {
    return $this->hasMany(Category::class);
  }

  public function leaving_reasons()
  {
    return $this->hasMany(LeavingReason::class)
      ->with('pf_code');
  }

  public function asset_groups()
  {
    return $this->hasMany(AssetGroup::class);
  }

  public function courses()
  {
    return $this->hasMany(Course::class)
      ->with('course_details');
  }

  public function shifts()
  {
    return $this->hasMany(Shift::class);
  }

  public function shift_rotation_policies()
  {
    return $this->hasMany(ShiftRotationPolicy::class);
  }

  public function shift_rotation_policy_shifts()
  {
    return $this->hasMany(ShiftRotationPolicyShift::class);
  }

  public function sitePasswordSetting()
  {
    return $this->hasMany(SitePasswordSetting::class);
  }

  public function employee_setting()
  {
    return $this->hasMany(EmployeeSetting::class);
  }

  public function lwf_policy()
  {
    return $this->hasMany(LwfPolicy::class);
  }

  public function userFamilyDetail()
  {
    return $this->hasMany(UserFamilyDetail::class);
  }

  public function userPassport()
  {
    return $this->hasMany(UserPassport::class);
  }

  public function userVisa()
  {
    return $this->hasMany(UserVisa::class);
  }

  public function userAsset()
  {
    return $this->hasMany(UserAsset::class);
  }

  public function userAccessCard()
  {
    return $this->hasMany(UserAccessCard::class);
  }

  public function lwfPolicyDetail()
  {
    return $this->hasMany(LwfPolicyDetail::class);
  }

  public function reimbusementReviewer()
  {
    return $this->hasMany(ReimbusementReviewer::class);
  }

  public function ptSlab()
  {
    return $this->hasMany(PtSlab::class);
  }

  public function ptSlabDetail()
  {
    return $this->hasMany(PtSlabDetail::class);
  }

  public function letterOption()
  {
    return $this->hasMany(LetterOption::class);
  }

  public function letterSignatory()
  {
    return $this->hasMany(LetterSignatory::class);
  }

  public function leaveOption()
  {
    return $this->hasMany(LeaveOption::class);
  }

  public function siteLeaveType()
  {
    return $this->hasMany(SiteLeaveType::class);
  }

  public function clientProductPricing()
  {
    return $this->hasMany(ClientProductPricing::class);
  }

  public function requisition()
  {
    return $this->hasMany(Requisition::class);
  }

  public function requisitionDetail()
  {
    return $this->hasMany(RequisitionDetail::class);
  }

  public function vendor()
  {
    return $this->hasMany(Vendor::class);
  }

  public function unit()
  {
    return $this->hasMany(Unit::class);
  }

  public function productVendor()
  {
    return $this->hasMany(ProductVendor::class);
  }

  public function ticket()
  {
    return $this->hasMany(Ticket::class);
  }

  public function ticketFollowup()
  {
    return $this->hasMany(TicketFollowup::class);
  }

  public function product()
  {
    return $this->hasMany(Product::class);
  }

  public function siteLeaveRule()
  {
    return $this->hasMany(SiteLeaveRule::class);
  }

  public function leaveType()
  {
    return $this->hasMany(LeaveType::class);
  }

  public function siteLeaveRuleDetail()
  {
    return $this->hasMany(SiteLeaveRuleDetail::class);
  }

  public function leaveSchemeDetail()
  {
    return $this->hasMany(LeaveSchemeDetail::class);
  }

  public function weekendPolicy()
  {
    return $this->hasMany(WeekendPolicy::class);
  }

  public function weekendPolicyDetail()
  {
    return $this->hasMany(WeekendPolicyDetail::class);
  }

  public function leaveReason()
  {
    return $this->hasMany(LeaveReason::class);
  }

  public function attendanceOption()
  {
    return $this->hasMany(AttendanceOption::class);
  }

  public function shiftSession()
  {
    return $this->hasMany(ShiftSession::class);
  }

  public function attendanceSchedule()
  {
    return $this->hasMany(AttendanceSchedule::class);
  }

  public function attendancePolicy()
  {
    return $this->hasMany(AttendancePolicy::class);
  }
  public function leave_schemes()
  {
    return $this->hasMany(LeaveScheme::class);
  }

  public function configuration_parameters()
  {
    return $this->hasMany(ConfigurationParameter::class);
  }

  public function configurations()
  {
    return $this->hasMany(Configuration::class);
  }

  public function pfconfigurations()
  {
    return $this->hasMany(PfConfiguration::class);
  }

  public function pt_configurations()
  {
    return $this->hasMany(PtConfiguration::class)
      ->with('state');
  }

  public function esic_configurations()
  {
    return $this->hasMany(EsicConfiguration::class);
  }
  public function lwf_configurations()
  {
    return $this->hasMany(LwfConfiguration::class)
      ->with('state');
  }
  public function monthly_attendances()
  {
    return $this->hasMany(MonthlyAttendance::class);
  }
  public function monthly_salaries()
  {
    return $this->hasMany(MonthlySalary::class);
  }
  public function groups()
  {
    return $this->hasMany(Group::class)
      ->with('country', 'state');
  }
  public function feedbacks()
  {
    return $this->hasMany(Feedback::class);
  }
}
