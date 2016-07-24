<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:17 PM
 */

namespace RobotCafe\Mapper;

use RobotCafe\Model\ShopInterface;

interface ShopMapperInterface
{
    /**
     * @param int|string $id
     * @return ShopInterface
     */
    public function findShop($id);

    /**
     * @param ShopInterface $shopObject
     * @return int
     */
    public function addShop(ShopInterface $shopObject);

    /**
     * @param int|string $id
     * @return bool
     */
    public function deleteShop($id);
}