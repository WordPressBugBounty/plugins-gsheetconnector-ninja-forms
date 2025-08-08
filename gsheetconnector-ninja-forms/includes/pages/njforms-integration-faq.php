<div class="faq-content">
<div class="wrap-main">

<div class="tab-full">
	<button class="collapsible"><?php echo esc_html__('How to configure your Google Spreadsheet with Ninja Form plugin?', 'gsheetconnector-ninja-forms'); ?></button>
	<div class="content">
		<ul>
			<li><?php echo esc_html__('Go to the Ninja Forms Menu.', 'gsheetconnector-ninja-forms'); ?></li>
<li><?php echo esc_html__('Edit/Add Ninja Form.', 'gsheetconnector-ninja-forms'); ?></li>
<li><?php echo esc_html__('Go to Emails & Actions tabs.', 'gsheetconnector-ninja-forms'); ?></li>
<li><?php echo esc_html__('Click on (Add new action) OR +(Plus Sign).', 'gsheetconnector-ninja-forms'); ?></li>


			
			<p>
	<img class="alignnone" src="<?php echo esc_url(NINJAFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot1.png'); ?>" style="width: 95%;" alt="FAQ Screenshot 1" />
</p>
<li><?php echo esc_html__('Click on Google Sheet action', 'gsheetconnector-ninja-forms'); ?></li>

<p>
	<img class="alignnone" src="<?php echo esc_url(NINJAFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot2.png'); ?>" style="width: 95%;" alt="FAQ Screenshot 2" />
</p>
<li><?php echo esc_html__('Fill the below details >> Click on Done button >> Click on Publish button', 'gsheetconnector-ninja-forms'); ?></li>

<p>
	<img class="alignnone" src="<?php echo esc_url(NINJAFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot3.png'); ?>" style="width: 95%;" alt="FAQ Screenshot 3" />
</p>
<li><?php echo esc_html__('Google Sheet Name, Google Sheet ID, Tab Name and Tab ID you can copy from spreadsheet like below', 'gsheetconnector-ninja-forms'); ?></li>

<p>
	<img class="alignnone" src="<?php echo esc_url(NINJAFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot4.png'); ?>" style="width: 95%;" alt="FAQ Screenshot 4" />
</p>

		</ul>
	</div>
</div>
<div class="tab-full">
	<button class="collapsible"><?php echo esc_html__('Why I am Getting Error 500 After Installing Plugin.', 'gsheetconnector-ninja-forms'); ?></button>
<div class="content">
    <p><?php echo esc_html__('Following are few of the points which will help to debug the issue.', 'gsheetconnector-ninja-forms'); ?></p>

    <p><?php echo esc_html__('1) Enable debug by adding the following in your wp-config.php file before /* That’s all, stop editing! Happy blogging.', 'gsheetconnector-ninja-forms'); ?></p>

    <p><?php echo esc_html__("define('WP_DEBUG', true); define('WP_DEBUG_LOG', true); define('SCRIPT_DEBUG', true); define('SAVEQUERIES', true);", 'gsheetconnector-ninja-forms'); ?></p>

    <p><?php echo esc_html__('And then try to activate the plugin again. This will create a debug.log under wp-content folder. Check for Ninja Forms Google Sheet Connector errors. If found, send us the file at support@westerndeal.com', 'gsheetconnector-ninja-forms'); ?></p>

    <p><?php echo esc_html__('2) Check the log that is created by Ninja Forms Google Sheet. For that, click “View” from the Google Sheet Integration page. Let us know if there is any error—we will assist you.', 'gsheetconnector-ninja-forms'); ?></p>

    <span class="cp-load-after-post"></span>
</div>

</div>

<div class="tab-full">
	<button class="collapsible">
		<?php echo esc_html__('How do I get the Google Access Code required in step 3 of Installation?', 'gsheetconnector-ninja-forms'); ?>
	</button>

	<div class="content">
		<ol>
			<li>
				<?php echo wp_kses(
					__('On the <code>Admin Panel &gt; Ninja Forms &gt; Google Sheets.</code>', 'gsheetconnector-ninja-forms'),
					['code' => []]
				); ?>
			</li>

			<li>
				<?php echo esc_html__('Go to “Integration” tab and click on "Get Code" button.', 'gsheetconnector-ninja-forms'); ?>
			</li>

			<li>
				<?php echo esc_html__('In a popup Google will ask you to authorize the plugin to connect to your Google Sheets. Authorize it – you may have to log in to your Google account if you aren’t already logged in.', 'gsheetconnector-ninja-forms'); ?>
			</li>

			<li>
				<?php echo esc_html__('On the next screen, you should receive the Access Code. Copy it.', 'gsheetconnector-ninja-forms'); ?>
			</li>

			<li>
				<?php echo esc_html__('Now you can paste this code back in the Google Access Code.', 'gsheetconnector-ninja-forms'); ?>
			</li>
		</ol>
	</div>
</div>


<div class="tab-full">
	<button class="collapsible">
		<?php echo esc_html__('Submitted Value is not showing in Googlesheet.', 'gsheetconnector-ninja-forms'); ?>
	</button>

	<div class="content">
		<div class="panel-body toggle-content post-content">
			<p>
				<?php echo esc_html__('Sometimes it can take a while of spinning before it goes through. But if the entries never show up in your Sheet then one of these things might be the reason:', 'gsheetconnector-ninja-forms'); ?>
			</p>

			<ol>
				<li><?php echo esc_html__('Wrong access code (Check debug log)', 'gsheetconnector-ninja-forms'); ?></li>
				<li><?php echo esc_html__('Wrong Sheet name or tab name', 'gsheetconnector-ninja-forms'); ?></li>
				<li><?php echo esc_html__('Wrong Column name (Column names in Ninja Forms. It cannot have underscore, space or any special characters.)', 'gsheetconnector-ninja-forms'); ?></li>
			</ol>

			<p>
				<?php echo esc_html__('Please double-check those items and hopefully getting them right will fix the issue.', 'gsheetconnector-ninja-forms'); ?>
			</p>

			<span class="cp-load-after-post"></span>
		</div>
	</div>
</div>


<div class="tab-full">
	<button class="collapsible">
		<?php echo esc_html__('Why am I not seeing Ninja Form values (submitted data) in my Google Sheet?', 'gsheetconnector-ninja-forms'); ?>
	</button>

	<div class="content">
		<p>
			<?php echo esc_html__('Sometimes it can take a while of spinning before it goes through. But if the entries never show up in your Sheet, then one of these things might be the reason:', 'gsheetconnector-ninja-forms'); ?>
		</p>

		<ul>
			<li><?php echo esc_html__('Wrong access code (Check debug log)', 'gsheetconnector-ninja-forms'); ?></li>
			<li><?php echo esc_html__('Wrong Sheet name or tab name', 'gsheetconnector-ninja-forms'); ?></li>
			<li><?php echo esc_html__('Wrong column name mapping (Column names in Ninja Forms cannot have underscores, spaces, or any special characters)', 'gsheetconnector-ninja-forms'); ?></li>
		</ul>

		<p>
			<?php echo esc_html__('Please double-check those items — getting them right will likely fix the issue.', 'gsheetconnector-ninja-forms'); ?>
		</p>
	</div>
</div>

</div>
</div>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>

