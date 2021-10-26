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

Route::resource('program_tasks', 'ProgramTasksController');
Route::resource('user_programs/{user_program}/user_program_tasks', 'UserProgramTasksController');
Route::post('upload_user_program_task_images', 'UploadsController@uploadUserProgramTaskImagePath');
Route::resource('user_program_tasks', 'UserProgramTasksController');
Route::post('upload_user_program_task_documents', 'UploadsController@uploadUserProgramTaskDocumentImage');
Route::resource('user_program_task_documents', 'UserProgramTaskDocumentsController');
