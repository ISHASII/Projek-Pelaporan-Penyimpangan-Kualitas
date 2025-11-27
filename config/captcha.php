<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['0','1','2','3','4','5','6','7','8','9'],
    'default' => [
        'length' => 7,
        'width' => 140,
        'height' => 36,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
    'length' => 5,
    'width' => 220,
    'height' => 80,
    'quality' => 50,
    'lines' => 3,
    'bgImage' => true,
    'bgColor' => '#ffffff',
    'fontColors' => ['#000000'],
    'contrast' => 10,
    'sharpen' => 10,
    'blur' => 3,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ]
];
