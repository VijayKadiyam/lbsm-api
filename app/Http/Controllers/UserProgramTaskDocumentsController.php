<?php

namespace App\Http\Controllers;

use App\UserProgramTaskDocument;
use Illuminate\Http\Request;

class UserProgramTaskDocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['site']);
    }

    public function index(Request $request)
    {
        $count = 0;
        if ($request->search) {
            $user_program_task_documents = request()->site->user_program_task_documents()
                ->where('user_program_task_id', 'LIKE', '%' . $request->search . '%')
                ->get();
            $count = $user_program_task_documents->count();
        } else if (request()->page && request()->rowsPerPage) {
            $user_program_task_documents = request()->site->user_program_task_documents();
            $count = $user_program_task_documents->count();
            $user_program_task_documents = $user_program_task_documents->paginate(request()->rowsPerPage)->toArray();
            $user_program_task_documents = $user_program_task_documents['data'];
        } else {
            $user_program_task_documents = request()->site->user_program_task_documents;
            $count = $user_program_task_documents->count();
        }

        return response()->json([
            'data'     =>  $user_program_task_documents,
            'count'    =>   $count
        ], 200);
    }

    /*
     * To store a new userProgramTask$userProgramTaskDocument
     *
     *@
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_program_task_id'        =>  'required',
        ]);

        $userProgramTaskDocument = new UserProgramTaskDocument(request()->all());
        $request->site->user_program_task_documents()->save($userProgramTaskDocument);

        return response()->json([
            'data'    =>  $userProgramTaskDocument
        ], 201);
    }

    /*
     * To view a single userProgramTask$userProgramTaskDocument
     *
     *@
     */
    public function show(UserProgramTaskDocument $userProgramTaskDocument)
    {
        return response()->json([
            'data'   =>  $userProgramTaskDocument,
            'success' =>  true
        ], 200);
    }

    /*
     * To update a userProgramTask$userProgramTaskDocument
     *
     *@
     */
    public function update(Request $request, UserProgramTaskDocument $userProgramTaskDocument)
    {
        $userProgramTaskDocument->update($request->all());

        return response()->json([
            'data'  =>  $userProgramTaskDocument
        ], 200);
    }

    public function destroy($id)
    {
        $userProgramTaskDocument = UserProgramTaskDocument::find($id);
        $userProgramTaskDocument->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
