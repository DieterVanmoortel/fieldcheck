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
      'even' => array(
        'callback' => 'validate_even',
        'error' => t('This is not an even value'),
      ),
      'uneven' => array(
        'callback' => 'validate_even',
        'error' => t('This is not an uneven value'),
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

function validate_even($value) {
  return (is_numeric($value) && fmod($value, 2) == 0);
}

function validate_uneven($value) {
    return (is_numeric($value) && fmod($value, 2) == 1);
}