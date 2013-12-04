<?php namespace Example\Core;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class AbstractModel implements ArrayAccess, Countable, IteratorAggregate {

	abstract public function getDefaults();

	protected $defaults = [];
	protected $data = [];
	protected $hiddenProperties = [];
	protected $isModified = false;
	protected $isNew = true;

	public function __construct(array $data = [], $isNew = false) {
		$this->hydrate(array_merge($this->getDefaults(), $data), $isNew);
	}

	public function hydrate(array $data, $isNew = true) {
		$this->data = $data;
		$this->setNew($isNew);
	}

	public function isModified() {
		return $this->isModified;
	}

	public function setModified($isModified = true) {
		$this->isModified = $isModified;
	}

	public function isNew() {
		return $this->isNew;
	}

	public function setNew($isNew = true) {
		$this->isNew = $isNew;
	}

	public function rawData() {
		return $this->data;
	}

	public function set($property, $value) {
		if (is_null($property)) {
			$this->data[] = $value;
		} else {
			$this->data[$property] = $value;
		}
		$this->isModified = true;
	}

	public function get($property, $default = null) {
		if ( ! isset($this->data[$property])) {
			return $default;
		}
		return $this->data[$property];
	}

	public function toArray() {
		return array_diff_key($this->data, array_flip($this->hiddenProperties));
	}

	public function toJson() {
		return json_encode($this->toArray());
	}

	/****************************************************************/
	/* Interface Implementations and PHP Magic Methods              */
	/****************************************************************/

	public function __get($property) {
		return $this->get($property);
	}

	public function __set($property, $value) {
		$this->set($property, $value);
	}

	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	public function getIterator() {
		return new ArrayIterator($this->data);
	}

	public function count() {
		return count($this->data);
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}
}