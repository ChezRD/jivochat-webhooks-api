<?php

namespace ChezRD\Jivochat\Webhooks;

use InvalidArgumentException;

/**
 * Populates object properties via given array.
 * 
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 */
trait PopulateObjectViaArray
{
    /**
     * Populates (massively sets) object properties via given associative array of values.
     *
     * If object has a setter method for concrete property, the setter will be executed for populating this property.
     * Otherwise, the property will be set directly.
     *
     * @param array $data Associative array with object data to be populated.
     * Array keys must represent property names, array values - properties value.
     */
    public function populate(array $data)
    {
        foreach ($data as $name => $value) {
            if (!property_exists($this, $name)) {
                continue;
            }

            $setter = 'set' . join('', array_map('ucfirst', preg_split('/_+/', $name, 4, PREG_SPLIT_NO_EMPTY)));
            if (method_exists($this, $setter)) {
                $this->$setter($value);
                continue;
            }

            $this->$name = $value;
        }
    }

    /**
     * Internal function to use in {@link populateFieldData}
     *
     * @param string $field_name field name in event object
     * @param string $class_name name of the class to create from
     * @param array|null $data input data for field
     * @throws InvalidArgumentException
     */
    private function __populateFieldData($field_name, $class_name, $data) {
        if (is_array($data)) {
            $object = new $class_name();
            $object->populate($data);
            $this->{$field_name} = $object;
            return true;
        }

        if (is_a($data, $class_name)) {
            $this->{$field_name} = $data;
            return true;
        }

        return false;
    }

    /**
     * Populate complex data
     *
     * @param string $field_name field name in event object
     * @param string $class_name name of the class to create from
     * @param array|null $data input data for field
     * @param boolean $iterable is field iterable
     * @param boolean $allow_null allow empty value (set null)
     * @throws InvalidArgumentException
     */
    protected function populateFieldData($field_name, $class_name, $data, $iterable = false, $allow_null = false ) {
        if (empty($data) && $allow_null === true) {
            $this->{$field_name} = null;
            return;
        }

        if ($iterable === false) {
            if ( $this->__populateFieldData($field_name, $class_name, $data) ) return;
        } else {
            $has_error = false;

            foreach ($data as $item) {
                if (!(is_array($item) || (is_a($item, $class_name))) || $has_error) {
                    throw new InvalidArgumentException('Invalid data given.');
                }

                if (!$this->__populateFieldData($field_name, $class_name, $item)) {
                    $has_error = true;
                }
            }

            if ( !$has_error ) return;
        }

        throw new InvalidArgumentException('Invalid data given.');
    }
}