<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 11:30 PM
 */

namespace RobotCafe\Service;


use RobotCafe\Model\ShopInterface;

interface ShopServiceInterface
{

    /**
     * @param $id
     * @return null|\RobotCafe\Model\Shop
     */
    public function find($id);

    /**
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * @param ShopInterface $shop
     * @return int
     */
    public function add(ShopInterface $shop);

}