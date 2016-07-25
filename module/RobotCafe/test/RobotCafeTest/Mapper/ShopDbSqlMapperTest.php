<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 25/07/2016
 * Time: 9:06 AM
 */

namespace RobotCafeTest\Mapper;


use Mockery;
use PHPUnit_Framework_TestCase;
use RobotCafe\Mapper\RobotDbSqlMapper;
use RobotCafe\Mapper\ShopDbSqlMapper;
use RobotCafe\Model\Robot;
use RobotCafe\Model\Shop;
use Zend\Stdlib\Hydrator\ClassMethods;

class ShopDbSqlMapperTest extends PHPUnit_Framework_TestCase
{
    // build the mocked adapter
    protected function getAdapterMock($functionMap){
        $driver = Mockery::mock('Zend\Db\Adapter\Driver\DriverInterface');
        $resultSet = Mockery::mock('Zend\Db\Adapter\Driver\Pdo\Result');
        $mockDbAdapter = Mockery::mock('Zend\Db\Adapter\Adapter');
        $platform = Mockery::mock('Zend\Db\Adapter\Platform\Mysql[getName]');
        $statement = Mockery::mock('Zend\Db\Adapter\Driver\Pdo\Statement');
        $paramContainer = Mockery::mock('Zend\Db\Adapter\ParameterContainer');

        $driver->shouldReceive('createStatement')
            ->once()
            ->andReturn($statement);

        $driver->shouldReceive('formatParameterName')
            ->once()
            ->andReturn('?');

        $platform->shouldReceive('getName')
            ->once()
            ->andReturn('MySQL');

        $mockDbAdapter->shouldReceive('getDriver')
            ->once()
            ->andReturn($driver);

        $mockDbAdapter->shouldReceive('getPlatform')
            ->once()
            ->andReturn($platform);

        $platform->shouldReceive('prepareStatement')
            ->once()
            ->andReturn($statement);

        $statement->shouldReceive('setSql')
            ->once()
            ->andReturn($statement);

        $statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultSet);

        $statement->shouldReceive('getParameterContainer')
            ->once()
            ->andReturn($paramContainer);

        $paramContainer->shouldReceive('offsetSet')
            ->once()
            ->andReturn(array());

        $paramContainer->shouldReceive('merge')
            ->once()
            ->andReturn(null);

        if (array_key_exists('getGeneratedValue', $functionMap)) {
            $resultSet->shouldReceive('getGeneratedValue')
                ->once()
                ->andReturn($functionMap['getGeneratedValue']);
        }

        if (array_key_exists('isQueryResult', $functionMap)) {
            $resultSet->shouldReceive('isQueryResult')
                ->once()
                ->andReturn($functionMap['isQueryResult']);
        }

        if (array_key_exists('getAffectedRows', $functionMap)) {
            $resultSet->shouldReceive('getAffectedRows')
                ->once()
                ->andReturn($functionMap['getAffectedRows']);
        }

        if (array_key_exists('current', $functionMap)) {
            $resultSet->shouldReceive('current')
                ->atLeast()
                ->andReturn($functionMap['current']);
        }

        return $mockDbAdapter;
    }

    /**
     * test addshop
     */
    public function testAddShop(){
        $functionMap = array(
            'getGeneratedValue' => 1
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);

        $hydrator = new ClassMethods(false);

        $shopDbSqlMapper = new ShopDbSqlMapper($mockDbAdapter, $hydrator, new Shop());
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $id = $shopDbSqlMapper->addShop($shop);
        $this->assertEquals($id, 1);
    }

    /**
     * test deleteshop
     */
    public function testDelete(){
        $functionMap = array(
            'getAffectedRows' => 1
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);
        $hydrator = new ClassMethods(false);
        $shopDbSqlMapper = new ShopDbSqlMapper($mockDbAdapter, $hydrator, new Shop());

        $deleted = $shopDbSqlMapper->deleteShop($id = 1);

        $this->assertTrue($deleted);
    }

    /**
     * test findshop
     */
    /*public function testFindShop(){
        $shop = new Shop();
        $shop->setHeight(6);
        $shop->setWidth(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $robot2 = new Robot();
        $robot2->setX(3);
        $robot2->setY(2);
        $robot2->setHeading(Robot::SOUTH);
        $robot2->setCommands('MMLMRMM');

        $shop->addRobot($robot);
        $shop->addRobot($robot2);

        $functionMap = array(
            'isQueryResult' => true,
            'getAffectedRows' => 1,
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);

        $hydrator = new ClassMethods(false);

        $shopDbSqlMapper = new ShopDbSqlMapper($mockDbAdapter);
        $id = 1;

        $findShop = $shopDbSqlMapper->findShop($id);

        $this->assertEquals($shop, $findShop);
    }*/



}