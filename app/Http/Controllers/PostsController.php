<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostHashtag;
use App\PostRelationVisibility;
use App\PostUser;
use App\PostUserVisibility;
use App\Role;
use App\Value;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'site']);
    }

    /*
   * To get all the users
   *
   *@
   */
    public function index(Request $request)
    {
        $count = 0;
        if (request()->page && request()->rowsPerPage) {
            $posts = request()->site->posts();
            $count = $posts->count();
            $posts = $posts->paginate(request()->rowsPerPage)->toArray();
            $posts = $posts['data'];
        } else {
            $posts = request()->site->posts;
            $count = $posts->count();
        }

        return response()->json([
            'data'     =>  $posts,
            'count'    =>   $count
        ], 200);
    }

    /*
   * To store a new site user
   *
   *@
   */
    public function store(Request $request)
    {
        $request->validate([
            'caption'  =>  'required',
        ]);

        if ($request->id == null || $request->id == '') {
            // Save Post
            $post = new Post(request()->all());
            $request->site->posts()->save($post);

            //   Save Post Users
            if (isset($request->post_users))
                foreach ($request->post_users as $user) {
                    $post_user = new PostUser($user);
                    $post->post_users()->save($post_user);
                }
            //   ---------------------------------------------------

            // Save Post hashtag
            if (isset($request->post_hashtags))
                foreach ($request->post_hashtags as $hashtag) {
                    $post_hashtag = new PostHashtag($hashtag);
                    $post->post_hashtags()->save($post_hashtag);
                }
            // ---------------------------------------------------

            // Save Post user_visilibities
            if (isset($request->post_user_visibilities))
                foreach ($request->post_user_visibilities as $user_visibilities) {
                    $post_user_visibilities = new PostUserVisibility($user_visibilities);
                    $post->post_user_visibilities()->save($post_user_visibilities);
                }
            // ---------------------------------------------------

            // Save Post relation_visilibities
            if (isset($request->post_relation_visibilities))
                foreach ($request->post_relation_visibilities as $relation_visibilities) {
                    $post_relation_visibilities = new PostRelationVisibility($relation_visibilities);
                    $post->post_relation_visibilities()->save($post_relation_visibilities);
                }
            // ---------------------------------------------------

            // Save Post Bunker Master
            //   if (isset($request->post_bunker_masters))
            //     foreach ($request->post_bunker_masters as $bunker_master) {
            //       $post_bunker_master = new PostBunkerMaster($bunker_master);
            //       $post->post_bunker_masters()->save($post_bunker_master);

            //       // Save Post Bunker Master Bunker
            //       $bunker_master_bunkers = $bunker_master['post_bunker_master_bunkers'];
            //       if (isset($bunker_master_bunkers))
            //       foreach ($bunker_master_bunkers as $bunker_master_bunker) {
            //         $post_bunker_master_bunker = new PostBunkerMasterBunker($bunker_master_bunker);
            //         $post_bunker_master->post_bunker_master_bunkers()->save($post_bunker_master_bunker);
            //         // dd($post_bunker_master_bunker);
            //         }
            //       // ---------------------------------------------------

            //     }
            // ---------------------------------------------------
        } else {
            // Update Post
            $post = Post::find($request->id);
            $post->update($request->all());

            //   Check if Post User deleted
            if (isset($request->post_users)) {
                $postUserIdResponseArray = array_pluck($request->post_users, 'id');
            } else
                $postUserIdResponseArray = [];
            $postId = $post->id;
            $postUserIdArray = array_pluck(PostUser::where('post_id', '=', $postId)->get(), 'id');
            $differencePostUserIds = array_diff($postUserIdArray, $postUserIdResponseArray);
            // Delete which is there in the database but not in the response
            if ($differencePostUserIds)
                foreach ($differencePostUserIds as $differencePostUserId) {
                    $postUser = PostUser::find($differencePostUserId);
                    $postUser->delete();
                }

            // Update Post User
            if (isset($request->post_users))
                foreach ($request->post_users as $user) {
                    if (!isset($user['id'])) {
                        $post_user = new PostUser($user);
                        $post->post_users()->save($post_user);
                    } else {
                        $post_user = PostUser::find($user['id']);
                        $post_user->update($user);
                    }
                }

            // ---------------------------------------------------

            // Check if Post Hashtag deleted
            if (isset($request->post_hashtags))
                $postHashtagIdResponseArray = array_pluck($request->post_hashtags, 'id');
            else
                $postHashtagIdResponseArray = [];
            $postId = $post->id;
            $postHashtagIdArray = array_pluck(PostHashtag::where('post_id', '=', $postId)->get(), 'id');
            $differencePosthashtagIds = array_diff($postHashtagIdArray, $postHashtagIdResponseArray);
            // Delete which is there in the database but not in the response
            if ($differencePosthashtagIds)
                foreach ($differencePosthashtagIds as $differencePosthashtagId) {
                    $postHashtag = PostHashtag::find($differencePosthashtagId);
                    $postHashtag->delete();
                }

            // Update Post Hashtag
            if (isset($request->post_hashtags))
                foreach ($request->post_hashtags as $hashtag) {
                    if (!isset($hashtag['id'])) {
                        $post_hashtags = new PostHashtag($hashtag);
                        $post->post_hashtags()->save($post_hashtags);
                    } else {
                        $post_hashtags = PostHashtag::find($hashtag['id']);
                        $post_hashtags->update($hashtag);
                    }
                }

            // ---------------------------------------------------
            // Check if Post User Visibility deleted
            if (isset($request->post_user_visibilities))
                $postUserVisibilityIdResponseArray = array_pluck($request->post_user_visibilities, 'id');
            else
                $postUserVisibilityIdResponseArray = [];
            $postId = $post->id;
            $postUserVisibilityIdArray = array_pluck(PostUserVisibility::where('post_id', '=', $postId)->get(), 'id');
            $differencePostuser_visibilityIds = array_diff($postUserVisibilityIdArray, $postUserVisibilityIdResponseArray);
            // Delete which is there in the database but not in the response
            if ($differencePostuser_visibilityIds)
                foreach ($differencePostuser_visibilityIds as $differencePostuser_visibilityId) {
                    $postUserVisibility = PostUserVisibility::find($differencePostuser_visibilityId);
                    $postUserVisibility->delete();
                }

            // Update Post UserVisibility
            if (isset($request->post_user_visibilities))
                foreach ($request->post_user_visibilities as $user_visibility) {
                    if (!isset($user_visibility['id'])) {
                        $post_user_visibilities = new PostUserVisibility($user_visibility);
                        $post->post_user_visibilities()->save($post_user_visibilities);
                    } else {
                        $post_user_visibilities = PostUserVisibility::find($user_visibility['id']);
                        $post_user_visibilities->update($user_visibility);
                    }
                }

            // ---------------------------------------------------
            // Check if Post Relation Visibility deleted
            if (isset($request->post_relation_visibilities))
                $postRelationVisibilityIdResponseArray = array_pluck($request->post_relation_visibilities, 'id');
            else
                $postRelationVisibilityIdResponseArray = [];
            $postId = $post->id;
            $postRelationVisibilityIdArray = array_pluck(PostRelationVisibility::where('post_id', '=', $postId)->get(), 'id');
            $differencePostrelation_visibilityIds = array_diff($postRelationVisibilityIdArray, $postRelationVisibilityIdResponseArray);
            // Delete which is there in the database but not in the response
            if ($differencePostrelation_visibilityIds)
                foreach ($differencePostrelation_visibilityIds as $differencePostrelation_visibilityId) {
                    $postRelationVisibility = PostRelationVisibility::find($differencePostrelation_visibilityId);
                    $postRelationVisibility->delete();
                }

            // Update Post RelationVisibility
            if (isset($request->post_relation_visibilities))
                foreach ($request->post_relation_visibilities as $relation_visibility) {
                    if (!isset($relation_visibility['id'])) {
                        $post_relation_visibilities = new PostRelationVisibility($relation_visibility);
                        $post->post_relation_visibilities()->save($post_relation_visibilities);
                    } else {
                        $post_relation_visibilities = PostRelationVisibility::find($relation_visibility['id']);
                        $post_relation_visibilities->update($relation_visibility);
                    }
                }

            // ---------------------------------------------------

            //   // Check if Post Bunker Master deleted
            //   if (isset($request->post_bunker_masters))
            //     $postBunkerMasterIdResponseArray = array_pluck($request->post_bunker_masters, 'id');
            //   else
            //     $postBunkerMasterIdResponseArray = [];
            //   $postId = $post->id;
            //   $postbunkerMasterIdArray = array_pluck(PostBunkerMaster::where('post_id', '=', $postId)->get(), 'id');
            //   $differencePostbunkermasterIds = array_diff($postbunkerMasterIdArray, $postBunkerMasterIdResponseArray);
            //   // Delete which is there in the database but not in the response
            //   if ($differencePostbunkermasterIds)
            //     foreach ($differencePostbunkermasterIds as $differencePostbunkermasterId) {
            //       $postBunkerMaster = PostBunkerMaster::find($differencePostbunkermasterId);
            //       $postBunkerMaster->delete();
            //     }

            //   // Update Post Bunker Master
            //   if (isset($request->post_bunker_masters))

            //     foreach ($request->post_bunker_masters as $bunkermaster) {
            //       if (!isset($bunkermaster['id'])) {
            //         $post_bunker_master = new PostBunkerMaster($bunkermaster);
            //         $post->post_bunker_masters()->save($post_bunker_master);
            //       } else {
            //         $post_bunker_master = PostBunkerMaster::find($bunkermaster['id']);
            //         $post_bunker_master->update($bunkermaster);
            //       }

            //       // Check if Post Bunker Master Bunker deleted
            //       $All_bunker_master_bunker = $bunkermaster['post_bunker_master_bunkers'];
            //       if (isset($All_bunker_master_bunker))
            //       $postBunkerMasterBunkerIdResponseArray = array_pluck($All_bunker_master_bunker, 'id');
            //       else
            //       $postBunkerMasterBunkerIdResponseArray = [];
            //       $bunkerMasterId = $post_bunker_master->id;
            //       $postbunkerMasterBunkerIdArray = array_pluck(PostBunkerMasterBunker::where('post_bunker_master_id', '=', $bunkerMasterId)->get(), 'id');
            //       $differencePostbunkermasterbunkerIds = array_diff($postbunkerMasterBunkerIdArray, $postBunkerMasterBunkerIdResponseArray);
            //       // Delete which is there in the database but not in the response
            //       if ($differencePostbunkermasterbunkerIds)
            //       foreach ($differencePostbunkermasterbunkerIds as $differencePostbunkermasterbunkerId) {
            //         $postBunkerMasterBunker = PostBunkerMasterBunker::find($differencePostbunkermasterbunkerId);
            //         $postBunkerMasterBunker->delete();
            //       }
            //       // Update Post Bunker Master Bunker
            //       if (isset($All_bunker_master_bunker))
            //       foreach ($All_bunker_master_bunker as $bunkermasterbunker) {
            //         if (!isset($bunkermasterbunker['id'])) {
            //           $post_bunker_master_bunker = new PostBunkerMasterBunker($bunkermasterbunker);
            //           $post_bunker_master->post_bunker_master_bunkers()->save($post_bunker_master_bunker);
            //         } else {
            //           $post_bunker_master_bunker = PostBunkerMasterBunker::find($bunkermasterbunker['id']);
            //           $post_bunker_master_bunker->update($bunkermasterbunker);
            //           // dd($post_bunker_master_bunker);
            //           }
            //         }
            //     }
        }
        $post->post_users = $post->post_users;
        $post->post_hashtags = $post->post_hashtags;
        $post->post_user_visibilities = $post->post_user_visibilities;
        $post->post_relation_visibilities = $post->post_relation_visibilities;
        // $post->post_bunker_masters = $post->post_bunker_masters;

        return response()->json([
            'data'    =>  $post
        ], 201);
    }

    /*
   * To view a single post
   *
   *@
   */
    public function show(Post $post)
    {
        $post->post_user = $post->post_user;
        $post->post_hashtag = $post->post_hashtag;
        $post->post_user_visibilities = $post->post_user_visibilities;
        $post->post_relation_visibilities = $post->post_relation_visibilities;

        // $post->post_bunker_master = $post->post_bunker_master;

        return response()->json([
            'data'   =>  $post,
            'success' =>  true
        ], 200);
    }

    /*
   * To update a post
   *
   *@
   */
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

        return response()->json([
            'data'  =>  $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return response()->json([
            'message' =>  'Deleted'
        ], 204);
    }
}
