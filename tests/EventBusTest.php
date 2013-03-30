<?php

require_once 'src/Event.php';
require_once 'src/EventBus.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * EventBus test case.
 */
class EventBusTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var EventBus
	 */
	private $eventBus;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->eventBus = new EventBus();

	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->eventBus = null;
		parent::tearDown ();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}

	public function testThrowsExceptionByInvalidEventType() {
		$this->setExpectedException('InvalidArgumentException', 'Event needs to be either string or array');
		$this->eventBus->listen(0, function() { });
	}

	public function testEventGetsAddToQueue() {
		$f = function() { };
		$this->eventBus->listen('myEvent', $f);

		$reflection = new \ReflectionObject($this->eventBus);

		/* @var $property ReflectionProperty */
		$property = $reflection->getProperty('_listener');
		$property->setAccessible(true);

		$expected = array('myEvent' => array(0 => $f));
		$this->assertEquals($expected, $property->getValue($this->eventBus));
	}

	public function testMultipleEventsGetsAddedToQueue() {
		$f = function() { };
		$this->eventBus->listen(array('myEvent', 'my2ndEvent'), $f);

		$reflection = new \ReflectionObject($this->eventBus);

		/* @var $property ReflectionProperty */
		$property = $reflection->getProperty('_listener');
		$property->setAccessible(true);

		$expected = array('myEvent'    => array(0 => $f),
						  'my2ndEvent' => array(0 => $f));

		$this->assertEquals($expected, $property->getValue($this->eventBus));
	}

	public function testListenerGetsNotified() {
		$that = $this;
		$f = function() use (&$that) {
			$that->assertTrue(true);
		};

		$this->eventBus->listen('myEvent', $f);
		$this->eventBus->notify('myEvent', null);
	}

	public function testEventDispatchingGetsStopped() {
		$that = $this;
		$f1 = function(Event $e) use(&$that) {
			$e->stop();
		};

		// Should not be called
		$f2 = function(Event $e) use(&$that){
			$that->fail('Closure f2 should not be called');
		};

		$this->eventBus->listen('myEvent', $f1);
		$this->eventBus->listen('myEvent', $f2);

		$this->eventBus->notify('myEvent');
	}

	public function testUnknownEventThrowsException() {
		$this->setExpectedException('InvalidArgumentException', 'Unknown event "myEvent"');
		$this->eventBus->notify('myEvent');
	}

}

