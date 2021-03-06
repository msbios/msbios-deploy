<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */

namespace MSBios\Deploy;

use MSBios\Deploy\Adapter\GitLab;

return [
    \MSBios\Assetic\Module::class => [
        'maps' => [
            // css
            'default/css/bootstrap.min.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/bootstrap.min.css',
            'default/css/bootstrap-theme.min.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/bootstrap-theme.min.css',
            'default/css/style.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/style.css',
            // js
            'default/js/jquery-3.1.0.min.js' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/js/jquery-3.1.0.min.js',
            'default/js/bootstrap.min.js' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/js/bootstrap.min.js',
            // imgs
            'default/img/zf-logo-mark.svg' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/img/zf-logo-mark.svg',
        ],
    ],

    Module::class => [
        'adapter' => GitLab::class,
        'token' => 'ed076287532e86365e841e92bfc50d8c',
        'commands' => [
            'Code Pull Operation' => [
                'type' => Command::class,
                'options' => [
                    'command' => "/usr/bin/git pull origin master 2>&1",
                ]
            ],
            'Test Call Bash Script' => [
                'type' => Shell::class,
                'options' => [
                    'command' => "bash msbios-deploy.sh 2>&1",
                ]
            ]
        ]
    ]
];
