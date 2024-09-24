<?php

namespace Neon;

/**
 * Base validator class
 */
class Validator
{
  /**
   * @var array $fields
   */
  private $fields;

  /**
   * Constructor
   * 
   * Example:
   * 
   * $validator = new \Hill\Validator([
   *      'first_name'    => [function($field, $value) {
   *          if (!preg_match("/^[0-9a-z]+$/i", $value)) {
   *              return "Your first name using forbidden symbols";
   *          }
   * 
   *          return null;
   *      }],
   *      'email'         => [function($field, $value) {
   *          if (!preg_match("/^[^@]+@[^$]+$/i", $value)) {
   *              return "Invalid email format";
   *          }
   * 
   *          return null;
   *      }]
   * ]);
   * 
   * @param array $fields Validation fields
   */
  public function __construct(array $fields = [])
  {
    $this->fields = $fields;
  }

  /**
   * Validates data by fields rules and returns errors if
   * one of fields is invalid.
   * 
   * @param array $data Input data
   * 
   * @return string[] $errors
   */
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
