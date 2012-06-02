
(function($){
  Drupal.behaviors.fieldCheck = {
    attach: function(context) {
      if($('[validators]').length) {
        $('[validators]').bind('blur', function(){
          element = $(this);
          $.ajax({
            url: Drupal.settings.basePath + 'sites/all/modules/custom/fieldcheck/fieldcheck.json.php',
            dataType: "json",
            type: "POST",
            data: {
              validators: element.attr('validators'),
              value: element.val(),
//              requiredValue: Drupal.behaviors.featureForm.getRequiredValue( event.data.requiredValue )
            },
            success: function(data){
              console.log(data);
              if( data.status ) {
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

