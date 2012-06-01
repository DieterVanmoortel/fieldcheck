
(function($){
  Drupal.behaviors.fieldCheck = {
    attach: function(context) {
      if($('[validators]').length) {
        $('[validators]').bind('blur', function(){
          console.log($(this).attr('validators'));
          element = $(this);
          $.ajax({
            url: Drupal.settings.basePath + 'sites/all/modules/dev/fieldcheck/fieldcheck.json.php',
            dataType: "json",
            type: "POST",
            data: {
              id: element.attr('id'),
              type: 'validate',
              validate: event.data.validate,
              value: element.val(),
//              requiredValue: Drupal.behaviors.featureForm.getRequiredValue( event.data.requiredValue )
            },
            success: function(data)
            {
              if( data.status )
              {
//                Drupal.behaviors.featureForm.changeFieldStatus(element, 'status-success');
              }
              else {
//                Drupal.behaviors.featureForm.changeFieldStatus(element, 'status-error', data.error);
              }
            }
          });
            });
          }
        }

  };

})(jQuery);

