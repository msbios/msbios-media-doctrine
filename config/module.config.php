<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine;

use Zend\Router\Http\Regex;
use Zend\Router\Http\Segment;

return [

    'router' => [
        'routes' => [
            'home' => [
                'may_terminate' => true,
                'child_routes' => [

                    'media' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'news[/]',
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
            'media.rest.news' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/news[/:id][.:format]',
                    'defaults' => [
                        'controller' => V1\Rest\News\NewsResource::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+',
                        'format' => '(json|xml)?',
                    ]
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
            V1\Rest\News\NewsResource::class =>
                Factory\NewsResourceFactory::class
        ]
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
