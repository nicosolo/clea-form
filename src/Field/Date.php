<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 09.07.17
 * Time: 01:46
 */

namespace Clea\Form\Field;


use Clea\Form\Field;

class Date extends Field
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