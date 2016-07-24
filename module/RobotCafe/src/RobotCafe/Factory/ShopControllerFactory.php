<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 10:41 PM
 */

namespace RobotCafe\Factory;


use RobotCafe\Controller\ShopController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ShopControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ShopController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $shopService        = $realServiceLocator->get('RobotCafe\Service\ShopServiceInterface');
        $robotService       = $realServiceLocator->get('RobotCafe\Service\RobotServiceInterface');

        return new ShopController($shopService, $robotService);
    }
}