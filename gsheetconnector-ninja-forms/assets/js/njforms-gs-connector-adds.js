jQuery(document).ready(function () {

 jQuery('.njform-gs-set-auth-expired-adds-interval').click(function () {
      var data = {
         action: 'njform_gs_set_auth_expired_adds_interval',
         security: jQuery('#njform_gs_auth_expired_adds_ajax_nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
         if (response.success) {
            jQuery('.njform-gs-auth-expired-adds').slideUp('slow');
         }
      });
   });

 jQuery('.njform-gs-close-auth-expired-adds-interval').click(function () {
      var data = {
         action: 'njform_gs_close_auth_expired_adds_interval',
         security: jQuery('#njform_gs_auth_expired_adds_ajax_nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
         if (response.success) {
            jQuery('.njform-gs-auth-expired-adds').slideUp('slow');
         }
      });
   });

});