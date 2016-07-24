<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 11:47 PM
 */

namespace RobotCafe\Service;


use RobotCafe\Model\RobotInterface;

interface RobotServiceInterface
{

    /**
     * @param $sid
     * @param $rid
     * @return null|\RobotCafe\Model\Shop
     */
    public function findByShopIdAndRobotId($sid, $rid);

    /**
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * @param $id
     * @return bool
     */
    public function deleteByShopId($id);

    /**
     * @param RobotInterface $robot
     * @return int
     */
    public function add(RobotInterface $robot);

    /**
     * @param RobotInterface $robot
     * @return bool
     */
    public function update(RobotInterface $robot);

    /**
     * @param int $x
     * @param int $y
     * @param int $sid
     * @return null|\RobotCafe\Model\Robot
     */
    public function findByRobotCoordinate($x, $y, $sid);

}