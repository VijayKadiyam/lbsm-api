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


Route::resource('sites', 'SitesController');
Route::resource('site_user', 'siteUserController');


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
Route::resource('program_posts', 'ProgramPostsController');
Route::resource('user_programs', 'UserProgramsController');
Route::resource('user_program_posts', 'UserProgramPostsController');
Route::resource('program_tasks', 'ProgramTasksController');
Route::resource('user_program_tasks', 'UserProgramTasksController');
Route::resource('user_program_task_documents', 'UserProgramTaskDocumentsController');
