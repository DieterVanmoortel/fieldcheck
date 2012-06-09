<?php
chdir($_SERVER['DOCUMENT_ROOT']);
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE); //  Faster alternative to bootstrap?

// get all posted data
$validators = explode(' ', $_POST['validators']);
$value = $_POST['value'];

// DEV
//$value = 2;
//$validators = explode(' ', 'equals|2|3');

drupal_json_output(fieldcheck_validate($value, $validators));


/**
 * Validation router
 */
function fieldcheck_validate($value, $validators) {
  drupal_load('module', 'fieldcheck');
  module_load_include('inc', 'fieldcheck', 'fieldcheck.validate');
  $checks = fieldcheck_get_checks();
  if(isset($checks['files'])){
    foreach((array)$checks['files'] as $mod => $modfiles) {
      foreach((array)$modfiles as $file) {
        $filepath = str_replace('.inc', '', $file);
        module_load_include('inc', $mod, $filepath);
      }
    }
  }
  // validation of the entered value
  foreach((array)$validators as $validator) {
    // extract the function name from a validator which uses arguments
    $args = array();
    if(strpos($validator, '|')){
      $args = explode('|', $validator);
      $validator = array_shift($args);
    }
    // check if the function exists
    $function = $checks[$validator]['callback'];
    if(!function_exists($function)) {
      return array('succes' => FALSE, 'error' => 'unable to validate');
    } 
    // and validate the entered value
    else if(!$function($value, $args)){
        return array('succes' => FALSE, 'error' => $checks[$validator]['error']);
    }
  }
  // If all validations are succesfull
  return array('succes' => TRUE);
}
