<?php

namespace Clea\Form;


use Psr\Http\Message\ServerRequestInterface;

class Form implements FormInterface
{
    /**
     * @var boolean
     */
    private $error = false;

    /**
     * @var string
     */
    private $action = "";

    /**
     * @var string
     */
    private $method = "GET";

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var \ArrayAccess
     */
    protected $data;

    public function __construct($name = null, $data = null)
    {
        $this->data = $data;
        $this->name = $name;
        $this->build();
    }

    /**
     * @return FormInterface
     */
    public function build(): FormInterface
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }


    public function isSubmitted(ServerRequestInterface $request)
    {
        $method = strtoupper($this->getMethod());
        if ($request->getMethod() != $method) {
            return false;
        }

        if ($method == "POST") {
            $params = $request->getParsedBody();
        } else {
            $params = $request->getQueryParams();
        }
        if (isset($params[$this->getName()])) {
            return true;
        }

        return false;

    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $method = strtoupper($request->getMethod());

        if ($method == "POST") {
            $params = $request->getParsedBody();
        } else {
            $params = $request->getQueryParams();
        }

        foreach ($this->getFields() as $field) {

            if (isset($params[$field->getName()])) {
                $field->setValue($params[$field->getName()]);
            }
        }
        if ($this->data != null and $this->data instanceof \ArrayObject) {
            $this->setData($this->getData());
        }
    }

    public function setData($data)
    {
        foreach ($data as $key => $value) {
            $this->data->offsetSet($key, $value);
        }

    }


    /**
     * @param \ArrayAccess $data
     */
    public function handleData($data)
    {

        foreach ($this->getFields() as $field) {

            if (isset($data[$field->getName()])) {
                $field->setValue($data[$field->getName()]);
            }
        }


    }


    /**
     * @return boolean
     */
    public function validate(): bool
    {
        foreach ($this->getFields() as $field) {

            if (!$field->validate()) {
                $this->setError(true);
            }
        }
        if ($this->hasError()) {
            return false;
        }

        return true;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [];
        foreach ($this->getFields() as $field) {
            $data[$field->getName()] = $field->getValue();
        }
        return $data;

    }

    /**
     * @param FieldInterface $field
     * @return FormInterface
     */
    public function addField(FieldInterface $field): FormInterface
    {
        $field->setForm($this);
        $this->fields[$field->getName()] = $field;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error;
    }

    /**
     * @param bool $error
     */
    public function setError(bool $error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FieldInterface[] $fields
     * @return void
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }


    /**
     * @param string $name
     * @return void
     */
    public function removeField(string $name)
    {
        unset($this->fields[$name]);
    }

    /**
     * @param $name
     * @return FieldInterface
     */
    public function get(string $name): FieldInterface
    {
        if (!isset($this->fields[$name])) {
            return false;
        }
        return $this->fields[$name];
    }

    /**
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }


}
