<?php


function fieldcheck_fieldcheck() {
  return array(
    'number' => array(
      'callback' => 'validate_number',
      'error' => 'This is not a number',
    ),
    'true' => array(
      'callback' => 'validate_true',
      'error' => 'This is not true',
    ),
  );
}