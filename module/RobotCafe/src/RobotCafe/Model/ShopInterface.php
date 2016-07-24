<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:49 PM
 */

namespace RobotCafe\Model;


interface ShopInterface
{

    /**
     * @return int
     */
    public function getWidth();

    /**
     * @return int
     */
    public function getHeight();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return Robot[]
     */
    public function getRobots();

    /**
     * @param RobotInterface $robot
     */
    public function addRobot(RobotInterface $robot);

    /**
     * @param
     */
    public function toArray();
}