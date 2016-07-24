<?php
/**
 * Created by IntelliJ IDEA.
 * User: tonyxu
 * Date: 23/07/2016
 * Time: 8:46 AM
 */

namespace RobotCafe\Controller;

use RobotCafe\Model\Robot;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

Abstract class AbstractRobotCafeController extends AbstractRestfulController
{
    /**
     * @param $allowed_methods
     * @return JsonModel
     */
    protected function methodNotAllowed($allowed_methods)
    {
        $this->response->setStatusCode(Response::STATUS_CODE_405);
        $this->response->getHeaders()
            //set allow methods
            ->addHeaderLine('Allow-Methods',$allowed_methods);

        return new JsonModel(array('message'=>'http method not allowed'));
    }

    /**
     * @return JsonModel
     */
    protected function invalidJsonRequest()
    {
        $this->response->setStatusCode(Response::STATUS_CODE_400);
        return new JsonModel(array('message'=>'invalid request data'));
    }

    /**
     * @return JsonModel
     */
    protected function successfulRequest($arrayData)
    {
        $this->response->setStatusCode(Response::STATUS_CODE_200);
        return new JsonModel($arrayData);
    }

    /**
     * @return JsonModel
     */
    protected function internalServerError()
    {
        $this->response->setStatusCode(Response::STATUS_CODE_500);
        return new JsonModel(array('message'=>'internal server error'));
    }

    /**
     * @return JsonModel
     */
    protected function robotDuplicatedPosition()
    {
        $this->response->setStatusCode(Response::STATUS_CODE_409);
        return new JsonModel(array('message'=>'request robot position is duplicated with existing one.'));
    }

    /**
     * @return JsonModel
     */
    protected function robotPositionInvalid()
    {
        $this->response->setStatusCode(Response::STATUS_CODE_409);
        return new JsonModel(array('message'=>'request robot position is invalid for the shop.'));
    }

    /**
     * @return JsonModel
     */
    protected function resourceDataNoFound()
    {
        $this->response->setStatusCode(Response::STATUS_CODE_404);
        return new JsonModel(array('message'=>'resource data no found'));
    }


    /**
     * @param $json
     * @return array
     */
    protected function shopJsonValidation($json){
        $shopArray = null;
        if ($json){
            $jsonArray = json_decode($json, true);
            if ($jsonArray
                && array_key_exists('width', $jsonArray)
                && array_key_exists('height', $jsonArray)
                && preg_match('/^[1-9]\d*$/', $jsonArray['width'])
                && preg_match('/^[1-9]\d*$/', $jsonArray['height'])
            ){
                $shopArray = $jsonArray;
            }
        }
        return $shopArray;
    }

    /**
     * @param $json
     * @return array
     */
    protected function robotJsonValidation($json){
        $robotArray = null;
        if ($json){
            $jsonArray = json_decode($json, true);

            $headingMatchPattern = '/^['.Robot::NORTH.'|'.Robot::SOUTH.'|'.Robot::EAST.'|'.Robot::WEST.']$/';
            $commandsMatchPattern = '/^['.Robot::LEFT.'*|'.Robot::RIGHT.'*|'.Robot::MOVE.'*]*$/';

            if ($jsonArray
                && array_key_exists('x', $jsonArray)
                && array_key_exists('y', $jsonArray)
                && array_key_exists('heading', $jsonArray)
                && array_key_exists('commands', $jsonArray)
                && preg_match('/^[0-9]*$/', $jsonArray['x'])
                && preg_match('/^[0-9]*$/', $jsonArray['y'])
                && preg_match($headingMatchPattern, $jsonArray['heading']) // only north, south, east and west
                && preg_match($commandsMatchPattern, $jsonArray['commands']) // only left, right, and move
                && strlen($jsonArray['commands']) <= 255 // commands string cannot over 255
            ){
                $robotArray = $jsonArray;
            }
        }
        return $robotArray;
    }

}