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

$plugin_version = defined('NINJAFORMS_GOOGLESHEET_VERSION') ? NINJAFORMS_GOOGLESHEET_VERSION : 'N/A';

?>
<div class="gsheet-header">
    <div class="gsheet-logo">
        <a href="https://www.gsheetconnector.com/"><i></i></a></div>
    <h1 class="gsheet-logo-text"><span class="title"><?php echo esc_html__( "GSheetConnector For Ninja Forms", "gsheetconnector-ninja-forms" ); ?></span>
    <small><?php echo esc_html__( "Version :", "gsheetconnector-ninja-forms" ); ?> <?php  echo esc_html($plugin_version, NINJAFORMS_GOOGLESHEET_VERSION); ?> </small></h1>
   
	<ul> <li><a href="https://www.gsheetconnector.com/docs/ninja-forms-gsheetconnector/introduction" title="Document" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-file-lines"></i></a></li>
        <li><a href="https://www.gsheetconnector.com/support" title="Support" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-life-ring"></i></a></li>
        <li><a href="https://wordpress.org/plugins/gsheetconnector-ninja-forms/#developers" title="Changelog" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-bullhorn"></i></a></li>
    </ul>
	
</div><!-- header #end -->

<div class="breadcrumb">
<span class="dashboard-gsc"><?php echo esc_html( __('DASHBOARD', 'gsheetconnector-ninja-forms' ) ); ?></span>
<span class="divider-gsc"> / </span>
<span class="modules-gsc"> <?php echo esc_html( __($active_tab_name, 'gsheetconnector-ninja-forms' ) ); ?></span>
</div>


   <?php
  $tabs = array(
    'integration'    => esc_html__( 'Integration', 'gsheetconnector-ninja-forms' ),
    'system_status'  => esc_html__( 'System Status', 'gsheetconnector-ninja-forms' ),
);

   echo '<div id="icon-themes" class="icon32"><br></div>';
   echo '<div class="nav-tab-wrapper">';
   foreach ($tabs as $tab => $name) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=njform-google-sheet-config&tab=$tab'>".esc_html($name)."</a>";
   }
   echo '</div><div class="wrap-gsc">';
   switch ($active_tab) {
        case 'settings' :
            $njform_settings = new NJforms_Googlesheet_Services();
            $njform_settings->add_settings_page();
            break;
        case 'integration' :
            $njform_integration = new NJforms_Googlesheet_Services();
            $njform_integration->add_integration();
            break;
        case 'system_status' :
            include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integrate-system-info.php" );
            break;
   }
   ?>
</div>
<?php include( NINJAFORMS_GOOGLESHEET_PATH . "/includes/pages/admin-footer.php" ); ?>

