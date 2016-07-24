<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 7:19 PM
 */

namespace RobotCafe\Mapper;


use RobotCafe\Model\RobotInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;


class RobotDbSqlMapper implements RobotMapperInterface
{

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var \Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $hydrator;

    /**
     * @var \RobotCafe\Model\ShopInterface
     */
    protected $robotPrototype;

    /**
     * @var string
     */
    protected $tableName = 'robots';

    /**
     * @param AdapterInterface $dbAdapter
     * @param HydratorInterface $hydrator
     * @param RobotInterface $robotPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        RobotInterface $robotPrototype
    )
    {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->robotPrototype = $robotPrototype;
    }


    /**
     * @param int|string $sid
     * @param int|string $rid
     * @return null|RobotInterface
     */
    public function findBySIdAndRId($sid, $rid)
    {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select($this->tableName);
        $select->where(array('id = ?' => $rid, 'sid = ?' => $sid));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $robot = null;
        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            $robot = $this->hydrator->hydrate($result->current(), $this->robotPrototype);
        }

        return $robot;
    }

    /**
     * @param RobotInterface $robot
     * @return null|int
     */
    public function add(RobotInterface $robot)
    {
        $robotData = $this->hydrator->extract($robot);
        unset($robotData['id']); // Neither Insert nor Update needs the ID in the array
        unset($robotData['sid']); // Neither Insert nor Update needs the shop ID in the array

        $action = new Insert($this->tableName);

        $action->values($robotData);

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        $id = null;
        if ($result instanceof ResultInterface) {
            if ($result->getGeneratedValue()) {
                $id = $result->getGeneratedValue();
            }
        }
        return $id;
    }

    /**
     * @param RobotInterface $robot
     * @return bool
     */
    public function update(RobotInterface $robot)
    {
        $postData = $this->hydrator->extract($robot);
        unset($postData['id']); // Neither Insert nor Update needs the ID in the array

        // ID present, it's an Update
        $action = new Update($this->tableName);
        $action->set($postData);
        $action->where(array('id = ?' => $robot->getId()));


        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        $successful = false;
        if ($result instanceof ResultInterface) {
            $successful = true;
        }
        return $successful;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $action = new Delete($this->tableName);
        $action->where(array('id=?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    /**
     * @param $sid
     * @return bool
     */
    public function deleteByShopId($sid)
    {
        $action = new Delete($this->tableName);
        $action->where(array('sid=?' => $sid));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    /**
     * @param $sid
     * @return bool
     */
    public function findByRobotCoordinate($x, $y, $sid)
    {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select($this->tableName);
        $select->where(array('x = ?' => $x, 'y = ?' => $y, 'sid = ?' => $sid));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        $robot = null;
        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            $robot = $this->hydrator->hydrate($result->current(), $this->robotPrototype);
        }

        return $robot;
    }
}