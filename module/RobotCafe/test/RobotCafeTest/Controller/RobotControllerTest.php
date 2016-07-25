<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 9:08 PM
 */

namespace RobotCafeTestTest\Controller;


use HttpRequest;
use RobotCafe\Controller\RobotController;
use RobotCafe\Model\Robot;
use RobotCafe\Model\Shop;
use RobotCafeTestTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


class RobotControllerTest extends AbstractHttpControllerTestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function buildUp($shopService, $robotService)
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new RobotController($shopService, $robotService);
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'robot'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);

    }


    /**
     * test add action can be accessed
     */
    public function testAddActionCanBeAccessed()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');


        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('find')
            ->will($this->returnValue($shop));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('add')
            ->will($this->returnValue(1));
        $robotService->expects($this->once())
            ->method('findByRobotCoordinate')
            ->will($this->returnValue(null));

        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop/1/robot');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"x":4,"y":4,"heading":"N","commands":"LM"}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
        $robot->setId(1);
        //$this->assertEquals($response->getContent(), json_encode($robot->toArray()));

        //$this->assertModuleName('RobotCafe');
        //$this->assertControllerName(RobotController::class);
        //$this->assertControllerClass('RobotController');
        //$this->assertMatchedRouteName('shop');
    }

    public function testAddActionShopNoExist()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');


        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop/1/robot');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"x":4,"y":4,"heading":"N","commands":"LM"}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testAddActionRobotPositionInvalid()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');


        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('find')
            ->will($this->returnValue($shop));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop/1/robot');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"x":7,"y":7,"heading":"N","commands":"LM"}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_409, $response->getStatusCode());
    }

    public function testAddActionRobotPositionDuplicated()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');


        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('find')
            ->will($this->returnValue($shop));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('findByRobotCoordinate')
            ->will($this->returnValue($robot));

        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop/1/robot');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"x":1,"y":2,"heading":"N","commands":"LM"}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_409, $response->getStatusCode());
    }

    public function testAddActionInvalidJson()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');


        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop/1/robot');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_400, $response->getStatusCode());
    }

    /**
     * test successful update action
     */
    public function testUpdateOrDeleteActionUpdate()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setId(1);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('findByShopIdAndRobotId')
            ->will($this->returnValue($robot));
        $robotService->expects($this->once())
            ->method('update')
            ->will($this->returnValue(true));


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'updateOrDelete');

        $this->request->setUri('/shop/1/robot/1');
        $this->request->setMethod(Request::METHOD_PUT);
        $this->request->setContent('{"x":1,"y":2,"heading":"N","commands":"LM"}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
    }

    /**
     * test update action invalid json
     */
    public function testUpdateOrDeleteActionUpdateInvalidJson()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setId(1);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'updateOrDelete');

        $this->request->setUri('/shop/1/robot/1');
        $this->request->setMethod(Request::METHOD_PUT);
        $this->request->setContent('invalid json');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_400, $response->getStatusCode());
    }



    /**
     * test delete action
     */
    public function testUpdateOrDeleteActionDelete()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setId(1);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('findByShopIdAndRobotId')
            ->will($this->returnValue($robot));
        $robotService->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'updateOrDelete');

        $this->request->setUri('/shop/1/robot/1');
        $this->request->setMethod(Request::METHOD_DELETE);
        $this->request->setContent('invalid json');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
    }

    /**
     * test delete action invalid json
     */
    public function testUpdateOrDeleteActionDeleteInvalidJson()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setId(1);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'updateOrDelete');

        $this->request->setUri('/shop/1/robot/1');
        $this->request->setMethod(Request::METHOD_PUT);
        $this->request->setContent('invalid json');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_400, $response->getStatusCode());
    }


}