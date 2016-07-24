<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 9:27 PM
 */

namespace RobotCafe\Controller;

use RobotCafe\Model\Robot;
use RobotCafe\Service\RobotServiceInterface;
use RobotCafe\Service\ShopServiceInterface;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Stdlib\ArrayObject;
use Zend\View\Model\JsonModel;

class RobotController extends AbstractRobotCafeController
{

    /**
     * @var \RobotCafe\Service\RobotServiceInterface
     */
    protected $robotService;

    /**
     * @var \RobotCafe\Service\ShopServiceInterface
     */
    protected $shopService;

    public function __construct(
        ShopServiceInterface $shopServiceInterface,
        RobotServiceInterface $robotServiceInterface)
    {
        $this->shopService = $shopServiceInterface;
        $this->robotService = $robotServiceInterface;
    }

    /**
     * @return JsonModel
     */
    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            $jsonBody = $this->getRequest()->getContent();
            // valid JSON
            $robotArray = $this->robotJsonValidation($jsonBody);
            if ($robotArray) {
                $shopId = $this->params()->fromRoute('id');
                // check if shop is existing
                $shop = $this->shopService->find($shopId);
                if ($shop) {
                    // if shop existing, the initial robot position should be in the shop range
                    $newRobot = new Robot();
                    $newRobot->setSid($shopId);
                    $newRobot->setX($robotArray['x']);
                    $newRobot->setY($robotArray['y']);
                    $newRobot->setHeading($robotArray['heading']);
                    $newRobot->setCommands($robotArray['commands']);

                    if ($shop->getWidth() >= $newRobot->getX() && $shop->getHeight() >= $newRobot->getY()){
                        // need to check if there is robot is on the same position in this shop
                        $existingRobot = $this->robotService->findByRobotCoordinate($newRobot->getX(), $newRobot->getY(), $shopId);
                        if (!$existingRobot) {
                            $rid = $this->robotService->add($newRobot);
                            if ($rid) {
                                // if new robot is added
                                return $this->successfulRequest($newRobot->toArray());
                            } else {
                                return $this->internalServerError();
                            }
                        } else {
                            // robot position is duplicated with existing one
                            return $this->robotDuplicatedPosition();
                        }

                    } else {
                        // robot position is invalid for shop range
                        return $this->robotPositionInvalid();
                    }

                } else {
                    // if shop is not existing by the given id, then return resource not found
                    return $this->resourceDataNoFound();
                }
            } else {
                return $this->invalidJsonRequest();
            }

        } else {
            return $this->methodNotAllowed(Request::METHOD_POST);
        }
    }

    /**
     * @return JsonModel
     */
    private function updateRobot()
    {
        $jsonBody = $this->getRequest()->getContent();
        $robotArray = $this->robotJsonValidation($jsonBody);
        if ($robotArray) {
            $shopId = $this->params()->fromRoute('id');
            $robotId = $this->params()->fromRoute('rid');
            // check if shop is existing
            $robot = $this->robotService->findByShopIdAndRobotId($shopId, $robotId);

            if ($robot) {
                $newRobot = new Robot();
                $newRobot->setSid($shopId);
                $newRobot->setId($robotId);
                $newRobot->setX($robotArray['x']);
                $newRobot->setY($robotArray['y']);
                $newRobot->setHeading($robotArray['heading']);
                $newRobot->setCommands($robotArray['commands']);

                $successful = $this->robotService->update($newRobot);
                if ($successful){
                    return $this->successfulRequest($newRobot->toArray());
                } else {
                    return $this->internalServerError();
                }

            } else {
                // if shop is not existing by the given id, then return resource not found
                return $this->resourceDataNoFound();
            }
        } else {
            return $this->invalidJsonRequest();
        }
    }

    /**
     * @return JsonModel
     */
    private function deleteRobot()
    {
        $shopId = $this->params()->fromRoute('id');
        $robotId = $this->params()->fromRoute('rid');
        // check if shop is existing
        $robot = $this->robotService->findByShopIdAndRobotId($shopId, $robotId);

        if ($robot) {
            $successful = $this->robotService->delete($robotId);
            if ($successful) {
                return $this->successfulRequest(array('status'=>'ok'));
            } else {
                return $this->internalServerError();
            }
        } else {
            return $this->resourceDataNoFound();
        }
    }

    /**
     * @return JsonModel
     */
    public function updateOrDeleteAction()
    {
        if ($this->getRequest()->isPut()) {
            return $this->updateRobot();
        } else if ($this->getRequest()->isDelete()) {
            return $this->deleteRobot();
        } else {
            $allowed_methods = Request::METHOD_GET ." ".Request::METHOD_DELETE;
            return $this->methodNotAllowed($allowed_methods);
        }
    }

}