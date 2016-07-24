<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 10:08 PM
 */

namespace RobotCafe\Service;


use RobotCafe\Mapper\RobotMapperInterface;
use RobotCafe\Model\RobotInterface;

class RobotService implements RobotServiceInterface
{


    /**
     * @var \RobotCafe\Mapper\RobotMapperInterface
     */
    protected $robotMapper;

    /**
     * @param RobotMapperInterface $robotMapper
     */
    public function __construct(RobotMapperInterface $robotMapper)
    {
        $this->robotMapper = $robotMapper;
    }

    /**
     * @param $sid
     * @param $rid
     * @return RobotInterface
     */
    public function findByShopIdAndRobotId($sid, $rid)
    {
        return $this->robotMapper->findBySIdAndRId($sid, $rid);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->robotMapper->delete($id);
    }

    /**
     * @param RobotInterface $robot
     * @return int
     */
    public function add(RobotInterface $robot)
    {
        return $this->robotMapper->add($robot);
    }

    /**
     * @param RobotInterface $robot
     * @return bool
     */
    public function update(RobotInterface $robot)
    {
        return $this->robotMapper->update($robot);
    }

    /**
     * @param $sid
     * @return bool
     */
    public function deleteByShopId($sid)
    {
        return $this->robotMapper->deleteByShopId($sid);
    }


    public function findByRobotCoordinate($x, $y, $sid){
        return $this->robotMapper->findByRobotCoordinate($x, $y, $sid);
    }

}