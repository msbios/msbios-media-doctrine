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

                    'news' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'news[/]',
                            'defaults' => [
                                'controller' => Controller\NewsController::class,
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
            'media.rpc.news' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/news.:format',
                    'defaults' => [
                        'controller' => V1\Rpc\News\NewsController::class,
                        'action' => 'news'
                    ],
                    'constraints' => [
                        'format' => '(json|xml)?',
                    ]
                ],
            ],

        ],
    ],

    'controllers' => [
        'factories' => [
            // MVC
            Controller\NewsController::class =>
                Factory\NewsControllerFactory::class,

            // Rpc
            V1\Rpc\News\NewsController::class =>
                V1\Rpc\News\NewsControllerFactory::class
        ]
    ],

    'form_elements' => [
        'factories' => [
            Form\NewsForm::class =>
                InvokableFactory::class
        ]
    ],
];
