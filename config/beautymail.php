<?php

return [

    // These CSS rules will be applied after the regular template CSS


        'css' => [
            'img[alt="logo"] { background: red }',
        ],

    'colors' => [

        'highlight' => '#0b7685',
        'button'    => '#0b7685',

    ],

    'view' => [
        'senderName'  => null,
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,
        'css' => file_get_contents(dirname(__FILE__) . '/../public/css/app.css'),

        'logo'        => [
            'path'   =>  '',
            'width'  => '50px',
            'height' => '50px',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
