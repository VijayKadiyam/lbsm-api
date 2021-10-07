<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Product;
use App\Group;
use App\GroupDivision;
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
      $imagePath = 'users/' .  $request->userid . '/' . $name;
      Storage::disk('local')->put($imagePath, file_get_contents($file), 'public');

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

  public function uploadProductImage(Request $request)
  {
    $request->validate([
      'productid'        => 'required',
    ]);

    $imagePath = '';
    if ($request->hasFile('imagepath')) {
      $file = $request->file('imagepath');
      $name = $request->filename ?? 'photo.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath = 'product/' .  $request->productid . '/' . $name;
      Storage::disk('local')->put($imagePath, file_get_contents($file), 'public');

      $product = Product::where('id', '=', request()->productid)->first();
      $product->imagepath = $imagePath;
      $product->update();

      return response()->json([
        'data'  =>  $product,
        'message' =>  "Product Image upload Successfully",
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
      $documentImagePath = 'user-program-task-documents/' .  $request->id . '/' . $name;
      Storage::disk('local')->put($documentImagePath, file_get_contents($file), 'public');

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

  public function uploadGroupDivisionImage(Request $request)
  {
    $request->validate([
      'id'        => 'required',
    ]);

    $imagePath = '';
    if ($request->hasFile('logo_path')) {
      $file = $request->file('logo_path');
      $name = $request->filename ?? 'photo.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath = 'group_division/' .  $request->id . '/' . $name;
      Storage::disk('local')->put($imagePath, file_get_contents($file), 'public');

      $group_division = GroupDivision::where('id', '=', request()->id)->first();
      $group_division->logo_path = $imagePath;
      $group_division->update();

      return response()->json([
        'data'  =>  $group_division,
        'message' =>  "Group Division Image upload Successfully",
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
}
