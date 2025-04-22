<?php
/*
 * njforms configuration and Intigration page
 * @since 1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}

$active_tab = ( isset($_GET['tab']) && sanitize_text_field($_GET["tab"])) ? sanitize_text_field($_GET['tab']) : 'integration';

$active_tab_name = '';
if($active_tab ==  'integration'){
  $active_tab_name = 'Integration';
}
elseif($active_tab ==  'system_status'){
  $active_tab_name = 'System Status';
}
elseif($active_tab ==  'extensions'){
  $active_tab_name = 'Extensions';
}

$plugin_version = defined('NINJAFORMS_GOOGLESHEET_VERSION') ? NINJAFORMS_GOOGLESHEET_VERSION : 'N/A';

?>
<div class="gsheet-header">
    <div class="gsheet-logo">
        <a href="https://www.gsheetconnector.com/"><i></i></a></div>
    <h1 class="gsheet-logo-text"><span class="title"><?php echo esc_html__( "Ninja Forms  - GSheetConnector", "gsheetconnector-ninjaforms" ); ?></span>
    <small>Version : <?php  echo esc_html($plugin_version, NINJAFORMS_GOOGLESHEET_VERSION); ?> </small></h1>
    <a href="https://support.gsheetconnector.com/kb" title="gsheet Knowledge Base" target="_blank" class="button gsheet-help"><i class="dashicons dashicons-editor-help"></i></a>
</div><!-- header #end -->
<span class="dashboard-gsc"><?php echo esc_html( __('DASHBOARD', 'gsheetconnector-ninjaforms' ) ); ?></span>
<span class="divider-gsc"> / </span>
<span class="modules-gsc"> <?php echo esc_html( __($active_tab_name, 'gsheetconnector-ninjaforms' ) ); ?></span>

<div class="wrap">
   <?php
   $tabs = array(
       'integration'    => __('Integration', 'gsheetconnector-ninjaforms'),
       //'demos'          => __('Demos', 'gsheetconnector-ninjaforms'),
       'system_status'  => __('System Status', 'gsheetconnector-ninjaforms'),
	     'extensions'            => __('Extensions', 'gsheetconnector-ninjaforms'),
      
   );
   echo '<div id="icon-themes" class="icon32"><br></div>';
   echo '<h2 class="nav-tab-wrapper">';
   foreach ($tabs as $tab => $name) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=njform-google-sheet-config&tab=$tab'>".esc_html($name)."</a>";
   }
   echo '</h2>';
   switch ($active_tab) {
      case 'settings' :
         $njform_settings = new NJforms_Googlesheet_Services();
         $njform_settings->add_settings_page();
         break;
      case 'integration' :
         $njform_integration = new NJforms_Googlesheet_Services();
         $njform_integration->add_integration();
         break;
       
     // case 'demos' :
       //  include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integration-demo-info.php" );
       //  break;
      case 'support' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-gs-integration-support.php" );
         break;
       case 'system_status' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integrate-system-info.php" );
         break;
	   case 'extensions' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/extensions.php" );
         break;
   }
   ?>
</div>
<?php include( NINJAFORMS_GOOGLESHEET_PATH . "/includes/pages/admin-footer.php" ); ?>

