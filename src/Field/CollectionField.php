<?php

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\FieldInterface;

class CollectionField extends Field implements \ArrayAccess, \Iterator
{

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var callable|string|array
     */
    private $field;

    /**
     * @var FieldInterface[]
     */
    private $collection;


    /**
     * CollectionField constructor.
     * @param $name
     * @param FieldInterface $field
     * @param null $validators
     */
    public function __construct($name, $validators = null)
    {
        parent::__construct($name, $validators);
    }

    public function validate(): bool
    {
        $valid = true;
        if(!$this->collection){
            return true;
        }
        foreach ($this->collection as $key => $field) {
            if (!$field->validate()) {
                $valid = false;
                $this->getErrors()->add($field->getErrors());
            }
        }
        return $valid;
    }


    public function getValue()
    {
        $values = [];
        if(!$this->collection){
            return $values;
        }
        foreach ($this->collection as $key => $field) {
            $values[$key] = $field->getValue();
        }
        return $values;
    }

    /**
     * @param $values
     * @return FieldInterface
     */
    public function setValue($values): FieldInterface
    {


        foreach ($values as $key => $value) {
            $this->add($key, $value);
        }
        return $this;
    }

    public function add($offset, $value = null): self
    {
        if (is_callable($this->getField())) {
            $this->collection[$offset] = call_user_func($this->getField());

            $this->collection[$offset]->setValue($value);
        } else {
            $this->collection[$offset] = new $this->field(null);
            $this->collection[$offset]->setValue($value);
        }
        return $this;
    }


    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    /**
     * @return array|callable|string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param array|callable|string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return FieldInterface[]
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * @param FieldInterface[] $collection
     */
    public function setCollection(array $collection)
    {
        $this->collection = $collection;
    }


    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->collection[$this->index];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->collection[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }

    public function toArray(): array
    {
        $fields = [];
        foreach ($this->getField() as $field) {
            $fields[$field->getName()] = $field->toArray();
        }
        return ["type" => "collection", "fields" => $fields] + parent::toArray();
    }
}