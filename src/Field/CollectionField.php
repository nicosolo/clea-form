<?php

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\FieldInterface;

class CollectionField extends Field implements \ArrayAccess
{


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


}