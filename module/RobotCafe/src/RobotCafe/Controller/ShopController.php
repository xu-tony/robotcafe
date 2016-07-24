<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 22/07/2016
 * Time: 6:41 PM
 */

namespace RobotCafe\Controller;

use RobotCafe\Model\Shop;
use RobotCafe\Model\ShopInterface;
use RobotCafe\Service\RobotServiceInterface;
use RobotCafe\Service\ShopServiceInterface;
use Zend\Http\Response;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;

class ShopController extends AbstractRobotCafeController
{

    /**
     * @var \RobotCafe\Service\ShopServiceInterface
     */
    protected $shopService;

    /**
     * @var \RobotCafe\Service\RobotServiceInterface
     */
    protected $robotService;

    public function __construct(ShopServiceInterface $shopServiceInterface, RobotServiceInterface $robotServiceInterface)
    {
        $this->shopService = $shopServiceInterface;
        $this->robotService = $robotServiceInterface;
    }

    // hello world
    public function helloAction()
    {
        return new JsonModel(array('hello' => 'world'));
    }

    /**
     * @return JsonModel
     */
    public function addAction()
    {
        if ($this->getRequest()->isPost()) {

            $jsonBody = $this->getRequest()->getContent();

            $shopArray = $this->shopJsonValidation($jsonBody);
            if ($shopArray) {
                $newShop = new Shop();
                $newShop->setHeight($shopArray['height']);
                $newShop->setWidth($shopArray['width']);

                $id = $this->shopService->add($newShop);
                if ($id) {
                    $newShop->setId($id);
                    return $this->successfulRequest($newShop->toArray());
                } else {
                    // server error
                    return $this->internalServerError();
                }
            } else {
                return $this->invalidJsonRequest();
            }

        } else {
            $allowed_methods = Request::METHOD_POST;
            return $this->methodNotAllowed($allowed_methods);
        }
    }


    /**
     * @return JsonModel
     */
    public function getOrDeleteAction()
    {
        if ($this->getRequest()->isGet()) {
            return $this->getShop();
        } else if ($this->getRequest()->isDelete()) {
            return $this->deleteShop();
        } else {
            $allowed_methods = Request::METHOD_GET ." ".Request::METHOD_DELETE;
            return $this->methodNotAllowed($allowed_methods);
        }
    }

    /**
     *
     * @return JsonModel
     */
    private function getShop()
    {
        $id = $this->params()->fromRoute('id');
        $shop = $this->shopService->find($id);

        if ($shop instanceof ShopInterface) {
            return $this->successfulRequest($shop->toArray());
        } else {
            return $this->resourceDataNoFound();
        }
    }

    /**
     * @return JsonModel
     */
    private function deleteShop()
    {
        $id = $this->params()->fromRoute('id');
        $this->robotService->deleteByShopId($id); // this is ok even no robots deleted by the shop id
        $successful = $this->shopService->delete($id);

        if ($successful) {
            return $this->successfulRequest(array('status'=>'ok'));
        } else {
            return $this->resourceDataNoFound();
        }
    }

    /**
     * @return JsonModel
     */
    public function executeAction()
    {
        $id = $this->params()->fromRoute('id');
        $shop = $this->shopService->find($id);

        if ($shop && count($shop->getRobots())>0){

            $shop->askRobotExecuteCommand();

            $result = $shop->toArray();

            return $this->successfulRequest($result);

        } else {
            return $this->resourceDataNoFound();
        }
    }

}