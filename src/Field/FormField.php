<?php

namespace Clea\Form\Field;


use Clea\Form\Error;
use Clea\Form\Field;
use Clea\Form\FieldInterface;
use Clea\Form\Form;
use Clea\Form\FormInterface;


class FormField extends Field
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * FormField constructor.
     * @param FormInterface $form
     * @param null $validators
     */
    public function __construct(FormInterface $form, $validators = null)
    {
        $this->form = $form;
        parent::__construct($this->form->getName(), $validators);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->form->validate();
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->form->getData();
    }

    /**
     * @param $values
     * @return FieldInterface
     */
    public function setValue($values): FieldInterface
    {
        $this->form->handleData($values);
        return $this;
    }

    /**
     * @return Error
     */
    public function getErrors(): Error
    {
        $error = new Error();

        foreach ($this->form->getFields() as $field) {
            if ($field->hasError()) {
                $error[$field->getName()] = $field->getErrors();
            }
        }
        return $error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        if ($this->getErrors()->count() >= 1) {
            return true;
        }
        return false;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function get(string $name): ?FieldInterface{
       return  $this->form->get($name);
    }

    public function getFields()
    {
        return $this->form->getFields();
    }


    public function toArray(): array
    {
        return ["type" => "form", "sub" => $this->toArray()] + parent::toArray();
    }
}