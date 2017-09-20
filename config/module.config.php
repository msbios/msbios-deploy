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
                    'format' => "%level_name%:%datetime% - %message%\n",
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
        'url' => '/d8578edf8458ce06fbc5bb76a58c5ca4[/]',

        /**
         * Configuration params
         *
         * Expects: array
         */
        'deploy' => [

            /**
             * Application Requests Access Token.
             *
             * Expects: string|string[]
             * Default: 6c1f79403172f56b6dc5fe0cece03cd7
             * TODO: array tokens in future
             */
            'token' => '6c1f79403172f56b6dc5fe0cece03cd7',

            /**
             * The beginning of Git file
             *
             * Expects: string
             * Default: /usr/bin/git
             */
            'git' => '/usr/bin/git',

            /**
             *
             * Expects: string
             * Default: refs/heads/master
             */
            'branch' => 'refs/heads/master',

            /**
             *
             * Expects: string
             * Default: N/A
             */
            'commiter' => 'N/A',

            /**
             *
             * Expects: string
             * Default: '%bash% pull origin %branch% 2>&1'
             * TODO: in future
             */
            'command' => null
        ]
    ]
];
