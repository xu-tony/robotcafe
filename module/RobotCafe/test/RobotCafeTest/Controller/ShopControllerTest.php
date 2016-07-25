<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 9:10 PM
 */

namespace RobotCafeTestTest\Controller;


use HttpRequest;
use RobotCafe\Controller\ShopController;
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


class ShopControllerTest extends AbstractHttpControllerTestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function buildUp($shopService, $robotService)
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new ShopController($shopService, $robotService);
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Shop'));
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
     * test add action
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
            ->method('add')
            ->will($this->returnValue(12));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'add');

        $this->request->setUri('/shop');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{"width":5,"height":5}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());

        //$this->assertEquals($response->getContent(), json_encode($robot->toArray()));

        //$this->assertModuleName('RobotCafe');
        //$this->assertControllerName(RobotController::class);
        //$this->assertControllerClass('RobotController');
        //$this->assertMatchedRouteName('shop');
    }


    /**
     * test add action
     */
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

        $this->request->setUri('/shop');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('{invalid json}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_400, $response->getStatusCode());

    }


    /**
     * test add action
     */
    public function testAddActionInvalidMethod()
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

        $this->request->setUri('/shop');
        $this->request->setMethod(Request::METHOD_GET);
        $this->request->setContent('{invalid json}');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_405, $response->getStatusCode());

    }

    public function testGetOrDeleteActionGet()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shop->addRobot($robot);

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

        $this->routeMatch->setParam('action', 'getOrDelete');

        $this->request->setUri('/shop');
        $this->request->setMethod(Request::METHOD_GET);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());

    }

    public function testGetOrDeleteActionGetNoFound()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shop->addRobot($robot);

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

        $this->routeMatch->setParam('action', 'getOrDelete');

        $this->request->setUri('/shop');
        $this->request->setMethod(Request::METHOD_GET);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_404, $response->getStatusCode());
    }

    public function testGetOrDeleteActionDelete()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shop->addRobot($robot);

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('deleteByShopId')
            ->will($this->returnValue(true));

        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'getOrDelete');

        $this->request->setUri('/shop/1');
        $this->request->setMethod(Request::METHOD_DELETE);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
    }


    public function testGetOrDeleteActionDeleteNoFound()
    {
        $shop = new Shop();
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $shop->addRobot($robot);

        $shopService = $this->getMockBuilder('RobotCafe\Service\ShopService')
            ->disableOriginalConstructor()
            ->getMock();
        $shopService->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(false));

        $robotService = $this->getMockBuilder('RobotCafe\Service\RobotService')
            ->disableOriginalConstructor()
            ->getMock();
        $robotService->expects($this->once())
            ->method('deleteByShopId')
            ->will($this->returnValue(false));


        $this->buildUp($shopService, $robotService);

        $this->routeMatch->setParam('action', 'getOrDelete');

        $this->request->setUri('/shop/1');
        $this->request->setMethod(Request::METHOD_DELETE);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_404, $response->getStatusCode());
    }

    public function testExecuteAction(){

        $shop = new Shop();
        $shop->setId(1);
        $shop->setWidth(6);
        $shop->setHeight(6);

        $robot = new Robot();
        $robot->setId(1);
        $robot->setX(1);
        $robot->setY(2);
        $robot->setHeading(Robot::SOUTH);
        $robot->setCommands('MMLMRMM');

        $robot2 = new Robot();
        $robot2->setId(3);
        $robot2->setX(2);
        $robot2->setY(2);
        $robot2->setHeading(Robot::SOUTH);
        $robot2->setCommands('MMLMRMM');

        $shop->addRobot($robot);
        $shop->addRobot($robot2);

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

        $this->routeMatch->setParam('action', 'execute');

        $this->request->setUri('/shop/1/execute');
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setContent('');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
    }

}