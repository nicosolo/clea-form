<?php

namespace Clea\Form\Field;

use Clea\Form\Field;


class TextField extends Field
{
    /**
     * @return bool
     */
    public function validate(): bool
    {

        v::max(255)->validate($this->getValue()) and parent::validate();
    }


}