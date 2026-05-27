<?php

return [
    'providers' => [
        'google' => [
            'label' => 'Google',
            'enabled' => env('GOOGLE_OAUTH_ENABLED', true),
            'notes' => 'Roadmap provider for Google account login and signup.',
        ],
        'github' => [
            'label' => 'GitHub',
            'enabled' => env('GITHUB_OAUTH_ENABLED', true),
            'notes' => 'Roadmap provider for GitHub account login, signup, and future team metadata.',
        ],
    ],
];
