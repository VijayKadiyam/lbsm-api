<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Site;
use App\Post;
use App\PostHashtag;
use App\PostUser;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(Post::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'caption' => 'caption',
            'moment_id' => '1',
            'event_id' => '1',
            'event_date' => 'event_date',
            'posted_by' => '1',
            'visibility' => '0',
            'post_users' =>  [
                0 =>  [
                    'user_id' =>  '1',
                ]
            ],
            'post_hashtags' =>  [
                0 =>  [
                    'hashtag' =>  'hashtag',
                ]
            ],
            'post_user_visibilities' =>  [
                0 =>  [
                    'user_id' =>  '1',
                ]
            ],
            'post_relation_visibilities' =>  [
                0 =>  [
                    'relation_id' =>  '1',
                ]
            ],
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/posts', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "caption"    =>  ["The caption field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_Post()
    {
        $this->disableEH();
        $this->json('post', '/api/posts', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'caption' => 'caption',
                    'moment_id' => '1',
                    'event_id' => '1',
                    'event_date' => 'event_date',
                    'posted_by' => '1',
                    'visibility' => '0',
                    'post_users' =>  [
                        0 =>  [
                            'user_id' =>  '1',
                        ]
                    ],
                    'post_hashtags' =>  [
                        0 =>  [
                            'hashtag' =>  'hashtag',
                        ]
                    ],
                    'post_user_visibilities' =>  [
                        0 =>  [
                            'user_id' =>  '1',
                        ]
                    ],
                    'post_relation_visibilities' =>  [
                        0 =>  [
                            'relation_id' =>  '1',
                        ]
                    ],
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'caption',
                    'moment_id',
                    'event_id',
                    'event_date',
                    'posted_by',
                    'visibility',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id',
                    'post_users',
                    'post_hashtags',
                    'post_user_visibilities',
                    'post_relation_visibilities',
                ]
            ]);
    }

    /** @test */
    function list_of_Posts()
    {
        $this->disableEH();
        $this->json('GET', '/api/posts', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'caption',
                        'moment_id',
                        'event_id',
                        'event_date',
                        'posted_by',
                        'visibility',
                    ]
                ]
            ]);
        $this->assertCount(1, Post::all());
    }

    /** @test */
    function show_single_Post()
    {

        $this->json('get', "/api/posts/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'caption' => 'caption',
                    'moment_id' => '1',
                    'event_id' => '1',
                    'event_date' => 'event_date',
                    'posted_by' => '1',
                    'visibility' => '0',
                ]
            ]);
    }

    /** @test */
    function update_single_Post()
    {
        $this->disableEH();

        $post = factory(Post::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $postUser = factory(PostUser::class)->create([
            'post_id' =>  $post->id
        ]);
        $postHashtag = factory(PostHashtag::class)->create([
            'post_id' =>  $post->id
        ]);
        // $postBunkerMaster = factory(PostBunkerMaster::class)->create([
        //     'post_id' =>  $post->id
        // ]);
        // $postBunkerMasterBunker = factory(PostBunkerMasterBunker::class)->create([
        //     'post_bunker_master_id' => $postBunkerMaster->id
        // ]);

        // Old Edit + No Delete + 1 New
        $payload = [
            'caption' => 'caption 2',
            'moment_id' => '1',
            'event_id' => '1',
            'event_date' => 'event_date 2',
            'posted_by' => '1',
            'visibility' => '0',
            'post_users' =>  [
                0 =>  [
                    // 'id'        =>  $postUser->id,
                    'user_id' =>  '1'
                ],
                1 =>  [
                    'user_id' =>  '2'
                ]
            ],
            'post_hashtags' =>  [
                0 =>  [
                    // 'id'          =>  $postHashtag->id,
                    'hashtag'  =>  "hashtag"
                ],
                1 =>  [
                    'hashtag'  =>  "hashtag"
                ]
            ],
            'post_user_visibilities' =>  [
                0 =>  [
                    'user_id' =>  '1'
                ],
                1 =>  [
                    'user_id' =>  '2'
                ]
            ],
            'post_relation_visibilities' =>  [
                0 =>  [
                    'relation_id' =>  '1'
                ],
                1 =>  [
                    'relation_id' =>  '2'
                ]
            ],
            // 'post_bunker_masters' =>  [
            //     0 =>  [
            //         'id'          =>  $postBunkerMaster->id,
            //         'fuel_type_id'  =>  1,
            //         'post_bunker_master_bunkers' => [
            //             0 => [
            //                 'id'          =>  $postBunkerMasterBunker->id,
            //                 'post_bunker_master_id' => 1,
            //                 'hashtag' => "hashtag",
            //             ]
            //         ]

            //     ],
            //     1 =>  [
            //         'fuel_type_id'  =>  2,
            //         'post_bunker_master_bunkers' => [
            //             0 => [
            //                 'post_bunker_master_id' => 2,
            //                 'hashtag' => "hashtag",
            //             ]
            //         ]
            //     ]
            // ]
        ];

        $this->json('post', '/api/posts', $payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'    => [
                    'caption' => 'caption 2',
                    'moment_id' => '1',
                    'event_id' => '1',
                    'event_date' => 'event_date 2',
                    'posted_by' => '1',
                    'visibility' => '0',
                    'post_users' =>  [
                        0 =>  [
                            // 'id'        =>  $postUser->id,
                            'user_id' => '1'
                        ],
                        1 =>  [
                            'user_id' =>  '2'
                        ]
                    ],
                    'post_hashtags' =>  [
                        0 =>  [
                            // 'id'          =>  $postHashtag->id,
                            'hashtag'  =>  "hashtag"
                        ],
                        1 =>  [
                            'hashtag'  =>  "hashtag"
                        ]
                    ],
                    'post_user_visibilities' =>  [
                        0 =>  [
                            // 'id'        =>  $postUser->id,
                            'user_id' =>  '1'
                        ],
                        1 =>  [
                            'user_id' =>  '2'
                        ]
                    ],
                    'post_relation_visibilities' =>  [
                        0 =>  [
                            'relation_id' =>  '1'
                        ],
                        1 =>  [
                            'relation_id' =>  '2'
                        ]
                    ],
                    // 'post_bunker_masters' =>  [
                    //     0 =>  [
                    //         'id'          =>  $postBunkerMaster->id,
                    //         'fuel_type_id'  =>  1,
                    //         'post_bunker_master_bunkers' => [
                    //             0 => [
                    //                 'id'          =>  $postBunkerMasterBunker->id,
                    //                 'post_bunker_master_id' => 1,
                    //                 'hashtag'==> "hashtag",
                    //             ]
                    //         ]

                    //     ],
                    //     1 =>  [
                    //         'fuel_type_id'  =>  2,
                    //         'post_bunker_master_bunkers' => [
                    //             0 => [
                    //                 'post_bunker_master_id' => 2,
                    //                 'hashtag' => "hashtag",
                    //             ]
                    //         ]
                    //     ]
                    // ]
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'caption',
                    'moment_id',
                    'event_id',
                    'event_date',
                    'posted_by',
                    'visibility',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id',
                    'post_users',
                    'post_hashtags',
                    'post_user_visibilities',
                    'post_relation_visibilities',
                    // 'post_bunker_masters'
                ]
            ]);

        // 1 Delete + 1 New
        $payload = [
            'caption' => 'caption 2',
            'moment_id' => '1',
            'event_id' => '1',
            'event_date' => 'event_date 2',
            'posted_by' => '1',
            'visibility' => '0',
            'post_users' =>  [
                0 =>  [
                    // 'id'        =>  $postUser->id,
                    'user_id' =>  '1'
                ]
            ],
            'post_hashtags' =>  [
                0 =>  [
                    // 'id'          =>  $postHashtag->id,
                    'hashtag'  =>  "hashtag"
                ]
            ],
            'post_user_visibilities' =>  [
                0 =>  [
                    // 'id'        =>  $postUser->id,
                    'user_id' =>  '1'
                ],
            ],
            'post_relation_visibilities' =>  [
                0 =>  [
                    'relation_id' =>  '1',
                ]
            ],
            // 'post_bunker_masters' =>  [
            //     0 =>  [
            //         'id'          =>  $postBunkerMaster->id,
            //         'fuel_type_id'  =>  1,
            //         'post_bunker_master_bunkers' => [
            //             0 => [
            //                 'id'          =>  $postBunkerMasterBunker->id,
            //                 'post_bunker_master_id' => 1,
            //                 'hashtag' => "hashtag",
            //             ]
            //         ]
            //     ]
            // ]
        ];

        $this->json('post', '/api/posts', $payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'    => [
                    'caption' => 'caption 2',
                    'moment_id' => '1',
                    'event_id' => '1',
                    'event_date' => 'event_date 2',
                    'posted_by' => '1',
                    'visibility' => '0',
                    'post_users' =>  [
                        0 =>  [
                            // 'id'        =>  $postUser->id,
                            'user_id' =>  '1'
                        ]
                    ],
                    'post_hashtags' =>  [
                        0 =>  [
                            // 'id'          =>  $postHashtag->id,
                            'hashtag'  =>  "hashtag"
                        ]
                    ],
                    'post_user_visibilities' =>  [
                        0 =>  [
                            // 'id'        =>  $postUser->id,
                            'user_id' =>  '1'
                        ],
                    ],
                    'post_relation_visibilities' =>  [
                        0 =>  [
                            // 'id'        =>  $postUser->id,
                            'relation_id' =>  '1'
                        ],
                    ],
                    // 'post_bunker_masters' =>  [
                    //     0 =>  [
                    //         'id'          =>  $postBunkerMaster->id,
                    //         'fuel_type_id'  =>  1
                    //     ]
                    // ]
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'caption',
                    'moment_id',
                    'event_id',
                    'event_date',
                    'posted_by',
                    'visibility',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id',
                    'post_users',
                    'post_hashtags',
                    'post_user_visibilities',
                    'post_relation_visibilities',
                    // 'post_bunker_masters'
                ]
            ]);
    }

    /** @test */
    function delete_Post()
    {
        $this->json('delete', '/api/posts/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, Post::all());
    }
}
