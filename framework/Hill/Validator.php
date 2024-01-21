<?php

namespace Hill;

/**
 * Simple validator class
 */
class Validator
{
    private $fields;

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function validate(array $data = [])
    {
        $errors = [];

        foreach ($this->fields as $field => $constraints) {
            foreach ($constraints  as $constraint) {
                if (($message = call_user_func(
                    $constraint,
                    $field,
                    isset($data[$field]) ? $data[$field] : null
                )) !== null) {
                    $errors[$field] = $message;

                    break;
                }
            }
        }

        return $errors;
    }
}
