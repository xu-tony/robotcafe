<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:19 PM
 */

namespace RobotCafe\Mapper;


use RobotCafe\Model\Robot;
use RobotCafe\Model\Shop;
use RobotCafe\Model\ShopInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;


class ShopDbSqlMapper implements ShopMapperInterface
{
    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @param AdapterInterface  $dbAdapter
     */
    public function __construct(
        AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter      = $dbAdapter;
    }

    /**
     * @param int|string $id
     * @return null|ShopInterface
     */
    public function findShop($id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select()->from(
            array('s' => 'shops')
        );

        $select->join(
            array('r' => 'robots'), // table name
            's.id = r.sid',
            array('rid'=>'id', 'x', 'y', 'heading', 'commands'),
            $select::JOIN_LEFT
        );
        $select->where(array('s.id = ?' => $id));
        $select->order(array('r.id ASC'));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $finalResult = null;

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            foreach($resultSet->toArray() as $row){
                $robot = new Robot();
                $robot->setId($row['rid']);
                $robot->setSid($id);
                $robot->setX($row['x']);
                $robot->setY($row['y']);
                $robot->setHeading($row['heading']);
                $robot->setCommands($row['commands']);

                if ($finalResult instanceof ShopInterface) {
                    if ($robot->getId()){
                        $finalResult->addRobot($robot);
                    }
                } else {
                    $shop = new Shop();
                    $shop->setId($row['id']);
                    $shop->setWidth($row['width']);
                    $shop->setHeight($row['height']);
                    if ($robot->getId()){
                        $shop->addRobot($robot);
                    }
                    $finalResult = $shop;
                }
            }
        }

        return $finalResult;
    }

    /**
     * @param ShopInterface $shopObject
     * @return null|int
     */
    public function addShop(ShopInterface $shopObject)
    {
        $shopData = array(
            'height' => $shopObject->getHeight(),
            'width'  => $shopObject->getWidth()
        );

        $action = new Insert('shops');

        $action->values($shopData);

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        $newId = null;
        if ($result instanceof ResultInterface) {
            if ($result->getGeneratedValue()) {
                $newId = $result->getGeneratedValue();
            }
        }

        return $newId;
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function deleteShop($id)
    {
        $action = new Delete('shops');
        $action->where(array('id=?'=> $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();

    }


}