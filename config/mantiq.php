<?php

return [

    'admin_email' => env('MANTIQ_ADMIN_EMAIL'),

    // Seed demo courses, lessons and demo accounts (never enable in real production).
    'seed_demo' => (bool) env('MANTIQ_SEED_DEMO', false),

    /*
    |--------------------------------------------------------------------------
    | Content Tracks
    |--------------------------------------------------------------------------
    */

    'tracks' => [

        'tech' => [
            'course',
        ],

        'business' => [
            'course',
            'book',
            'article',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Standalone Content Types
    |--------------------------------------------------------------------------
    */

    'standalone_types' => [
        'podcast',
    ],

];
