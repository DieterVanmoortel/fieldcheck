<?php
// TODO clean this up
chdir($_SERVER['DOCUMENT_ROOT']);
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
//require_once DRUPAL_ROOT . '/includes/unicode.inc';

drupal_bootstrap(6); // faster bootstrap : 3

drupal_load('module', 'transliteration');
$validators = explode(' ', $_POST['validators']);
$value = $_POST['value'];

$value = 5;
$validators = array('number', 'true');

drupal_json_output(fieldcheck_validate($value, $validators));


/**
 * Validation router
 */
function fieldcheck_validate($value, $validators) {
  include (DRUPAL_ROOT . '/sites/all/modules/custom/fieldcheck/fieldcheck.validate.inc');
  $checks = fieldcheck_fieldcheck();
  
  foreach((array)$validators as $validator) {
    $function = $checks[$validator]['callback'];
    if(function_exists($function) && !$function($value)){
      return array('succes' => FALSE, 'error' => $checks[$validator]['error']);
    }
  }

  return array('succes' => TRUE);
}
