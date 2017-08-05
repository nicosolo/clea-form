<?php

namespace Clea\Form\Field;


use Clea\Form\Field;
use Clea\Form\FieldInterface;

class DateField extends Field
{
    /**
     * @return int
     */
    public function getTimeStamp(){
        return $this->getDateTime()->getTimestamp();
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime{
        return new \DateTime($this->getValue());
    }

}