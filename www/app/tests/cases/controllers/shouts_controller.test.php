<?php 
/* SVN FILE: $Id$ */
/* ShoutsController Test cases generated on: 2009-07-26 13:07:25 : 1248609505*/
App::import('Controller', 'Shouts');

class TestShouts extends ShoutsController {
	var $autoRender = false;
}

class ShoutsControllerTest extends CakeTestCase {
	var $Shouts = null;

	function setUp() {
		$this->Shouts = new TestShouts();
		$this->Shouts->constructClasses();
	}

	function testShoutsControllerInstance() {
		$this->assertTrue(is_a($this->Shouts, 'ShoutsController'));
	}

	function tearDown() {
		unset($this->Shouts);
	}
}
?>