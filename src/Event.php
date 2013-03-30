<?php
/**
 * Event Object
 *
 * @author  Christopher Marchfelder <marchfelder@googlemail.com>
 * @license MIT
 */
class Event {

	/**
	 * Flag if we should proceed dispatching the event
	 *
	 * @var bool
	 */
	protected $_dispatch = true;

	/**
	 * The sender of the event (if any)
	 *
	 * @var object
	 */
	protected $_sender = null;

	/**
	 * Returns the sender of the event
	 *
	 * @return object
	 */
	public function getSender() {
		return $this->_sender;
	}

	/**
	 * Sets the sender of the event
	 *
	 * @param object $sender
	 *
	 * @return void
	 */
	public function setSender($sender) {
		$this->_sender = $sender;
	}

	/**
	 * Stop the event dispatching
	 *
	 * @return void
	 */
	public function stop() {
		$this->_dispatch = false;
	}

	/**
	 * Resume event dispatching (Yeah, I now - a bit strange)
	 *
	 * @return void
	 */
	public function resume() {
		$this->_dispatch = true;
	}

	/**
	 * Returns, if the event dispatching shall be stopped
	 *
	 * @return bool
	 */
	public function isStopped() {
		return !$this->_dispatch;
	}

}