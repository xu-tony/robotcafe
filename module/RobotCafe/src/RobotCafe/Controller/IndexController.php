<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 24/07/2016
 * Time: 4:43 PM
 */

namespace RobotCafe\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{

    // hello world
    public function helloAction()
    {
        return new JsonModel(array('hello' => 'world'));
    }

}