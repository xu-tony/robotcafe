<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 9:31 PM
 */

namespace RobotCafe\Service;


use RobotCafe\Mapper\ShopMapperInterface;
use RobotCafe\Model\ShopInterface;

class ShopService implements ShopServiceInterface
{

    /**
     * @var \RobotCafe\Mapper\ShopMapperInterface
     */
    protected $shopMapper;

    /**
     * @param ShopMapperInterface $shopMapper
     */
    public function __construct(ShopMapperInterface $shopMapper)
    {
        $this->shopMapper = $shopMapper;
    }

    /**
     * @param $id
     * @return ShopInterface
     */
    public function find($id)
    {
        return $this->shopMapper->findShop($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->shopMapper->deleteShop($id);
    }

    public function add(ShopInterface $shop)
    {
        return $this->shopMapper->addShop($shop);
    }

}