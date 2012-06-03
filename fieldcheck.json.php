<?php
chdir($_SERVER['DOCUMENT_ROOT']);
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE); //  Do we need to bootstrap?

$validators = explode(' ', $_POST['validators']);
$value = $_POST['value'];

drupal_json_output(fieldcheck_validate($value, $validators));


/**
 * Validation router
 */
function fieldcheck_validate($value, $validators) {
  module_load_include('inc', 'fieldcheck', 'fieldcheck.validate');
  $checks = fieldcheck_fieldcheck();
  foreach((array)$validators as $validator) {
    $function = $checks[$validator]['callback'];
    if(!function_exists($function)) {
      continue;
    } 
    else if(!$function($value)){
      return array('succes' => FALSE, 'error' => $checks[$validator]['error']);
    }
  }

  return array('succes' => TRUE);
}
