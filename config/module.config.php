<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */

namespace MSBios\Deploy;

return [

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Module::class => Factory\ModuleFactory::class
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    \MSBios\Monolog\Module::class => [
        'loggers' => [
            Controller\IndexController::class => [
                'handlers' => [
                    Controller\IndexController::class
                ],
                'processors' => [
                    \Monolog\Processor\WebProcessor::class,
                    \Monolog\Processor\PsrLogMessageProcessor::class
                ]
            ],
        ],

        'handlers' => [
            Controller\IndexController::class => [
                'class' => \Monolog\Handler\StreamHandler::class,
                'args' => [
                    'stream' => './data/logs/deploy.handler.log'
                ],
                'formatter' => Controller\IndexController::class
            ],
        ],

        'formatters' => [
            Controller\IndexController::class => [
                'class' => \Monolog\Formatter\LineFormatter::class,
                'args' => [
                    'format' => "%datetime% - %channel% - %message% \n%extra% \n",
                ],
            ],
        ],
    ],

    Module::class => [
        /**
         * Enables or disables the deploy.
         *
         * Expects: bool
         * Default: false
         */
        'enabled' => false,

        /**
         * Url for request.
         *
         * Expects: string
         * Default: /d8578edf8458ce06fbc5bb76a58c5ca4
         */
        'url' => '/d8578edf8458ce06fbc5bb76a58c5ca4',

        /**
         * Configuration params
         */
        'deploy' => [

            'token' => 'some-token-key',
            'git' => '/usr/bin/git',
            'branch' => 'refs/heads/develop',
            'commiter' => 'N/A'
        ]
    ]
];
