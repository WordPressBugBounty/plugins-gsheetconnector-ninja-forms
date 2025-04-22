<?php
/*
 * Ninja Forms GS Dashboard Widget
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}
?>
<div class="dashboard-content">
   <?php
   $gs_connector_service = new njforms_Googlesheet_Services();

   $forms_list = $gs_connector_service->get_forms_connected_to_sheet();
   

   ?>
   <div class="main-content">
      <div>
         <h3><?php echo __("Ninja Forms connected with Google Sheets.", "gsheetconnector-ninjaforms"); ?></h3>
		  
		  
		  <style>
			  .widget-table { border:1px solid #eee; width:100%; }
			  .widget-table th { text-align: left; background: #eee; padding: 2px 3px; border-bottom: 1px solid #eee; }
			  .widget-table td { text-align: left; background: #fff; padding: 2px 3px; word-wrap: break-word; }
			  .widget-table td:nth-child(1) {width:50%;}
		  </style>
		  
		  <table class="widget-table">
    <tr>
        <th>Form Name</th>
        
    </tr>
    <?php
    if (!empty($forms_list)) {
        foreach ($forms_list as $key => $value) {
           ?>
                <tr>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=ninja-forms&form_id=' . $value->ID); ?>" target="_blank">
                            <?php echo esc_html($value->title); ?>
                        </a>
                    </td>
                    
                </tr>
                <?php
            
        }
    } else {
        // No forms found
        ?>
        <tr>
            <td colspan="2"><?php echo __("No Ninja Forms are connected with Google Sheets.", "gsheetconnector-ninjaforms"); ?></td>
        </tr>
        <?php
    }
    ?>
</table>


	  
		  
         
      </div>
   </div> <!-- main-content end -->
</div> <!-- dashboard-content end -->
<style type="text/css">
.postbox-header .hndle {
justify-content: flex-start !important;
}
</style>