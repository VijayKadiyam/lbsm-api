<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('me', 'MeController@me');

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/reset_password', 'Auth\ResetPasswordController@reset_password');
Route::post('login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::get('/logout', 'Auth\LoginController@logout');

Route::resource('roles', 'RolesController');
Route::resource('role_user', 'RoleUserController');

Route::resource('permissions', 'PermissionsController');
Route::resource('permission_role', 'PermissionRoleController');
Route::resource('permission_user', 'PermissionUserController');

Route::get('users/masters', 'UsersController@masters');
Route::resource('users', 'UsersController');
Route::resource('users/{user}/user_educations', 'UserEducationsController');
Route::resource('users/{user}/user_addresses', 'UserAddressesController');
Route::resource('users/{user}/user_identities', 'UserIdentitiesController');
Route::resource('users/{user}/user_family_details', 'UserFamilyDetailsController');
Route::resource('users/{user}/user_assets', 'UserAssetsController');
Route::resource('users/{user}/user_visas', 'UserVisasController');
Route::resource('users/{user}/user_passports', 'UserPassportsController');
Route::resource('users/{user}/user_access_cards', 'UserAccessCardsController');
Route::resource('users/{user}/user_categories', 'UserCategoriesController');
Route::resource('users/{user}/user_experiences', 'UserExperiencesController');
Route::resource('users/{user}/user_documents', 'UserDocumentsController');
Route::resource('users/{user}/user_salaries', 'UserSalariesController');
Route::get('user_punches/masters', 'UserPunchesController@masters');
Route::resource('users/{user}/user_punches', 'UserPunchesController');
Route::get('/user_daily_summaries/masters', 'UserDailySummariesController@masters');
Route::resource('users/{user}/user_daily_summaries', 'UserDailySummariesController');

Route::resource('sites', 'SitesController');
Route::resource('site_user', 'siteUserController');

Route::resource('timezones', 'TimeZonesController');
Route::resource('countries', 'CountriesController');
Route::resource('countries/{country}/states', 'StatesController');
Route::resource('banks', 'BanksController');
Route::get('bank_branches/masters', 'BankBranchesController@masters');
Route::resource('banks/{bank}/bank_branches', 'BankBranchesController');
Route::post('banks/{bank}/bank_branches_multiple', 'BankBranchesController@storeMultiple');
Route::resource('pf_codes', 'PfCodesController');
Route::resource('frequencies', 'FrequenciesController');
Route::resource('service_providers', 'ServiceProvidersController');
Route::resource('employee_statuses', 'EmployeeStatusesController');
Route::resource('months', 'MonthsController');
Route::resource('values', 'ValuesController');
Route::get('value_lists/masters', 'ValueListsController@masters');
Route::resource('values/{value}/value_lists', 'ValueListsController');
Route::post('values/{value}/value_lists_multiple', 'ValueListsController@storeMultiple');
Route::resource('employee_no_series', 'EmployeeNoSeriesController');
Route::resource('categories', 'CategoriesController');
Route::get('category_lists/masters', 'CategoryListsController@masters');
Route::resource('categories/{category}/category_lists', 'CategoryListsController');
Route::post('categories/{category}/category_lists_multiple', 'CategoryListsController@storeMultiple');
Route::get('leaving_reasons/masters', 'LeavingReasonsController@masters');
Route::resource('leaving_reasons', 'LeavingReasonsController');
Route::resource('asset_groups', 'AssetGroupsController');
Route::get('asset_group_lists/masters', 'AssetGroupListsController@masters');
Route::resource('asset_groups/{asset_group}/asset_group_lists', 'AssetGroupListsController');
Route::post('asset_groups/{asset_group}/asset_group_lists_multiple', 'AssetGroupListsController@storeMultiple');

Route::resource('courses', 'CoursesController');
Route::resource('courses/{course}/course_details', 'CourseDetailsController');

Route::get('attendanceCalendar', 'AnalyticsController@attendanceCalendar');

Route::get('send_otp', 'SendSmsController@index');

Route::post('upload_user_image', 'UploadsController@uploadUserImage');
Route::post('upload_group_image', 'UploadsController@uploadGroupImage');
Route::post('upload_group_division_image', 'UploadsController@uploadGroupDivisionImage');

Route::resource('shifts', 'ShiftsController');

Route::resource('shift_rotation_policy_shifts', 'ShiftRotationPolicyShiftsController');

Route::resource('shift_rotation_policys', 'ShiftRotationPoliciesController');

Route::resource('site_password_settings', 'SitePasswordSettingsController');

Route::resource('employee_settings', 'EmployeeSettingsController');

Route::resource('lwf_policys', 'LwfPoliciesController');

Route::resource('user_family_details', 'UserFamilyDetailsController');

Route::resource('user_passports', 'UserPassportsController');

Route::resource('user_salaries', 'UserSalariesController');

Route::resource('user_visas', 'UserVisasController');

Route::resource('user_assets', 'UserAssetsController');

Route::resource('user_access_cards', 'UserAccessCardsController');

Route::resource('lwf_policy_details', 'LwfPolicyDetailsController');

Route::resource('reimbusement_reviewers', 'ReimbusementReviewersController');

Route::resource('pt_slabs', 'PtSlabsController');

Route::resource('pt_slab_details', 'PtSlabDetailsController');

Route::resource('letter_options', 'LetterOptionsController');

Route::resource('letter_signatorys', 'LetterSignatoriesController');

Route::resource('leave_options', 'LeaveOptionsController');

Route::resource('site_leave_types', 'SiteLeaveTypesController');

Route::resource('client_product_pricings', 'ClientProductPricingsController');

Route::resource('requisitions', 'RequisitionsController');

Route::resource('requisition_details', 'RequisitionDetailsController');

Route::get('vendors/masters', 'VendorsController@masters');

Route::resource('vendors', 'VendorsController');

Route::resource('units', 'UnitsController');

Route::resource('product_vendors', 'ProductVendorsController');

Route::get('tickets/masters', 'TicketsController@masters');
Route::resource('tickets', 'TicketsController');

Route::resource('ticket_followups', 'TicketFollowupsController');
Route::resource('tickets/{ticket}/ticket_followups', 'TicketFollowupsController');

Route::resource('products', 'ProductsController');

Route::post('upload_product_image', 'UploadsController@uploadProductImage');

Route::resource('site_leave_rules', 'SiteLeaveRulesController');

Route::resource('leave_types', 'LeaveTypesController');

Route::resource('site_leave_rule_details', 'SiteLeaveRuleDetailsController');

Route::resource('leave_scheme_details', 'LeaveSchemeDetailsController');

Route::resource('weekend_policys', 'WeekendPoliciesController');

Route::resource('weekend_policy_details', 'WeekendPolicyDetailsController');

Route::resource('leave_reasons', 'LeaveReasonsController');

Route::resource('attendance_options', 'AttendanceOptionsController');

Route::resource('shift_sessions', 'ShiftSessionsController');

Route::resource('attendance_schedules', 'AttendanceSchedulesController');

Route::resource('attendance_policys', 'AttendancePoliciesController');

Route::resource('leave_schemes', 'LeaveSchemesController');

Route::resource('configuration_parameters', 'ConfigurationParametersController');

Route::resource('configurations', 'ConfigurationsController');

Route::resource('pf_configurations', 'PfConfigurationsController');

Route::get('pt_configurations/masters', 'PtConfigurationsController@masters');

Route::resource('pt_configurations', 'PtConfigurationsController');

Route::resource('esic_configurations', 'EsicConfigurationsController');

Route::get('lwf_configurations/masters', 'LwfConfigurationsController@masters');
Route::resource('lwf_configurations', 'LwfConfigurationsController');

Route::resource('monthly_attendances', 'MonthlyAttendancesController');

Route::get('monthly_salaries/masters', 'MonthlySalariesController@masters');
Route::post('monthly_salaries/multiple', 'MonthlySalariesController@storeMultiple');
Route::get('monthly_salaries/filter', 'MonthlySalariesController@filter');
Route::resource('monthly_salaries', 'MonthlySalariesController');
Route::get('groups/masters', 'GroupsController@masters');
Route::resource('groups', 'GroupsController');
Route::resource('feedbacks', 'FeedbacksController');
Route::get('group_divisions/masters', 'GroupDivisionsController@masters');
Route::resource('groups/{group}/group_divisions', 'GroupDivisionsController');
Route::resource('group_divisions', 'GroupDivisionsController');
Route::post('groups/{group}/group_divisions_multiple', 'GroupDivisionsController@storeMultiple');

// Upload Excell User
Route::get('crude_users', 'CrudeUsersController@index');
Route::post('upload_user', 'CrudeUsersController@uploadUser');
Route::get('process_user', 'CrudeUsersController@processUser');
Route::get('truncate_users', 'CrudeUsersController@truncate');

// Upload Excell Group
Route::get('crud_group', 'CrudgroupsController@index');
Route::post('upload_group', 'CrudgroupsController@uploadGroup');
Route::get('process_group', 'CrudgroupsController@processGroup');
Route::get('truncate_groups', 'CrudgroupsController@truncate');

// Upload Excell Group Division
Route::get('crud_group_division', 'CrudGroupDivisionController@index');
Route::post('upload_group_division', 'CrudGroupDivisionController@uploadGroupDivision');
Route::get('process_group_division', 'CrudGroupDivisionController@processGroupDivision');
Route::get('truncate_group_divisions', 'CrudGroupDivisionController@truncate');

Route::resource('posts', 'PostsController');
