<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 5:00 PM
 */

namespace RobotCafeTestTest\Model;


use Exception;
use PHPUnit_Framework_TestCase;
use RobotCafe\Model\Robot;

class RobotTest extends PHPUnit_Framework_TestCase
{

    /**
     * initial object test
     */
    public function testRobotInitialState()
    {
        $robot = new Robot();

        $this->assertNull(
            $robot->getId(),
            'id should initially be null'
        );
        $this->assertNull(
            $robot->getX(),
            '"x" should initially be null'
        );
        $this->assertNull(
            $robot->getY(),
            '"y" should initially be null'
        );
        $this->assertNull(
            $robot->getHeading(),
            '"heading" should initially be null'
        );
        $this->assertNull(
            $robot->getCommands(),
            '"commands" should initially be null'
        );
        $this->assertNull(
            $robot->getSid(),
            '"sid" should initially be null'
        );
    }

    /**
     * test getId()
     */
    public function testRobotSetGetId(){
        $robot = new Robot();
        $id = 1;
        $robot->setId($id);
        $this->assertEquals(
            $robot->getId(), $id);
        $id = null;
        $robot->setId($id);
        $this->assertNull(
            $robot->getSid());
    }

    /**
     * test getSid()
     */
    public function testRobotSetGetSid(){
        $robot = new Robot();
        $sid = 2;
        $robot->setSid($sid);
        $this->assertEquals(
            $robot->getSid(), $sid);
        $sid = null;
        $robot->setSid(null);
        $this->assertNull(
            $robot->getSid());
    }


    /**
     * test getHeading()
     */
    public function testRobotSetGetHeading(){
        $robot = new Robot();
        $heading = Robot::NORTH;
        $robot->setHeading($heading);
        $this->assertEquals(
            $robot->getHeading(), $heading);
    }

    /**
     * test getCommands()
     */
    public function testRobotSetGetCommands(){
        $robot = new Robot();
        $commands = 'LLMM';
        $robot->setCommands($commands);
        $this->assertEquals(
            $robot->getCommands(), $commands);
    }

    /**
     * test getX()
     */
    public function testRobotGetX(){
        $robot = new Robot();
        $x = 2;
        $robot->setX($x);
        $this->assertEquals(
            $robot->getX(), $x);
    }

    /**
     * test getY()
     */
    public function testRobotGetY(){
        $robot = new Robot();
        $y = 3;
        $robot->setY($y);
        $this->assertEquals(
            $robot->getY(), $y);
    }

    /**
     * test toArray()
     */
    public function testToArray(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLLRRMM');

        $robotArray = $robot->toArray();

        $this->assertArrayHasKey(
            'x', $robotArray);
        $this->assertArrayHasKey(
            'y', $robotArray);
        $this->assertArrayHasKey(
            'heading', $robotArray);
        $this->assertArrayHasKey(
            'commands', $robotArray);
        $this->assertArrayNotHasKey(
            'sid', $robotArray);

        $this->assertEquals(
            $robotArray,
            array(
                'id' => 3,
                'x' => 1,
                'y' => 2,
                'heading' => Robot::SOUTH,
                'commands'=> 'MMLLRRMM'
            )
        );
    }

    /**
     * test move forward function
     */
    public function testMoveForward(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $robot->moveForward();

        $this->assertEquals(
            $robot->getX(), 1);
        $this->assertEquals(
            $robot->getY(), 3);
        $this->assertEquals(
            $robot->getHeading(), Robot::SOUTH);

    }

    /**
     * test MoveForwardCoordinateException
     * @throws Exception
     */
    public function testMoveForwardCoordinateException(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(-2);
        $robot->setHeading(Robot::NORTH);
        $robot->setCommands('MMLMRMM');
        $this->expectException(Exception::class);
        $robot->moveForward();
    }

    /**
     * @throws Exception
     */
    public function testMoveForwardHeadingException(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(-1);
        $robot->setY(2);
        $robot->setHeading('wrong heading');
        $robot->setCommands('MMLMRMM');
        $this->expectException(Exception::class);
        $robot->moveForward();
    }


    /**
     * test Turn function
     */
    public function testTurn(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $robot->turn(Robot::LEFT);

        $this->assertEquals($robot->getHeading(), Robot::EAST);
        $this->assertEquals($robot->getX(), 1);
        $this->assertEquals($robot->getY(), 2);
    }

    /**
     * test Turn function throw exception
     * @throws Exception
     */
    public function testTurnException(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading('wrong heading');
        $robot->setCommands('MMLMRMM');
        $this->expectException(Exception::class);
        $robot->turn(Robot::LEFT);
    }

    public function testExecuteCommand(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading('E');
        $robot->setCommands('MMLMRMM');

        $robot->executeCommand(Robot::LEFT);

        $this->assertEquals(
            $robot->getX(), 1);
        $this->assertEquals(
            $robot->getY(), 2);
        $this->assertEquals(
            $robot->getHeading(), Robot::NORTH);

        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading('E');
        $robot->setCommands('MMLMRMM');

        $robot->executeCommand(Robot::RIGHT);

        $this->assertEquals(
            $robot->getX(), 1);
        $this->assertEquals(
            $robot->getY(), 2);
        $this->assertEquals(
            $robot->getHeading(), Robot::SOUTH);


        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading('E');
        $robot->setCommands('MMLMRMM');

        $robot->executeCommand(Robot::MOVE);

        $this->assertEquals(
            $robot->getX(), 2);
        $this->assertEquals(
            $robot->getY(), 2);
        $this->assertEquals(
            $robot->getHeading(), Robot::EAST);

    }

    public function testExecuteCommandException(){
        $robot = new Robot();
        $robot->setId(3);
        $robot->setSid(2);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading('E');
        $robot->setCommands('MMLMRMM');
        $this->expectException(Exception::class);
        $robot->executeCommand('WrongCommand');
    }

}