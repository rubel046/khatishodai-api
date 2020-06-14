<?php

return [

    'facebook' => [
        'client_id'     => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI'),
    ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_CALLBACK_URL'),
    ],

    'siteUrl'=>env('SITE_URL', 'http://tizaara.com')

];
