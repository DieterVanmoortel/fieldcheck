<?php


function fieldcheck_fieldcheck() {
  return array(
    'number' => array(
      'callback' => 'validate_number',
      'error' => 'This is not a number',
    ),
    'required' => array(
      'callback' => 'validate_required',
      'error' => 'This is a required field',
    ),
  );
}

function validate_number($value) {
 return is_numeric($value);
}

function validate_required($value) {
  return zqempty($value);
}