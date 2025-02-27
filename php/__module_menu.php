<?php
namespace SIM\COMMENTS;
use SIM;

const MODULE_VERSION		= '8.0.5';

DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));

//module slug is the same as grandparent folder name
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));

add_filter('sim_submenu_comments_options', __NAMESPACE__.'\subMenuOptions', 10, 2);
function subMenuOptions($optionsHtml, $settings){
	ob_start();
	
    ?>
	<label>Which post types should have comments allowed by default?</label><br>
	<?php
	foreach(get_post_types() as $type){
		?>
		<label>
			<input type='checkbox' name='posttypes[]' value='<?php echo $type;?>' <?php if(is_array($settings['posttypes']) && in_array($type, $settings['posttypes'])){echo 'checked';}?>>
			<?php echo $type;?>
		</label>
		<br>
		<?php
	}
	
	return $optionsHtml.ob_get_clean();
}

add_filter('sim_email_comments_settings', __NAMESPACE__.'\emailSettings', 10, 2);
function emailSettings($html, $settings){
	ob_start();
	
    ?>
	<h4>Define the e-mail people get when someone left a comment to a page they created.</h4>
	<?php
	$email    = new ApprovedCommentEmail([]);
	$email->printPlaceholders();
	$email->printInputs($settings);
	?>
	<br>
	<br>
	<h4>Define the e-mail content managers get when a comment needs approval</h4>
	<?php
	$email    = new CommentWarningEmail([]);
	$email->printPlaceholders();
	$email->printInputs($settings);
	?>
	<br>
	<br>
	<h4>Define the e-mail people get when someone replies to their comment</h4>
	<?php
	$email    = new CommentReplyEmail([]);
	$email->printPlaceholders();
	$email->printInputs($settings);

	return $html.ob_get_clean();
}