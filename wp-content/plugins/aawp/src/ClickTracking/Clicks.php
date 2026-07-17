<?php

namespace AAWP\ClickTracking;

use Iterator;
use Countable;

/**
 * Class Clicks.
 *
 * @since 3.20
 */
class Clicks implements Countable, Iterator {

	/**
	 * Iterator position.
	 *
	 * @since 3.20
	 *
	 * @var int
	 */
	private $iterator_position = 0;

	/**
	 * List of clicks.
	 *
	 * @since 3.20
	 *
	 * @var array
	 */
	private $list = [];

	/**
	 * Return the current element.
	 *
	 * @since 3.20
	 *
	 * @return \AAWP\ClickTracking\Click|null Return null when no items in collection.
	 */
	public function current() {

		return $this->valid() ? $this->list[ $this->iterator_position ] : null;
	}

	/**
	 * Move forward to next element.
	 *
	 * @since 3.20
	 */
	public function next() {

		++ $this->iterator_position;
	}

	/**
	 * Return the key of the current element.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function key() {

		return $this->iterator_position;
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @since 3.20
	 *
	 * @return bool
	 */
	public function valid() {

		return isset( $this->list[ $this->iterator_position ] );
	}

	/**
	 * Rewind the Iterator to the first element.
	 *
	 * @since 3.20
	 */
	public function rewind() {

		$this->iterator_position = 0;
	}

	/**
	 * Count number of Clicks in a Queue.
	 *
	 * @since 3.20
	 *
	 * @return int
	 */
	public function count() {

		return count( $this->list );
	}

	/**
	 * Push click to list.
	 *
	 * @since 3.20
	 *
	 * @param \AAWP\ClickTracking\Click $click Click.
	 */
	public function push( $click ) {

		if ( ! is_a( $click, '\AAWP\ClickTracking\Click' ) ) {
			return;
		}
		$this->list[] = $click;
	}

	/**
	 * Clear collection.
	 *
	 * @since 3.20
	 */
	public function clear() {

		$this->list              = [];
		$this->iterator_position = 0;
	}
}
