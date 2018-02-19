<?php


namespace Clea\Form;


interface FormInterface
{
    public function build(): self;
    public function getName(): ?string;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function handleRequest(\Psr\Http\Message\ServerRequestInterface $request);

    /**
     * @param $data
     * @return void
     */
    public function handleData($data);

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return bool
     */
    public function hasError(): bool;

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array;

    /**
     * @param FieldInterface[] $fields
     * @return mixed
     */
    public function setFields(array $fields);

    /**
     * @param FieldInterface $field
     * @return FormInterface
     */
    public function addField(FieldInterface $field): FormInterface;

    /**
     * @param string $name
     * @return mixed
     */
    public function removeField(string $name);

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name);

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}