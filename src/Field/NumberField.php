<?php
/**
 * Created by IntelliJ IDEA.
 * User: nico
 * Date: 07/08/17
 * Time: 15:27
 */

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\Validator;

class NumberField extends Field
{
    public function validate(): bool
    {
        if(empty($this->getValue())){
            return true;
        }
        return is_numeric($this->getValue()) and parent::validate();
    }

    public function getValue()
    {
        if(!is_numeric(parent::getValue())) {
            return parent::getValue();
        }
        return parent::getValue()+0;
    }

}