<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 23/07/2016
 * Time: 10:17 AM
 */

namespace RobotCafe\Factory;


use RobotCafe\Mapper\RobotDbSqlMapper;
use RobotCafe\Model\Robot;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class RobotDbSqlMapperFactory implements FactoryInterface
{

    /**
     * Create robot DB query service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RobotDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(false),
            new Robot()
        );

    }
}