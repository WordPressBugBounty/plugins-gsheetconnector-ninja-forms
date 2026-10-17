jQuery(document).ready(function () {
  jQuery(".njform-gs-set-auth-expired-adds-interval").click(function () {
    var data = {
      action: "njform_gs_set_auth_expired_adds_interval",
      security: jQuery("#njform_gs_auth_expired_adds_ajax_nonce").val(),
    };

    jQuery.post(ajaxurl, data, function (response) {
      if (response.success) {
        jQuery(".njform-gs-auth-expired-adds").slideUp("slow");
      }
    });
  });

  jQuery(".njform-gs-close-auth-expired-adds-interval").click(function () {
    var data = {
      action: "njform_gs_close_auth_expired_adds_interval",
      security: jQuery("#njform_gs_auth_expired_adds_ajax_nonce").val(),
    };

    jQuery.post(ajaxurl, data, function (response) {
      if (response.success) {
        jQuery(".njform-gs-auth-expired-adds").slideUp("slow");
      }
    });
  });
});

/** get the url for connected sheet  */
function getSheetLink() {
  $sheet_str = "https://docs.google.com/spreadsheets/d/";
  $sheetId = jQuery("#field_map_ninja_gsid").val();
  $tabId = jQuery("#field_map_ninja_gstabid").val();
  $sheetURL = $sheet_str + $sheetId + "/edit#gid=" + $tabId;
  window.open($sheetURL, "_blank");
}
