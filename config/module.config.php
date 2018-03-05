<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Media\Doctrine;

use Zend\Router\Http\Regex;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'router' => [
        'routes' => [
            'home' => [
                'may_terminate' => true,
                'child_routes' => [

                    'media' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'media[/]',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'view' => [
                                'type' => Regex::class,
                                'options' => [
                                    'regex' => '(?<id>[\d]+)-(?<slug>[a-zA-Z-_\d]+)\.html',
                                    'spec' => '%id%-%slug%.html',
                                    'defaults' => [
                                        'action' => 'view'
                                    ]
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    // ...
                                ]
                            ],
                        ]
                    ],

                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                InvokableFactory::class,
        ]
    ],

    'controllers' => [
        'factories' => [

        ],
        'aliases' => [
        ]
    ],

    'service_manager' => [
        'factories' => [

        ],
        'aliases' => [

        ]
    ]

];
