<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 07.07.17
 * Time: 13:36
 */

namespace Clea\Form;


class Field
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
     * @var string
     */
    private $group;
    /**
     * @var \Clea\Form\Form
     */
    private $parentForm;

    /**
     * Field constructor.
     * @param string $name
     * @param null $value
     */
    public function __construct($name, $validators = [], $label = null, $group = "default")
    {
        $this->errors = new Error;
        if ($validators instanceof Validator) {
            $this->addValidator($validators);
        } else {
            $this->validators = $validators;
        }

        $this->fieldName = $name;
        $this->label = $label;
        $this->group = $group;
    }

    public function validate()
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

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function hasError()
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
     * @param $callBack
     * @param $errorMessage
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
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     */
    public function setGroup(string $group)
    {
        $this->group = $group;
    }

    /**
     * @return Form
     */
    public function getParentForm(): Form
    {
        return $this->parentForm;
    }

    /**
     * @param Form $parentForm
     */
    public function setParentForm(Form $parentForm)
    {
        $this->parentForm = $parentForm;
    }


}