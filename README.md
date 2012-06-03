fieldcheck
==========

##Field Check module provides an API for easy and instant form field validation.

###How it works :

When building your form, add an array of validators to the attributes of your form item.

e.g.   

$form['test'] = array( /n
    '#type' => 'textfield',/n
    '#title' => 'test me',/n
    '#attributes' => array(/n
      'validators' => array('required', 'number', 'even'),/n
    ),/n
);/n

You can use the validators of fieldcheck module, listed below.. or you can write your own validators.

###How to write your own validators? => Hook_fieldcheck

Use your validators as keys in the array of fieldchecks.

function mymodule_fieldcheck() {/n
  return array(/n
    'number' => array(/n
      'callback' => 'validate_number',    // the function you will use to check the value/n
      'error' => t('This is not a number'), // the error message that will be displayed/n
      'file' = 'includes/fieldcheck.validate.inc, // optional, the file where the callback function resides. Must be an include file!/n
    ),/n
  );/n
}/n

Then just write your callback functions and you're done.

####Fieldcheck module will do an on-blur validation of your form elements and will add an inline error message if validation fails. 
####Further more, Fieldcheck adds the #element_validate to your form element, so that all values get checked again on submission of the form.