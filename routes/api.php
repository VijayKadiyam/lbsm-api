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
Route::resource('assign_permissions', 'AssignPermissionsController');
Route::resource('unassign_permissions', 'UnAssignPermissionsController');

Route::get('users/masters', 'UsersController@masters');
Route::post('upload_user_image', 'UploadsController@uploadUserImage');
Route::resource('users', 'UsersController');


Route::resource('sites', 'SitesController');
Route::resource('site_user', 'siteUserController');

Route::resource('values', 'ValuesController');
Route::get('value_lists/masters', 'ValueListsController@masters');
Route::post('values/{value}/value_lists_multiple', 'ValueListsController@storeMultiple');
Route::resource('values/{value}/value_lists', 'ValueListsController');

// Upload Excell User
Route::get('crude_users', 'CrudeUsersController@index');
Route::post('upload_user', 'CrudeUsersController@uploadUser');
Route::get('process_user', 'CrudeUsersController@processUser');
Route::get('truncate_users', 'CrudeUsersController@truncate');


// // Upload Excell Group Division
// Route::get('crud_group_division', 'CrudGroupDivisionController@index');
// Route::post('upload_group_division', 'CrudGroupDivisionController@uploadGroupDivision');
// Route::get('process_group_division', 'CrudGroupDivisionController@processGroupDivision');
// Route::get('truncate_group_divisions', 'CrudGroupDivisionController@truncate');

Route::resource('programs', 'ProgramsController');
Route::get('program_posts/masters', 'ProgramPostsController@masters');
Route::post('programs/{program}/program_posts_multiple', 'ProgramPostsController@storeMultiple');
Route::resource('programs/{program}/program_posts', 'ProgramPostsController');
// Route::resource('program_posts', 'ProgramPostsController');

Route::get('user_programs/masters', 'UserProgramsController@masters');
Route::resource('user_programs', 'UserProgramsController');

Route::get('user_program_posts/masters', 'UserProgramPostsController@masters');
Route::resource('user_program_posts', 'UserProgramPostsController');
Route::resource('programs/{program}/program_tasks', 'ProgramTasksController');

Route::post('program_tasks/filter', 'ProgramTasksController@filter');
Route::resource('program_tasks', 'ProgramTasksController');
Route::post('user_programs/delete_image', 'UserProgramTasksController@DeleteImage');
Route::get('user_program_tasks/masters', 'UserProgramTasksController@masters');
Route::resource('user_programs/{user_program}/user_program_tasks', 'UserProgramTasksController');
Route::post('upload_user_program_task_images', 'UploadsController@uploadUserProgramTaskImagePath');
Route::resource('user_program_tasks', 'UserProgramTasksController');
Route::post('upload_user_program_task_documents', 'UploadsController@uploadUserProgramTaskDocumentImage');
Route::resource('user_program_task_documents', 'UserProgramTaskDocumentsController');

// User Count Route
Route::get('analytics/masters', 'AnalyticsController@masters');
Route::get('user_counts', 'AnalyticsController@userCounts');
Route::get('total_tasks_performed', 'AnalyticsController@total_tasks_performed');
Route::get('top_performers_by_average', 'AnalyticsController@top_performers_by_Average');
Route::get('top_performers_by_task', 'AnalyticsController@top_performers_by_Task');
Route::get('top_performers', 'AnalyticsController@top_performers');
Route::get('kpi_data', 'AnalyticsController@kpiData');

// Upload Excell KARCO User
Route::get('crude_karco_tasks', 'CrudeKarcoTasksController@index');
Route::post('upload_karco_task', 'CrudeKarcoTasksController@uploadKarcoTask');
Route::get('process_karco_task', 'CrudeKarcoTasksController@processKarcoTask');
Route::get('truncate_karco_tasks', 'CrudeKarcoTasksController@truncate');

// KARCO Task
Route::post('delete_karco_tasks/{id}', 'KarcoTasksController@destroy');
Route::resource('karco_tasks', 'KarcoTasksController');

// Upload Excell Videotel User
Route::get('crude_videotel_tasks', 'CrudeVideotelTasksController@index');
Route::post('upload_videotel_task', 'CrudeVideotelTasksController@uploadVideotelTask');
Route::get('process_videotel_task', 'CrudeVideotelTasksController@processVideotelTask');
Route::get('truncate_videotel_tasks', 'CrudeVideotelTasksController@truncate');

// Videotel Task
Route::post('delete_videotel_tasks/{id}', 'VideotelTasksController@destroy');
Route::resource('videotel_tasks', 'VideotelTasksController');

// User ship 
Route::get('user_ships/masters', 'UserShipsController@masters');
Route::resource('user_ships', 'UserShipsController');

// User I Test
Route::resource('user_i_tests', 'UserITestsController');