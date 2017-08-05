<?php

namespace Clea\Form;


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

    public function __construct($name = null)
    {
        $this->name = $name;
        $this->build();
    }

    /**
     * @return $this
     */
    protected function build(): self
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


    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function handleRequest(\Psr\Http\Message\ServerRequestInterface $request): void
    {

        if (strtolower($request->getMethod()) == "post") {
            $params = $request->getParsedBody();
        } else {
            $params = $request->getQueryParams();
        }

        $this->handleData($params);
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
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }


    /**
     * @param string $name
     */
    public function removeField(string $name)
    {
        unset($this->fields[$name]);
    }

    /**
     * @param $name
     * @return Filed
     */
    public function get(string $name)
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