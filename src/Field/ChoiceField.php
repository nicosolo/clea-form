<?php


namespace Clea\Form\Field;


use Clea\Form\Field;

class ChoiceField extends Field
{
    /**
     * @var callable|\ArrayAccess
     */
    private $choices = [];


    /**
     * @return \ArrayAccess
     */
    public function getChoices()
    {
        if(is_callable($this->choices)){
            return ($this->choices)($this);
        }

        return $this->choices;
    }

    /**
     * @param callable|\ArrayAccess $choices
     */
    public function setChoices($choices)
    {
        $this->choices = $choices;
    }


    /**
     * @param $key
     * @param $value
     */
    public function addChoice(string $key, $value){
        $this->choices[$key] = $value;
    }



}