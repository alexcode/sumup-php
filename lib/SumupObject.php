<?php

namespace Sumup;

class SumupObject implements \ArrayAccess, \Countable, \JsonSerializable
{
    public $required = [];
    public $_keys = [];
    protected $dirty = false;

    public function __construct(array $attributes = null)
    {
        $this->setAttributes($attributes);
        $this->validateRequired();
        $this->dirty = false;
    }

    public function setAttributes($attributes)
    {
        if ($attributes) {
            foreach ($attributes as $property => $value) {
                array_push($this->_keys, $property);
                if (property_exists($this, $property) && $this->{$property} !== $value) {
                    $this->{$property} = $value;
                    $this->dirty = true;
                }
            }
        }
    }

    public function validateRequired()
    {
        foreach ($this->required as $property) {
            if (!isset($this->{$property})) {
                $message = sprintf('The property %s is required for class %s',
                $property, get_class($this));
                throw new \Exception($message, 1);
            }
        }
    }

    public function isDirty()
    {
        return $this->dirty;
    }

    // ArrayAccess methods
    public function offsetSet($k, $v)
    {
        $this->$k = $v;
    }

    public function offsetExists($k)
    {
        return array_key_exists($k, $this->_keys);
    }

    public function offsetUnset($k)
    {
        unset($this->$k);
    }

    public function offsetGet($k)
    {
        return array_key_exists($k, $this->_keys) ? $this->_keys[$k] : null;
    }

    // Countable method
    public function count()
    {
        return count($this->_keys);
    }

    public function keys()
    {
        return $this->_keys;
    }

    public function values()
    {
        return array_values($this->__toArray());
    }

    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    public function __toJSON()
    {
        return json_encode($this->__toArray());
    }

    public function __toArray()
    {
        $results = [];
        foreach ($this->_keys as $property) {
            $v = $this->{$property};
            if ($v instanceof SumupObject) {
                $results[$property] = $v->__toArray();
            } else {
                $results[$property] = $v;
            }
        }

        return $results;
    }
}
