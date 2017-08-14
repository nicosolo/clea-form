<?php
/**
 * Created by IntelliJ IDEA.
 * User: nico
 * Date: 13/08/17
 * Time: 20:46
 */

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\FieldInterface;

class JsonField extends Field
{

    public function setValue($value): FieldInterface
    {
        if ($value instanceof \Traversable) {
            return parent::setValue($value);
        } else {
            return parent::setValue(json_decode($value, true));
        }
    }

    public function getJson(){
        if ($this->getValue() instanceof \Traversable) {
            return json_encode($this->getValue());
        }
    }

    public function getValue()
    {
        return parent::getValue(); // TODO: Change the autogenerated stub
    }


}