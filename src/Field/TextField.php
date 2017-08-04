<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 07.07.17
 * Time: 18:16
 */

namespace Clea\Form\Field;

use Clea\Form\Field;


class TextField extends Field
{
    public function validate()
    {

        v::max(255)->validate($this->getValue());
    }


}