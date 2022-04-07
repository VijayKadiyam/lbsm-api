<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\UserProgramTask;
use App\UserProgramTaskDocument;

class UploadsController extends Controller
{
  public function uploadUserImage(Request $request)
  {
    
    $request->validate([
      'userid'        => 'required',
    ]);

    $imagePath = '';
    if ($request->hasFile('imagepath')) {
      $file = $request->file('imagepath');
      $name = $request->filename ?? 'photo.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath = 'lbsm/users/' .  $request->userid . '/' . $name;
      Storage::disk('s3')->put($imagePath, file_get_contents($file), 'public');

      $user = User::where('id', '=', request()->userid)->first();
      $user->image_path = $imagePath;
      $user->update();

      $user->roles = $user->roles;
      $user->sites = $user->sites;

      return response()->json([
        'data'  =>  $user,
        'message' =>  "User is Logged in Successfully",
        'success' =>  true
      ], 200);
    }

    return response()->json([
      'data'  => [
        'image_path'  =>  $imagePath
      ],
      'success' =>  true
    ]);
  }

  public function uploadUserProgramTaskDocumentImage(Request $request)
  {
    $request->validate([
      'id'        => 'required',
    ]);

    $documentImagePath = '';
    if ($request->hasFile('document_path')) {
      $file = $request->file('document_path');
      $name = $request->filename ?? 'photo.';
      $name = $name . $file->getClientOriginalExtension();;
      $documentImagePath = 'lbsm/user-program-task-documents/' .  $request->id . '/' . $name;
      Storage::disk('s3')->put($documentImagePath, file_get_contents($file), 'public');

      $group = UserProgramTaskDocument::where('id', '=', request()->id)->first();
      $group->document_path = $documentImagePath;
      $group->update();

      return response()->json([
        'data'  =>  $group,
        'message' =>  "User Program Task Document Image upload Successfully",
        'success' =>  true
      ], 200);
    }

    return response()->json([
      'data'  => [
        'image_path'  =>  $documentImagePath
      ],
      'success' =>  true
    ]);
  }

  public function uploadUserProgramTaskImagePath(Request $request)
  {
    $request->validate([
      'user_program_task_id'        => 'required',
    ]);

    $imagePath1 = '';
    if ($request->hasFile('imagepath1')) {
      $file = $request->file('imagepath1');
      $name = $request->filename ?? 'imagepath1.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath1 = 'lbsm/user-program-task/' .  $request->user_program_task_id . '/' . $name;
      Storage::disk('s3')->put($imagePath1, file_get_contents($file), 'public');

      $userProgramTask = UserProgramTask::where('id', '=', request()->user_program_task_id)->first();
      $userProgramTask->imagepath1 = $imagePath1;
      $userProgramTask->update();
    }

    $imagePath2 = '';
    if ($request->hasFile('imagepath2')) {
      $file = $request->file('imagepath2');
      $name = $request->filename ?? 'imagepath2.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath2 = 'lbsm/user-program-task/' .  $request->user_program_task_id . '/' . $name;
      Storage::disk('s3')->put($imagePath2, file_get_contents($file), 'public');

      $userProgramTask = UserProgramTask::where('id', '=', request()->user_program_task_id)->first();
      $userProgramTask->imagepath2 = $imagePath2;
      $userProgramTask->update();
    }

    $imagePath3 = '';
    if ($request->hasFile('imagepath3')) {
      $file = $request->file('imagepath3');
      $name = $request->filename ?? 'imagepath3.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath3 = 'lbsm/user-program-task/' .  $request->user_program_task_id . '/' . $name;
      Storage::disk('s3')->put($imagePath3, file_get_contents($file), 'public');

      $userProgramTask = UserProgramTask::where('id', '=', request()->user_program_task_id)->first();
      $userProgramTask->imagepath3 = $imagePath3;
      $userProgramTask->update();

    }

    $imagePath4 = '';
    if ($request->hasFile('imagepath4')) {
      $file = $request->file('imagepath4');
      $name = $request->filename ?? 'imagepath4.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath4 = 'lbsm/user-program-task/' .  $request->user_program_task_id . '/' . $name;
      Storage::disk('s3')->put($imagePath4, file_get_contents($file), 'public');

      $userProgramTask = UserProgramTask::where('id', '=', request()->user_program_task_id)->first();
      $userProgramTask->imagepath4 = $imagePath4;
      $userProgramTask->update();

    }

    return response()->json([
      'data'  => [
        'image_path1'  =>  $imagePath1,
        'image_path2'  =>  $imagePath2,
        'image_path3'  =>  $imagePath3,
        'image_path4'  =>  $imagePath4
      ],
      'success' =>  true
    ]);
  }
}
