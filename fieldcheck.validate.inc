<?php


function fieldcheck_fieldcheck() {
  return array(
    'number' => array(
      'callback' => 'validate_number',
      'error' => t('This is not a number'),
      'file' => '',
    ),
    'required' => array(
      'callback' => 'validate_required',
      'error' => t('This is a required field'),
    ),
  );
}

function validate_number($value) {
 return is_numeric($value);
}

function validate_required($value) {
  return !empty($value);
}











