<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 9:08 PM
 */

namespace RobotCafeTestTest\Controller;


use RobotCafe\Controller\RobotController;
use Zend\Http\Request as HttpRequest;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class RobotControllerTest //extends AbstractHttpControllerTestCase
{

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

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

        $this->dispatch('/shop/1/robot/2', HttpRequest::METHOD_DELETE);

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('RobotCafe');
        $this->assertControllerName(RobotController::class);
        $this->assertControllerClass('RobotController');
        $this->assertMatchedRouteName('shop');


    }
}