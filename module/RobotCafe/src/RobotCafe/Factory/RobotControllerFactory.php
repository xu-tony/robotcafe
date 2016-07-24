<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 10:36 PM
 */

namespace RobotCafe\Factory;

use RobotCafe\Controller\RobotController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RobotControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RobotController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $shopService        = $realServiceLocator->get('RobotCafe\Service\ShopServiceInterface');
        $robotService       = $realServiceLocator->get('RobotCafe\Service\RobotServiceInterface');

        return new RobotController($shopService, $robotService);
    }
}