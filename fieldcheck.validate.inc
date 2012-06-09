<?php

/**
 * Implementing hook_fieldcheck
 */
function fieldcheck_fieldcheck() {
    return array(
      'number' => array(
        'callback' => 'validate_number',
        'error' => t('This is not a number'),
      ),
      'required' => array(
        'callback' => 'validate_required',
        'error' => t('This is a required field'),
      ),
      'equals' => array(
        'callback' => 'validate_equals',
        'error' => t('This is not the correct value'),
      ),
    );
}


/**
 * Validation functions
 * @param type $value, $args
 * @return type boolean
 */

function validate_number($value) {
 return is_numeric($value);
}

function validate_required($value) {
  return !empty($value);
}

function validate_equals($value, $args){
  return in_array($value, $args);
}