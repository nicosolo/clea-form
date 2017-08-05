<?php

namespace Clea\Form;


interface FieldInterface
{

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value
     * @return FieldInterface
     */
    public function setValue($value): FieldInterface;

    /**
     * @return Error
     */
    public function getErrors(): Error;

    /**
     * @return array
     */
    public function getValidators(): array;

    /**
     * @return bool
     */
    public function hasError(): bool;
}