==========

##Field Check module provides an API for easy and instant form field validation.

###How it works :

When building your form, add an array of validators to the attributes of your form item.

e.g.   

$form['test'] = array(
    '#type' => 'textfield',
    '#title' => 'test me',
    '#attributes' => array(
      'validators' => array('required', 'number', 'even'),
    ),
);

You can use the validators of fieldcheck module, listed below.. or you can write your own validators.

###How to write your own validators? => Hook_fieldcheck

Use your validators as keys in the array of fieldchecks.

function mymodule_fieldcheck() {
  return array(
    'number' => array(
      'callback' => 'validate_number',    // the function you will use to check the value
      'error' => t('This is not a number'), // the error message that will be displayed
      'file' = 'includes/fieldcheck.validate.inc, // optional, the file where the callback function resides. Must be an include file!
    ),
  );
}

Then just write your callback functions and you're done.

Fieldcheck module will do an on-blur validation of your form elements and will add an inline error message if validation fails. 
Further more, Fieldcheck adds the #element_validate to your form element, so that all values get checked again on submission of the form.