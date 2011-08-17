<?php

/*
 * Test PHP Router
 *
 * (c) August 2011 - Goldeneaglecoin
 *
 * Author: Manuel Odendahl - wesen@ruinwesen.com
 */

require_once(dirname(__FILE__)."/../vendor/simpletest/autorun.php");
require_once(dirname(__FILE__)."/../Router.php");

class TestRouter extends UnitTestCase {
  public function setUp() {
    $this->router = new Router\Router();
  }

  public function noRouteException() {
    try {
      $router->handle("/get");
      $this->assertFalse(true);
    } catch (Router\NoRouteException $e) {
      $this->assertTrue(true);
      $this->assertEqual($e->path, "/get");
    }
  }


  public function noRoute() {
    $this->router = new Router\Router(array("noRouteException" => false));
    try {
      $res = $router->handle("/get");
      $this->assertFalse($res);
    } catch (Router\NoRouteException $e) {
      $this->assertFalse(true);
    }
  }
  
  public function singleRoute() {
    $router->addRoute("/", "route");
    $res = $router->handle("/");
    $this->assertEqual($res, "route");

    /* check for leading slash */
    $res = $router->handle("");
    $this->assertEqual($res, "route");

    /* check that other routes don't work */
    try {
      $router->handle("/get");
      $this->assertFalse(true);
    } catch (Router\NoRouteException $e) {
      $this->assertTrue(true);
      $this->assertEqual($e->path, "/get");
    }
  }

  public function twoRoutes() {
    $router->addRoute("/p1", "p1");
    $router->addRoute("/p2", "p2");

    $res = $router->handle("/p1");
    $this->assertEqual($res, "p1");
    $res = $router->handle("p1");
    $this->assertEqual($res, "p1");

    $res = $router->handle("/p2");
    $this->assertEqual($res, "p2");
    $res = $router->handle("p2");
    $this->assertEqual($res, "p2");

      /* check that other routes don't work */
    try {
      $router->handle("/get");
      $this->assertFalse(true);
    } catch (Router\NoRouteException $e) {
      $this->assertTrue(true);
      $this->assertEqual($e->path, "/get");
    }
  }

  public function testSimpleCallback() {
    $router->addRoute("/p1", function () {
        return "p1";
      });
    $res = $router->handle("/p1");
    $this->assertEqual($res, "p1");
  }

  public function testAdditionCallbackArguments() {
    $router->addRoute("/p1", function ($a = 1) {
        return $a;
      });
    $res = $router->handle("/p1", 2);
    $this->assertEqual($res, 2);
    $res = $router->handle("/p1", 3);
    $this->assertEqual($res, 3);
  }

  public function testLeadingSlash() {
    $router->addRoute("p1", "p1");
    $res = $router->handle("/p1");
    $this->assertEqual($res, "p1");
    $res = $router->handle("p1");
    $this->assertEqual($res, "p1");
  }
    
  public function testRedirect() {
    $router->addRoute("/p1", "p1");
    $router->addRedirect("/p2", "/p1");

    $res = $router->handle("/p1");
    $this->assertEqual($res, "p1");
    $res = $router->handle("/p2");
    $this->assertEqual($res, "p1");
  }

  public function testHttpDispatch() {
  }
                                      
};

?>