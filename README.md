RobotCafeApplication
====================

Introduction
------------
This is a simple, restful application using the ZF2 MVC layer and module
systems. This application is meant to be designed for robot adding to shop with moving command,
shop is simulated as a matrix grid, top left corner coordinate will be (0,0). Robot has the
coordinate and heading direction.shop can trigger the robots to execute their own moving commands.

The whole app is hosting in AWS,
the general endpoint is http://54.206.18.76 , you will get a 'hello world' when you hit that url.



Design ideas
------------
1. The basic relationship of Shop and Robot will be acting like observer pattern. Robots are added in to the shop, when
shop notify robot to execute command, robot will update its status. After every single command finished by all robots,
shop will immediate check their status, if there is error happening, then break out.

2. RESTful api design, there are 3 layer, controller, service and DAO, each dependency is injected based on the dependency
inversion principle. Factory pattern is plenty used, when zend service manager need to load the service, it goes to a factory,
this will decrease the dependency coupling issue.



API explanation
----------------

1. shop get
-----------
url: http://54.206.18.76/shop/<:id>, http method is GET only, otherwise 405 status with response:

{
  "message": "http method not allowed"
}

the id must be integer which is greater than 0,
The possible return data with 200 status is:

{
  "id": "1",
  "width": "5",
  "height": "5",
  "robots": [
    {
      "id": "1",
      "x": "4",
      "y": "4",
      "heading": "N",
      "commands": "LM"
    },
    {
      "id": "2",
      "x": "0",
      "y": "0",
      "heading": "S",
      "commands": "LLM"
    },
  ]
}

when the provided id has no data then response with 404 status:

{
  "message": "resource data no found"
}


2. shop add
-----------
url: http://54.206.18.76/shop, http method is POST only, otherwise 405 status will return.

The request json data:

{"width":5,"height":5}

"width" and "height" must occur, and value must be integer greater than 0.

response with 200 status:

{
  "id": "3",
  "width": 5,
  "height": 5,
  "robots": []
}

3. shop delete
--------------
url: http://54.206.18.76/shop/<:id>, http method is DELETE only, otherwise 405 status will return.

if the provided id is successfully deleted, response with 200 status:

{
  "status": "ok"
}

otherwise 404 will return.

4. shop robot add
-----------------
url: http://54.206.18.76/shop/<:id>/robot, http method is POST only, otherwise 405 status will return.
Request json:

{"x":4,"y":4,"heading":"N","commands":"LM"},
which x,y must by integer which is greater or equal than zero, "heading" must be one of "N","S","E" or "W" which represent
four directions, then commands can be a string which contains "L"(left), "R"(right), or "M"(moveforward).

When insert this robot, shop id must be existing in our database, otherwise 404 status will return, also the robot must not
has the same coordinate with the existing robot which is in the same shop.

the valid response is like:

{
  "id": "6",
  "x": 4,
  "y": 4,
  "heading": "N",
  "commands": "LM"
}


5. shop robot update
-------------------
url: http://54.206.18.76/shop/<:id>/robot/<:rid>, http method is PUT only, otherwise 405 status will return.
Request json:

{"x":4,"y":4,"heading":"N","commands":"LM"},

json validation is same as robot add action.

when robot id is existing in the shop id, the update action will be executed and response 200 status with data like:

{
  "id": "6",
  "x": 4,
  "y": 4,
  "heading": "N",
  "commands": "LM"
}

otherwise 404 will return.


6. shop robot delete
--------------------
url: http://54.206.18.76/shop/<:id>/robot/<:rid>, httpd method is DELETE only, otherwise 405 status will return.
when robot id is existing in the provided shop id, then 200 will be returned with:

{
  "status": "ok"
}

otherwise resource not found.

7. shop robot execute commands
------------------------------
url: http://54.206.18.76/shop/<:id>/execute, httpd method is POST only, otherwise 405 status will return.
when robot id is existing in the provided shop id, the shop will trigger all robots to run their commands in "parallel":

{
  "id": "1",
  "width": "5",
  "height": "5",
  "robots": [
    {
      "id": "1",
      "x": 3,
      "y": "4",
      "heading": "W",
      "commands": "LM"
    },
    {
      "id": "2",
      "x": "0",
      "y": -1,
      "heading": "N",
      "commands": "LLM"
    },
    {
      "id": "4",
      "x": 2,
      "y": "3",
      "heading": "W",
      "commands": "LM"
    }
  ],
  "errors": [
    "robot id:2 has moved out of the shop range"
  ]
}

when there is any error happening during the robots moving, the error could be crashed, could be running out of shop
range, the execution will break out, and return all robots status.



Unit-test result
----------------
https://travis-ci.org/xu-tony/robotcafe/builds

 Summary:

  Classes:  0.00% (0/15)

  Methods: 60.26% (47/78)

  Lines:   75.27% (353/469)


  \RobotCafe\Controller::AbstractRobotCafeController

    Methods:  88.89% ( 8/ 9)   Lines:  95.74% ( 45/ 47)

  \RobotCafe\Controller::RobotController

    Methods:  20.00% ( 1/ 5)   Lines:  87.50% ( 56/ 64)

  \RobotCafe\Controller::ShopController

    Methods:  42.86% ( 3/ 7)   Lines:  88.37% ( 38/ 43)

  \RobotCafe\Mapper::RobotDbSqlMapper

    Methods:  85.71% ( 6/ 7)   Lines:  90.48% ( 57/ 63)

  \RobotCafe\Mapper::ShopDbSqlMapper

    Methods:  75.00% ( 3/ 4)   Lines:  35.38% ( 23/ 65)
  \RobotCafe\Model::Robot

    Methods:  87.50% (14/16)   Lines:  92.75% ( 64/ 69)
    
  \RobotCafe\Model::Shop

    Methods:  92.31% (12/13)   Lines:  97.22% ( 70/ 72)


