<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:25 PM
 */

namespace RobotCafeTestTest\Mapper;


use PHPUnit_Framework_TestCase;
use RobotCafe\Mapper\RobotDbSqlMapper;
use RobotCafe\Model\Robot;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\ClassMethods;
use Mockery;

class RobotDbSqlMapperTest //extends PHPUnit_Framework_TestCase
{

    private $myService;
    private $typeaffaireTable;

    private $mockDriver;
    private $mockConnection;
    private $mockPlatform;
    private $mockStatement;
    private $adapter;
    private $sql;





    public function testAdd(){


        $mockDbAdapter = Mockery::mock('Zend\Db\Adapter\Adapter');

        $robot = new Robot();
        $hydrator = new ClassMethods(false);

        $robotDbSqlMapper = new RobotDbSqlMapper($mockDbAdapter, $hydrator, $robot);
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $id = $robotDbSqlMapper->add($robot);

        $this->assertEquals($id, 1);
    }

}