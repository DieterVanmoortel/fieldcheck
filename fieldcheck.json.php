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
$element = $_POST['element'];
if(empty($value) || empty($element)){
  header("Status: 403 Forbidden");
  header("Cache-Control: no-cache");
  exit('Invalid Page Request');
}
// Check if these are european chars..
//mb_substitute_character(NULL);
//
//$mb = mb_convert_encoding($value, "ISO-8859-15");
//
//if(strlen($mb) == 0){
//  return drupal_json_encode(array('status' => FALSE, 'error' => 'Invalid Charset', 'element' => $element));
//}

drupal_json_output(fieldcheck_validate($value, $validators, $element));


/**
 * Validation router
 */
function fieldcheck_validate($value, $validators, $element) {
  // first get all validation functions & error msgs & load necessary files
  drupal_load('module', 'fieldcheck');
//  module_load_include('inc', 'fieldcheck', 'fieldcheck.validate');
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
    fieldcheck_get_args($validator, $args);
    // check if the function exists
    $function = $checks[$validator]['callback'];
    if(!function_exists($function)) {
      return array('succes' => FALSE, 'error' => 'unable to validate', 'element' => $element);
    } 
    // and validate the entered value
    else if(!$function($value, $args)){
        return array('succes' => FALSE, 'error' => $checks[$validator]['error'], 'element' => $element);
    }
  }
  // If all validations are succesfull
  return array('succes' => TRUE, 'element' => $element);
}
