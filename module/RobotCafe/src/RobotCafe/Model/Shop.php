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

        return $shop;
    }

}