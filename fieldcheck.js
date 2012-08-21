(function($){
  Drupal.behaviors.fieldCheck = {
    attach: function(context) {
      // hide validated fields
      if( Drupal.settings.fieldCheck.validated == true) {
        // still need to replace this validated span with an icon
        // ++ and maybe add some js to show the input field again when clicking on validation icon or label
        $('input').not('.error').hide().parents('.form-item').prepend('<span>Validated</span>');
      }
      // core functionality for validation
      if($('[validators]').length) {
        $('[validators]').bind('blur', function(){
          // spinner options : Move to function?
          var opts = {
            lines: 5, // The number of lines to draw
            length: 3, // The length of each line
            width: 3, // The line thickness
            radius: 4, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#000',// #rgb or #rrggbb
            speed: 0.7, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
          };
          element = $(this);
          var spinner = new Spinner(opts).spin();
          element.parent('.form-item').append(spinner.el);
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
                spinner.stop();
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
