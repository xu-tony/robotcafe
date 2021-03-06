<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:25 PM
 */

namespace RobotCafeTest\Mapper;


use Mockery;
use PHPUnit_Framework_TestCase;
use RobotCafe\Mapper\RobotDbSqlMapper;
use RobotCafe\Model\Robot;
use Zend\Stdlib\Hydrator\ClassMethods;

class RobotDbSqlMapperTest extends PHPUnit_Framework_TestCase
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
                ->once()
                ->andReturn($functionMap['current']);
        }

        return $mockDbAdapter;
    }

    /**
     * test the robot add function
     */
    public function testAdd()
    {
        $functionMap = array(
            'getGeneratedValue' => 1
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);

        $robot = new Robot();
        $hydrator = new ClassMethods(false);

        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, new Robot());
        $robot = new Robot();
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $id = $robotDbSqlMapper->add($robot);

        $this->assertEquals($id, 1);
    }

    /**
     * test the findBySIdAndRId
     */
    public function testFindBySIdAndRId()
    {
        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $functionMap = array(
            'isQueryResult' => true,
            'getAffectedRows' => 1,
            'current' => $robot->toArray()
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);

        $hydrator = new ClassMethods(false);

        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, new Robot());
        $id = 1;
        $rid = 2;

        $findRobot = $robotDbSqlMapper->findBySIdAndRId($id, $rid);

        $this->assertEquals($robot, $findRobot);
    }

    /**
     * test update
     */
    public function testUpdate(){

        $robot = new Robot();
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $mockDbAdapter = $this->getAdapterMock(array());
        $hydrator = new ClassMethods(false);
        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, new Robot());
        $updated = $robotDbSqlMapper->update($robot);

        $this->assertTrue($updated);
    }

    /**
     * test delete
     */
    public function testDelete(){
        $functionMap = array(
            'getAffectedRows' => 1
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);
        $hydrator = new ClassMethods(false);
        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, new Robot());

        $deleted = $robotDbSqlMapper->delete($id = 1);

        $this->assertTrue($deleted);
    }

    /**
     * test findByRobotCoordinate
     */
    public function testFindByRobotCoordinate(){
        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $functionMap = array(
            'isQueryResult' => true,
            'getAffectedRows' => 1,
            'current' => $robot->toArray()
        );
        $mockDbAdapter = $this->getAdapterMock($functionMap);

        $hydrator = new ClassMethods(false);

        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, new Robot());
        $id = 1;
        $x =1;
        $y = 1;
        $findRobot = $robotDbSqlMapper->findByRobotCoordinate($x, $y, $id);

        $this->assertEquals($robot, $findRobot);
    }

}