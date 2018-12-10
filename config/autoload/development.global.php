<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Media\Doctrine;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'host' => '127.0.0.1',
                    'user' => 'root',
                    'password' => 'root',
                    'dbname' => 'portal.dev',
                ],
            ],
        ],
    ],

    'controllers' => [
        'aliases' => [
            \MSBios\Application\Controller\IndexController::class =>
                Controller\NewsController::class
        ]
    ],

    \MSBios\Theme\Module::class => [
        'default_global_paths' => [
            'default_global_paths' => __DIR__ . '/../../vendor/msbios/cpanel/themes/'
        ]
    ],

    \MSBios\Assetic\Module::class => [
        'paths' => [
            __DIR__ . '/../../vendor/msbios/application/themes/default/public/',
        ],

        'maps' => [
            // css
            'limitless/assets/css/icons/fontawesome/styles.min.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/icons/fontawesome/styles.min.css',
            'limitless/assets/css/bootstrap.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/bootstrap.css',
            'limitless/assets/css/colors.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/colors.css',
            'limitless/assets/css/components.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/components.css',
            'limitless/assets/css/core.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/core.css',
            'limitless/assets/css/icons/icomoon/styles.css' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/icons/icomoon/styles.css',
            'limitless/assets/css/icons/icomoon/fonts/icomoon.woff' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/icons/icomoon/fonts/icomoon.woff',
            'limitless/assets/css/icons/icomoon/fonts/icomoon.ttf' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/css/icons/icomoon/fonts/icomoon.ttf',
            // js
            'limitless/assets/js/plugins/loaders/pace.min.js' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/js/plugins/loaders/pace.min.js',
            'limitless/assets/js/core/libraries/jquery.min.js' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/js/core/libraries/jquery.min.js',
            'limitless/assets/js/core/libraries/bootstrap.min.js' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/js/core/libraries/bootstrap.min.js',
            'limitless/assets/js/plugins/loaders/blockui.min.js' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/js/plugins/loaders/blockui.min.js',
            'limitless/assets/js/core/app.js' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/js/core/app.js',
            // images
            'limitless/assets/images/logo_light.png' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/images/logo_light.png',
            'limitless/assets/images/logo_light_msbios.png' =>
                __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public/assets/images/logo_light_msbios.png',
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
