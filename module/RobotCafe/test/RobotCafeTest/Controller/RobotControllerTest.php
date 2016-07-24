<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 9:08 PM
 */

namespace RobotCafeTestTest\Controller;


use Zend\Http\Request as HttpRequest;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class RobotControllerTest extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        $modulePath = static::findParentPath("module");
        $this->setApplicationConfig(
            include $modulePath . '/../config/application.config.php'
        );
        parent::setUp();
    }

    public function testDeleteActionCanBeAccessed()
    {

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);


        $this->dispatch('/shop/1/robot/1', HttpRequest::METHOD_DELETE);

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('RobotCafe');
        $this->assertControllerName('RobotCafe\Controller\Robot');
        $this->assertControllerClass('RobotController');
        $this->assertMatchedRouteName('shop');
    }
}