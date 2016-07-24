<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 10:27 PM
 */

namespace RobotCafe\Factory;

use RobotCafe\Service\ShopService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ShopServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ShopService
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new ShopService(
            $serviceLocator->get('RobotCafe\Mapper\ShopMapperInterface')
        );
    }
}