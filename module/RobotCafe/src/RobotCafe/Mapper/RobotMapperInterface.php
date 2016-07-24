<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:18 PM
 */

namespace RobotCafe\Mapper;


use RobotCafe\Model\RobotInterface;

interface RobotMapperInterface
{
    /**
     * @param int|string $sid shopId
     * @param int|string $rid robotId
     * @return null|RobotInterface
     */
    public function findBySIdAndRId($sid, $rid);

    /**
     * @param RobotInterface $robot
     * @return null|int
     */
    public function add(RobotInterface $robot);

    /**
     * @param RobotInterface $robot
     * @return bool
     */
    public function update(RobotInterface $robot);

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
     * @param int $x
     * @param int $y
     * @param int $sid
     * @return null|\RobotCafe\Model\Robot
     */
    public function findByRobotCoordinate($x, $y, $sid);

}