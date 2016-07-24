<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:49 PM
 */

namespace RobotCafe\Model;


class Shop implements ShopInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var Robot[]
     */
    protected $robots = array();

    /**
     * @var array()
     */
    protected $executionErrors = array();


    /**
     * @param $error
     */
    protected function setExecutionErrors($error) {

        $this->executionErrors[]= $error;
    }
    /**
     * return array()
     */
    public function getExecutionErrors()
    {
        return $this->executionErrors;
    }


    /**
     * @param $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param RobotInterface $robot
     */
    public function addRobot (RobotInterface $robot) {
        $this->robots[] = $robot;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return Robot[]
     */
    public function getRobots() {
        return $this->robots;
    }


    /**
     * this will ask each robot to execute their command, after all robots finish one single command,
     * we need to check their status
     */
    public function askRobotExecuteCommand() {

        if ($this->getRobots()) {
            // if this shop has robots.
            $robotsHaveCommand = true;
            $i = 0;
            while($robotsHaveCommand) {

                $robotsHaveCommand = $this->robotsExecuteSingleCommand($this->getRobots(), $i);
                if ($this->getExecutionErrors()) {
                    // when robots execution have errors, break
                    break;
                }
                $i++;
            }
        } else {
            $this->setExecutionErrors('The shop has no any robots.');
        }
    }

    /**
     * @param Robot[] $robots
     * @param int $commandIndex
     * @return bool
     */
    private function robotsExecuteSingleCommand($robots, $commandIndex)
    {
        $allHaveCommand = true;
        $robotsRunCommand = array();
        $robotsPositions = array();
        foreach($robots as $robot) {

            $robotCommands = $robot->getCommands();
            if ($commandIndex < strlen($robotCommands))
            {
                // notify robot execute command
                $robot->executeCommand($robotCommands[$commandIndex]);
                $robotsRunCommand[] = true;
            } else {
                $robotsRunCommand[] = false;
            }

            if ($robot->getX() >= 0
                && $robot->getX() <= $this->getWidth()
                && $robot->getY() >= 0
                && $robot->getY() <= $this->getHeight())
            {

                /*when the robot is still in the shop range,
                we check if robot is running on the same position with others*/

                $robotCoordinate = array($robot->getX(), $robot->getY());
                $searchedRobotId = array_search($robotCoordinate, $robotsPositions);
                if (false === $searchedRobotId) {
                    $robotsPositions[$robot->getId()] = $robotCoordinate;
                } else {
                    $error = "robot_id:".$robot->getId()." is running on the same position with robot_id: ".$searchedRobotId;
                    $this->setExecutionErrors($error);
                }
            } else {
                // robot is running out of the shop range
                $error = "robot id:".$robot->getId()." has moved out of the shop range";
                $this->setExecutionErrors($error);
            }
        }

        // when there is no any command left for all robots
        if (false === array_search(true, $robotsRunCommand)) {
            $allHaveCommand = false;
        }
        return $allHaveCommand;
    }

    /**
     * @return array
     */
    public function toArray(){
        $shop = array();
        $shop['id'] = $this->getId();
        $shop['width'] = $this->getWidth();
        $shop['height'] = $this->getHeight();
        $shop['robots'] = array();

        if(count($this->getRobots()) > 0) {
            foreach($this->getRobots() as $robot) {
                $shop['robots'][] = $robot->toArray();
            }
        }
        if(count($this->getExecutionErrors()) > 0){
            $shop['errors'] = $this->getExecutionErrors();
        }
        return $shop;
    }

}