<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:47 PM
 */

namespace RobotCafe\Model;


interface RobotInterface
{

    /**
     * robot move forward
     */
    public function moveForward();

    /**
     * @param $direction
     */
    public function turn($direction);

    /**
     * @return string
     */
    public function getHeading();

    /**
     * @return int
     */
    public function getX();

    /**
     * @return int
     */
    public function getY();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getSid();

    /**
     * @return string
     */
    public function getCommands();
}