<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:48 PM
 */

namespace RobotCafe\Model;


use Exception;

class Robot implements RobotInterface
{

    const WEST = 'W';
    const EAST = 'E';
    const SOUTH = 'S';
    const NORTH = 'N';
    const LEFT = 'L';
    const RIGHT = 'R';
    const MOVE = 'M';

    /**
     * robot id
     * @var int
     */
    protected $id;

    /**
     * shop id
     * @var int
     */
    protected $sid;

    /**
     * coordinate X
     * @var int
     */
    protected $x;

    /**
     * coordinate Y
     * @var int
     */
    protected $y;

    /**
     * @var string
     */
    protected $heading;

    /**
     * @var string
     */
    protected $commands;

    /**
     * @param $heading
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    }

    /**
     * @param $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @param $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $sid
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    /**
     * @param $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @return string
     */
    public function getCommands(){
        return $this->commands;
    }

    /**
     * @throws Exception
     */
    public function moveForward()
    {
        if ($this->getX() >= 0 && $this->getY() >= 0) {
            if ($this->getHeading() == self::NORTH) {
                $updatedY = $this->getY() - 1;
                $this->setY($updatedY);
            } else if ($this->getHeading() == self::SOUTH) {
                $updatedY = $this->getY() + 1;
                $this->setY($updatedY);
            } else if ($this->getHeading() == self::EAST) {
                $updatedX = $this->getX() + 1;
                $this->setX($updatedX);
            } else if ($this->getHeading() == self::WEST) {
                $updatedX = $this->getX() - 1;
                $this->setX($updatedX);
            } else {
                throw new Exception('Invalid robot heading direction');
            }
        } else {
            throw new Exception('Robot has no valid coordination');
        }
    }

    /**
     * @param $direction
     * @throws Exception
     */
    public function turn($direction)
    {
        if ($direction == self::LEFT && $this->getHeading() == self::NORTH) {
            $this->setHeading(self::WEST);
        } else if ($direction == self::LEFT && $this->getHeading() == self::WEST) {
            $this->setHeading(self::SOUTH);
        } else if ($direction == self::LEFT && $this->getHeading() == self::SOUTH) {
            $this->setHeading(self::EAST);
        } else if ($direction == self::LEFT && $this->getHeading() == self::EAST) {
            $this->setHeading(self::NORTH);
        } else if ($direction == self::RIGHT && $this->getHeading() == self::NORTH) {
            $this->setHeading(self::EAST);
        } else if ($direction == self::RIGHT && $this->getHeading() == self::EAST) {
            $this->setHeading(self::SOUTH);
        } else if ($direction == self::RIGHT && $this->getHeading() == self::SOUTH) {
            $this->setHeading(self::WEST);
        } else if ($direction == self::RIGHT && $this->getHeading() == self::WEST) {
            $this->setHeading(self::NORTH);
        } else {
            throw new Exception('Invalid direction input');
        }
    }

    /**
     * @param $command
     * @throws Exception
     */
    public function executeCommand($command){
        if($command == self::LEFT || $command == self::RIGHT) {
            $this->turn($command);
        } else if ($command == self::MOVE) {
            $this->moveForward();
        } else {
            throw new Exception('Invalid $command input');
        }
    }

    /**
     * @return array
     */
    public function toArray(){
        $robot = array();
        $robot['x'] = $this->getX();
        $robot['y'] = $this->getY();
        $robot['heading'] = $this->getHeading();
        $robot['commands']= $this->getcommands();

        return $robot;
    }
}