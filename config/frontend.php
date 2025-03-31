<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Frontend URLs
    |--------------------------------------------------------------------------
    |
    | These values determine the URLs for the frontend application
    | that will be used for redirects from the backend.
    |
    */

    'url' => env('FRONTEND_URL', 'http://localhost:3000'),
    'reset_password_url' => env('FRONTEND_RESET_PASSWORD_URL', env('FRONTEND_URL', 'http://localhost:3000').'/reset-password'),
];
