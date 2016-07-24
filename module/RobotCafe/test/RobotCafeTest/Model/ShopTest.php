<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 6:02 PM
 */

namespace RobotCafeTestTest\Model;

use Exception;
use PHPUnit_Framework_TestCase;
use RobotCafe\Model\Robot;
use RobotCafe\Model\Shop;

class ShopTest extends PHPUnit_Framework_TestCase
{
    /**
     * initial object test
     */
    public function testShopInitialState()
    {
        $shop = new Shop();

        $this->assertNull(
            $shop->getId(),
            'id should initially be null'
        );
        $this->assertNull(
            $shop->getHeight(),
            '"x" should initially be null'
        );
        $this->assertNull(
            $shop->getWidth(),
            '"y" should initially be null'
        );
        $this->assertEmpty(
            $shop->getRobots(),
            '"heading" should initially be null'
        );
        $this->assertEmpty(
            $shop->getExecutionErrors(),
            '"commands" should initially be null'
        );
    }

    /**
     * test getId()
     */
    public function testShopSetGetId(){
        $shop = new Shop();
        $id = 1;
        $shop->setId($id);
        $this->assertEquals(
            $shop->getId(), $id);
        $id = null;
        $shop->setId($id);
        $this->assertNull(
            $shop->getId());
    }

    /**
     * test getHeight()
     */
    public function testShopSetGetHeight(){
        $shop = new Shop();
        $height = 5;
        $shop->setHeight($height);
        $this->assertEquals(
            $shop->getHeight(), $height);
        $height = null;
        $shop->setHeight($height);
        $this->assertNull(
            $shop->getHeight());
    }

    /**
     * test getWidth()
     */
    public function testShopSetGetWidth(){
        $shop = new Shop();
        $width = 5;
        $shop->setWidth($width);
        $this->assertEquals(
            $shop->getWidth(), $width);
        $width = null;
        $shop->setWidth($width);
        $this->assertNull(
            $shop->getWidth());
    }

    /**
     * test getRobots()
     */
    public function testShopSetGetRobots(){
        $robot1 = new Robot();
        $robot1->setId(3);
        $robot1->setSid(2);
        $robot1->setX(3);
        $robot1->setY(2);
        $robot1->setHeading('S');
        $robot1->setCommands('MMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setSid(2);
        $robot2->setX(1);
        $robot2->setY(2);
        $robot2->setHeading('E');
        $robot2->setCommands('MMLMRMM');

        $shop = new Shop();
        $shop->addRobot($robot1);
        $shop->addRobot($robot2);

        $robots = $shop->getRobots();

        $this->assertEquals(count($robots), count(array($robot2, $robot1)));
        $this->assertContains($robot1, $robots);
        $this->assertContains($robot2, $robots);
    }

    /**
     * test to array function
     */
    public function testShopToArray(){
        $robot1 = new Robot();
        $robot1->setId(3);
        $robot1->setSid(2);
        $robot1->setX(3);
        $robot1->setY(2);
        $robot1->setHeading('S');
        $robot1->setCommands('MMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setSid(2);
        $robot2->setX(1);
        $robot2->setY(2);
        $robot2->setHeading('E');
        $robot2->setCommands('MMLMRMM');

        $shop = new Shop();
        $shop->setId(2);
        $shop->setWidth(10);
        $shop->setHeight(10);
        $shop->addRobot($robot1);
        $shop->addRobot($robot2);

        $shopArray = $shop->toArray();

        $this->assertArrayHasKey(
            'width', $shopArray);
        $this->assertArrayHasKey(
            'height', $shopArray);
        $this->assertArrayHasKey(
            'id', $shopArray);
        $this->assertArrayHasKey(
            'robots', $shopArray);

        $this->assertEquals(
            $shopArray['width'], $shop->getWidth());
        $this->assertEquals(
            $shopArray['height'], $shop->getHeight());
        $this->assertEquals(
            $shopArray['id'], $shop->getId());
        $this->assertEquals(
            count($shopArray['robots']), count($shop->getRobots()));

        $this->assertContains($robot1->toArray(), $shopArray['robots']);
        $this->assertContains($robot2->toArray(), $shopArray['robots']);
    }


    /**
     * test askRobotExecuteCommand
     */
    public function testAskRobotExecuteCommandNoRobots()
    {
        // test when no robots added
        $shop = new Shop();
        $shop->setId(2);
        $shop->setWidth(10);
        $shop->setHeight(10);
        $shop->askRobotExecuteCommand();

        $this->assertContains('The shop has no any robots.', $shop->getExecutionErrors());

    }

    /**
     * test askRobotExecuteCommand
     */
    public function testAskRobotExecuteCommandRobotsNoCrash()
    {
        //test when add two robots, but no crashed after execute command
        $robot1 = new Robot();
        $robot1->setId(3);
        $robot1->setSid(2);
        $robot1->setX(3);
        $robot1->setY(2);
        $robot1->setHeading('S');
        $robot1->setCommands('MMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setSid(2);
        $robot2->setX(1);
        $robot2->setY(2);
        $robot2->setHeading('E');
        $robot2->setCommands('MMLMRMM');

        $shop = new Shop();
        $shop->setId(2);
        $shop->setWidth(10);
        $shop->setHeight(10);
        $shop->addRobot($robot1);
        $shop->addRobot($robot2);
        $shop->askRobotExecuteCommand();

        $this->assertEmpty($shop->getExecutionErrors());
        $this->assertEquals($robot1->getX(), 1);
        $this->assertEquals($robot1->getY(), 4);
        $this->assertEquals($robot1->getHeading(), Robot::WEST);

        $this->assertEquals($robot2->getX(), 5);
        $this->assertEquals($robot2->getY(), 1);
        $this->assertEquals($robot2->getHeading(), Robot::EAST);
    }

    /**
     * test askRobotExecuteCommand
     */
    public function testAskRobotExecuteCommandRobotsCrash()
    {
        // test when add two robots, but crashed after run
        $robot1 = new Robot();
        $robot1->setId(3);
        $robot1->setSid(2);
        $robot1->setX(3);
        $robot1->setY(2);
        $robot1->setHeading('S');
        $robot1->setCommands('MMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setSid(2);
        $robot2->setX(1);
        $robot2->setY(2);
        $robot2->setHeading('E');
        $robot2->setCommands('MRMMRMM');

        $shop = new Shop();
        $shop->setId(2);
        $shop->setWidth(10);
        $shop->setHeight(10);
        $shop->addRobot($robot1);
        $shop->addRobot($robot2);
        $shop->askRobotExecuteCommand();

        $this->assertEquals($robot1->getX(), 2);
        $this->assertEquals($robot1->getY(), 4);
        $this->assertEquals($robot1->getHeading(), Robot::WEST);

        $this->assertEquals($robot2->getX(), 2);
        $this->assertEquals($robot2->getY(), 4);
        $this->assertEquals($robot2->getHeading(), Robot::SOUTH);
        $this->assertContains("robot_id:" . $robot2->getId() . " is running on the same position with robot_id: " . $robot1->getId(), $shop->getExecutionErrors());
    }

    /**
     * test askRobotExecuteCommand
     */
    public function testAskRobotExecuteCommandRobotsRunOut()
    {
        // test when add two robots, but some robot run out of range
        $robot1 = new Robot();
        $robot1->setId(3);
        $robot1->setSid(2);
        $robot1->setX(3);
        $robot1->setY(2);
        $robot1->setHeading('S');
        $robot1->setCommands('MMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setSid(2);
        $robot2->setX(1);
        $robot2->setY(2);
        $robot2->setHeading('E');
        $robot2->setCommands('MLMMMRMM');

        $shop = new Shop();
        $shop->setId(2);
        $shop->setWidth(10);
        $shop->setHeight(10);
        $shop->addRobot($robot1);
        $shop->addRobot($robot2);
        $shop->askRobotExecuteCommand();

        $this->assertEquals($robot1->getX(), 1);
        $this->assertEquals($robot1->getY(), 4);
        $this->assertEquals($robot1->getHeading(), Robot::WEST);

        $this->assertEquals($robot2->getX(), 2);
        $this->assertEquals($robot2->getY(), -1);
        $this->assertEquals($robot2->getHeading(), Robot::NORTH);
        $this->assertContains("robot id:".$robot2->getId()." has moved out of the shop range", $shop->getExecutionErrors());

    }

}