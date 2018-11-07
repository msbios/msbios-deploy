<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */

namespace MSBios\Deploy;

use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;

return [

    'router' => [
        'routes' => [
            'home' => [
                'may_terminate' => true,
                'child_routes' => [
                    'deploy' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'deploy[/]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            DeployManager::class =>
                Factory\DeployManagerFactory::class,
            Adapter\GitLab::class =>
                Factory\GitLabAdapterFactory::class,
            Module::class =>
                Factory\ModuleFactory::class
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
         * Expects: string
         * Default: AdapterInterface
         */
        'adapter' => AdapterInterface::class,

        /**
         * Application Requests Access Token.
         *
         * Expects: string|string[]
         * Default: 6c1f79403172f56b6dc5fe0cece03cd7
         * TODO: array tokens in future
         */
        'token' => '',

        /**
         *
         * Expects: array
         * Default: []
         */
        'commands' => [
            [
                'type' => Command::class,
                'options' => [
                    'command' => "/usr/bin/git pull origin develop 2>&1",
                ]
            ],
            [
                'type' => Shell::class,
                'options' => [
                    'command' => "/usr/bin/git pull origin develop 2>&1",
                ]
            ]
        ]
    ]
];
