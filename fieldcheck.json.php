<?php
chdir($_SERVER['DOCUMENT_ROOT']);
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE); //  Faster alternative to bootstrap?

$validators = explode(' ', $_POST['validators']);
$value = $_POST['value'];
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
    $function = $checks[$validator]['callback'];
    if(!function_exists($function)) {
      return array('succes' => FALSE, 'error' => 'unable to validate');
    } 
    else if(!$function($value)){
      return array('succes' => FALSE, 'error' => $checks[$validator]['error']);
    }
  }
  return array('succes' => TRUE);
}
