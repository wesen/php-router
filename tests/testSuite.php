<?php

require_once(dirname(__FILE__).'/../vendor/simpletest/autorun.php');
require_once(dirname(__FILE__).'/../Router.php');

class RouterTestSuite extends TestSuite {
  function __construct() {
    $this->TestSuite('All Router tests');
    $this->addFile(dirname(__FILE__)."/testRouter.php");
  }
};

?>