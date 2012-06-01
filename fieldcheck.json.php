<?php

chdir($_SERVER['DOCUMENT_ROOT']);
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
//require_once DRUPAL_ROOT . '/includes/unicode.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE); // faster bootstrap : 3

drupal_load('module', 'transliteration');

print fieldcheck_validate();


/**
 * Validation router
 */
function fieldcheck_validate() {
  module_load_include('inc', 'feature_form', 'includes/feature_form.validate');









  $status = TRUE;
  $value 					= $_POST['value'];
  $validate 			= $_POST['validate'];
  $required_value	=  isset($_POST['requiredValue']) ? $_POST['requiredValue'] : '';
  $error 					= '';
	// Update also feature_form.js !
	$placeholders		= array(
												'edit-personal-phone' 				=> 'Ex. 021234567',
												'edit-personal-cellphone'			=> 'Ex. 0499234567',
												'edit-partner-more-phone' 		=> 'Ex. 021234567',
												'edit-partner-more-cellphone'	=> 'Ex. 0499234567',
												);

	// IE fix (placeholders)
	if ( isset( $placeholders[$field_id] ) && $placeholders[$field_id] == $value ) {
		$value = '';
	}

  foreach ($validate as $val) {
    if ($status == TRUE) {

			$this_value = $value;
			$this_required_value = filter_required_value( $field_id, $val, $required_value); // @TODO : dit anders oplossen!

      if (!empty($this_required_value)) {

				if ( is_array($this_required_value) )
				{
					array_unshift($this_required_value, $value);
					$this_value = $this_required_value;
				}
				else {
					$this_value = array($value, $this_required_value);
				}
      }

      if (in_array($val, array_keys($validators)) && call_user_func('feature_form_is_valid_' . $val, $this_value)) {
        $status = TRUE;
      }
      else {
        $status = FALSE;
        $error = $validators[$val]['error'];
      }
    }
  }
  return drupal_json_encode(array('status' => $status, 'error' => $error));
}

function feature_form_ajax_callback(){

  $op = check_plain($_POST['op']);
  $aid = check_plain($_POST['aid']);
  switch($op) {
    case 'check-agent' :
      include(drupal_get_path('module', 'feature_form') . '/includes/feature_form.helpers.inc');
      $agent = node_load($aid);
      if($agent){
        $block = feature_form_build_agentblock($agent);
        return drupal_json_output($block);
      }
    break;
  }
}


/*
 * Helper function for exceptional form fields
 * Validations on blur events with both required
 * and non-required values
 * @param $field = the id of the input field
 * @param $func = name of the validation function
 * @param $return = the initial value
 */
function filter_required_value( $field, $func, $return )
{
	$special_fields 			= array(
																'edit-personal-email-repeat',
																'edit-partner-more-email-repeat',
															);

	$has_required_values 	= array(
																'valid_email_repeat',
															);

	if ( in_array( $field, $special_fields ) && !in_array( $func, $has_required_values ) )
	{
		$return = ''; // Filter requiredValue for some validation functions
	}

	return $return;
}
