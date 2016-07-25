<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 25/07/2016
 * Time: 8:25 AM
 */

namespace RobotCafeTest\Mapper;


use Mockery;
use PHPUnit_Framework_TestCase;

Abstract class AbstractDbSqlTestCase extends PHPUnit_Framework_TestCase
{

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

}