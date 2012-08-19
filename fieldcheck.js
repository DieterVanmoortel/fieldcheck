(function($){
  Drupal.behaviors.fieldCheck = {
    attach: function(context) {
      // hide validated fields
      if( Drupal.settings.fieldCheck.validated == true) {
        $('input').not('.error').hide().parents('.form-item').prepend('<span>Validated</span>');
      }
      // core functionality for validation
      if($('[validators]').length) {
        $('[validators]').bind('blur', function(){
          element = $(this);
          // Exception : required values
          if(element.val() == '' && element.hasClass('required')){
            data = new Object();
            data.succes = false;
            data.error = Drupal.t('This is a required field');
            Drupal.behaviors.fieldCheck.changeFieldStatus(element, data);
          }
          else if(element.val() != ''){
            $.ajax({
              url: Drupal.settings.basePath + Drupal.settings.fieldCheck.modulePath + '/fieldcheck.json.php',
              dataType: "json",
              type: "POST",
              data: {
                validators: element.attr('validators'),
                value: element.val()
              },
              success: function(data){
                Drupal.behaviors.fieldCheck.changeFieldStatus(element, data);
              }
            });
          }
        });
      }
    },
    changeFieldStatus: function(element, data) {
      element.parent().find('.validation-msg').remove();
      if(data.succes){
        element.removeClass('error').addClass('validated-succes');
      }
      else{
        element.removeClass('succes').addClass('validated-error');
        element.parent().append('<div class="validation-msg">' + data.error + '</div>');
      }
    }
  };

})(jQuery);
