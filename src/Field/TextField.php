<?php

namespace Clea\Form\Field;

use Clea\Form\Field;
use Respect\Validation\Validator as v;

class TextField extends Field
{
    /**
     * @return bool
     */
    public function validate(): bool
    {

        return v::max(255)->validate($this->getValue()) and parent::validate();
    }

    public function toArray(): array
    {
        return ["type" => "text"] + parent::toArray();
    }

}