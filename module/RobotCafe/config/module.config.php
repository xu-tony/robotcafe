<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:42 PM
 */

return array(

    'router' => array(
        'routes' => array(
            'hello' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'RobotCafe\Controller\Index',
                        'action' => 'hello'
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'RobotCafe\Controller\Index' => 'RobotCafe\Controller\IndexController',
        ),
    ),


    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'error/404'     => __DIR__ . '/../view/error/404.phtml',
            'error/index'   => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'application' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);