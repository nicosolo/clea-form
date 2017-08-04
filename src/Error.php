<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 07.07.17
 * Time: 23:53
 */

namespace Clea\Form;


class Error implements \ArrayAccess
{

    private $errors = [];



    public function add($value){
        $this->errors[] = $value;
    }

    public function remove($key){
        $this->offsetUnset($key);
    }

    /**
     * Does this collection have a given key?
     *
     * @param  string $key The data key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->errors);
    }

    /**
     * Get collection item for key
     *
     * @param string $key The data key
     *
     * @return mixed The key's value, or the default value
     */
    public function offsetGet($key)
    {
        return $this->errors[$key];
    }

    /**
     * Set collection item
     *
     * @param string $key   The data key
     * @param mixed  $value The data value
     */
    public function offsetSet($key, $value)
    {
        if(is_null($key)){
            $this->errors[] = $value;
        }else{
            $this->errors[$key] = $value;
        }

    }

    /**
     * Remove item from collection
     *
     * @param string $key The data key
     */
    public function offsetUnset($key)
    {
        unset($this->errors[$key]);
    }

    /********************************************************************************
     * Countable interface
     *******************************************************************************/

    /**
     * Get number of items in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->errors);
    }

    /********************************************************************************
     * IteratorAggregate interface
     *******************************************************************************/

    /**
     * Get collection iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->errors);
    }

    function __toString(): string
    {
        if(count($this->errors) <= 0){
            return "";
        }
        return array_shift($this->errors);
    }

}