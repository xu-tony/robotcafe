<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 10:29 PM
 */

namespace RobotCafe\Factory;

use RobotCafe\Service\RobotService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RobotServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RobotService
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new RobotService(
            $serviceLocator->get('RobotCafe\Mapper\RobotMapperInterface')
        );
    }
}