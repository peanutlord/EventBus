<?php
/**
 * EventBus System
 *
 * @author  Christopher Marchfelder <marchfelder@googlemail.com>
 * @license MIT
 */

/**
 * Class to dispatch events
 *
 * @author Christopher Marchfelder <marchfelder@googlemail.com>
 */
class EventBus {

	/**
	 * Contains all listeners
	 *
	 * @var array
	 */
	protected $_listener = array();

	/**
	 * Adds a set of listeners
	 *
	 * @throws \InvalidArgumentException
	 * @param  string|array $event
	 * @param  Closure      $toCall
	 * @return void
	 */
	public function listen($event, Closure $toCall) {
		if (!is_string($event) && !is_array($event)) {
			throw new \InvalidArgumentException('Event needs to be either string or array');
		}

		if (is_string($event)) {
			$event = array($event);
		}

		foreach ($event as $type) {
			$this->_listener[$type][] = $toCall;
		}
	}

	/**
	 * Notifies all listener's about an event
	 *
	 * @param object $sender
	 */
	public function notify($event, $sender = null) {
		if (!isset($this->_listener[$event])) {
			throw new \InvalidArgumentException(sprintf('Unknown event "%s"', $event));
		}

		// This should be loaded by a autoloader!
		$e = new Event();
		$e->setSender($sender);

		foreach ($this->_listener[$event] as $func) {
			$func($e);

			if ($e->isStopped()) {
				break;
			}
		}
	}
}