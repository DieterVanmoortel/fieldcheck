
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
            },
            success: function(data){
              Drupal.behaviors.fieldCheck.changeFieldStatus(element, data);
            }
          });
        });
      }
    },
    changeFieldStatus: function(element, data) {
      element.parent().find('.validation-msg').remove();
      if(data.succes){
        element.removeClass('error').addClass('validated succes');
      }
      else{
        element.removeClass('succes').addClass('validated error');
        element.parent().append('<div class="validation-msg">' + data.error + '</div>');
      }
    }
  };

})(jQuery);

