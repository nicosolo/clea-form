<?php

namespace Clea\Form;


class Field implements FieldInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var Error
     */
    private $errors;

    /**
     * @var Validator[]
     */
    private $validators;

    /**
     * @var null|string
     */
    private $label;

    /**
     * @var \Clea\Form\Form
     */
    private $form;



    /**
     * Field constructor.
     * @param $name
     * @param null $validators
     */
    public function __construct($name, $validators = null)
    {
        $this->errors = new Error;
        if ($validators instanceof Validator) {
            $this->addValidator($validators);
        } elseif ($validators == null) {
            $this->validators = [];
        } else {
            $this->validators = $validators;
        }

        $this->fieldName = $name;
    }

    public function validate(): bool
    {
        foreach ($this->getValidators() as $validator) {
            if (!$validator->validate($this->getValue())) {
                $this->errors->add($validator->getErrorMessage());
            }
        }
        return !$this->hasError();
    }

    public function getName(): string
    {
        return $this->getFieldName();
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): FieldInterface
    {
        $this->value = $value;
       return $this;
    }


    /**
     * @return mixed
     */
    public function getErrors(): Error
    {
        return $this->errors;
    }

    public function hasError(): bool
    {

        if ($this->errors->count() >= 1) {
            return true;
        }
        return false;
    }

    /**
     * @return Validator[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * @param Validator[] $validators
     */
    public function setValidator(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param Validator $validator
     * @return Field
     */
    public function addValidator(Validator $validator): self
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * @return null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param null $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }


    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }



}