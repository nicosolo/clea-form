<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 07.07.17
 * Time: 13:36
 */

namespace Clea\Form;


class Validator
{
    /**
     * @var callable
     */
    private $callBack;

    /**
     * @var string
     */
    private $errorMessage;

    public function __construct(callable $callBack, $errorMessage = true)
    {
        $this->callBack = $callBack;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param $value
     * @return array|string|null|bool
     */
    public function validate($value){

        if($this->getCallBack() instanceof  \Respect\Validation\Validator){

            return $this->getCallBack()->validate($value);
        }else{
            return $this->getCallBack()($value);
        }

    }

    /**
     * @return callable
     */
    public function getCallBack(): callable
    {
        return $this->callBack;
    }

    /**
     * @param callable $callBack
     */
    public function setCallBack(callable $callBack)
    {
        $this->callBack = $callBack;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }



}