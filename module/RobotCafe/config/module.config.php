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
                        'controller' => 'RobotCafe\Controller\Shop',
                        'action' => 'hello'
                    ),
                ),
            ),
            'shop' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/shop',
                    'defaults' => array(
                        'controller' => 'RobotCafe\Controller\Shop',
                        'action' => 'add'
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'getOrDelete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '[/:id]', // match /shop/:id
                            'constraints' => array(
                                'id' => '[1-9]\d*'
                            ),
                            'defaults' => array(
                                'action' => 'getOrDelete'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes'  => array(
                            'execute' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route'    => '/execute', // match /shop/:id/execute
                                    'defaults' => array(
                                        'action' => 'execute'
                                    )
                                )
                            ),
                            'robot' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route'    => '/robot', // match /shop/:id/robot
                                    'defaults' => array(
                                        'controller' => 'RobotCafe\Controller\Robot',
                                        'action' => 'add'
                                    )
                                ),
                                'may_terminate' => true,
                                'child_routes'  => array(
                                    'updateOrDelete' => array(
                                        'type' => 'segment',
                                        'options' => array(
                                            'route'    => '[/:rid]', // match /shop/:id/robot/:rid
                                            'constraints' => array(
                                                'rid' => '[1-9]\d*'
                                            ),
                                            'defaults' => array(
                                                'action' => 'updateOrDelete'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),

    'controllers' => array(
        'factories' => array(
            'RobotCafe\Controller\Shop'  => 'RobotCafe\Factory\ShopControllerFactory',
            'RobotCafe\Controller\Robot' => 'RobotCafe\Factory\RobotControllerFactory',
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'RobotCafe\Service\ShopServiceInterface' => 'RobotCafe\Factory\ShopServiceFactory',
            'RobotCafe\Service\RobotServiceInterface'=> 'RobotCafe\Factory\RobotServiceFactory',
            'RobotCafe\Mapper\ShopMapperInterface'   => 'RobotCafe\Factory\ShopDbSqlMapperFactory',
            'RobotCafe\Mapper\RobotMapperInterface'  => 'RobotCafe\Factory\RobotDbSqlMapperFactory',
            'Zend\Db\Adapter\Adapter'                => 'Zend\Db\Adapter\AdapterServiceFactory',
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