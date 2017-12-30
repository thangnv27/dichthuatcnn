<?php
// create custom plugin settings menu
add_action('admin_menu', 'expertrans_create_menu');

if(!defined('WORDS_PO')) {
	define('WORDS_PO', dirname(__FILE__) . '/../languages/vi_VN.po');
}

function expertrans_create_menu() {

	//create new top-level menu
	add_theme_page('Theme Options', 'Theme Options', 'administrator', 'expertrans-theme-options', 'expertrans_settings_page');
	add_theme_page('Words Translation', 'Words Translation', 'administrator', 'expertrans-words-translation', 'expertrans_words_translation_page');

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
	add_action( 'admin_init', 'register_words');
}


function register_mysettings() {
	//register our settings
	register_setting( 'expertrans-settings', 'hotline_label' );
	register_setting( 'expertrans-settings', 'hotline' );

	register_setting( 'expertrans-settings', 'copyright' );

	register_setting( 'expertrans-settings', 'free_quote_translation_page' );
	register_setting( 'expertrans-settings', 'free_quote_interpretation_page' );
	register_setting( 'expertrans-settings', 'free_quote_voice_overs_page' );
	register_setting( 'expertrans-settings', 'free_quote_others_page' );

	register_setting( 'expertrans-settings', 'free_quote_translation_email' );
	register_setting( 'expertrans-settings', 'free_quote_interpretation_email' );
	register_setting( 'expertrans-settings', 'free_quote_voice_overs_email' );
	register_setting( 'expertrans-settings', 'free_quote_others_email' );
	register_setting( 'expertrans-settings', 'contact_message_email' );
	register_setting( 'expertrans-settings', 'linguist_application_email' );

	register_setting( 'expertrans-settings', 'contact_skype_1_label' );
	register_setting( 'expertrans-settings', 'contact_yahoo_1' );
	register_setting( 'expertrans-settings', 'contact_skype_1' );

	register_setting( 'expertrans-settings', 'contact_skype_2_label' );
	register_setting( 'expertrans-settings', 'contact_yahoo_2' );
	register_setting( 'expertrans-settings', 'contact_skype_2' );

	register_setting( 'expertrans-settings', 'contact_online_support_text' );
	register_setting( 'expertrans-settings', 'used_languages' );
	register_setting( 'expertrans-settings', 'footer-scripts' );
}

function register_words()
{
	$fh = fopen(WORDS_PO, 'r');
	$checked = array();
	while (($buffer = fgets($fh, 4096)) !== false) {
		if(strpos($buffer, 'msgid') === 0) {
			$msg = str_replace('msgid', '', $buffer);
			$msg = trim($msg);
			$msg = trim($msg, '"');
			if(empty($msg)) {
				continue;
			}

			$key = __myKey($msg);

			if(isset($checked[$key])) {
				continue;
			}

			$checked[$key] = true;

			register_setting( 'expertrans-settings', $key );
		}
	}
	fclose($fh);
}

function expertrans_settings_page() {
	if(!empty($_POST)) {
		foreach($_POST as $key => $val) {
			$val = update_option($key, $val);
		}
	}
?>
<div class="wrap">
<h2>Theme Options</h2>

<form method="post" action="themes.php?page=expertrans-theme-options">
	<?php settings_fields( 'expertrans-settings' ); ?>
	<style type="text/css">
		.cust-form-table input{width: 400px}
	</style>
	<table class="form-table cust-form-table">

		<tr valign="top">
		<th scope="row"><strong>Email</strong></th>
		<td><i>(Không điền thì sẽ lấy Email của Admin)</i></td>
		</tr>

		<?php # emails ?>
		<tr valign="top">
		<th scope="row">Free Quote - Translation Email</th>
		<td><input type="text" name="free_quote_translation_email" value="<?php echo get_option('free_quote_translation_email'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">Free Quote - Interpretation Email</th>
		<td><input type="text" name="free_quote_interpretation_email" value="<?php echo get_option('free_quote_interpretation_email'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">Free Quote - Voice-overs Email</th>
		<td><input type="text" name="free_quote_voice_overs_email" value="<?php echo get_option('free_quote_voice_overs_email'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">Free Quote - Others Email</th>
		<td><input type="text" name="free_quote_others_email" value="<?php echo get_option('free_quote_others_email'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">Contact Message Email</th>
		<td><input type="text" name="contact_message_email" value="<?php echo get_option('contact_message_email'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">Linguist Application Email</th>
		<td><input type="text" name="linguist_application_email" value="<?php echo get_option('linguist_application_email'); ?>" /></td>
		</tr>

		<?php #END emails */ ?>

	</table>

	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Lưu thay đổi') ?>" />
	</p>

</form>
</div>
<?php }

function __myKey($msg) {
	$msg2 = preg_replace("/[^a-z0-9\s-]/", '', strtolower($msg));
	$key = 'word_' . str_replace(array(' ', '-'), '_', $msg2);
	if(strlen($key) > 64) {
		$key = md5($key);
	}
	return $key;
}

function __my($msg, $return = false) {
	$msg = trim($msg);
	$msg = trim($msg, '"');

	$key = __myKey($msg);

	$trans = get_option($key);
	return empty($trans) ? $msg : $trans;
}

function expertrans_words_translation_page() {
	if(!empty($_POST)) {
		foreach($_POST as $key => $val) {
			$val = update_option($key, $val);
		}
	}
	?>
	<div class="wrap">
	<h2>Words Translation</h2>

	<form method="post" action="themes.php?page=expertrans-words-translation">
		<style type="text/css">
			.cust-form-table input{width: 400px}
		</style>
		<?php settings_fields( 'expertrans-settings' );?>
		<table class="form-table cust-form-table">

	<?php
	$fh = fopen(WORDS_PO, 'r');
	$checked = array();
	while (($buffer = fgets($fh, 4096)) !== false) {
		if(strpos($buffer, 'msgid') === 0) {
			$msg = str_replace('msgid', '', $buffer);
			$msg = trim($msg);
			$msg = trim($msg, '"');
			if(empty($msg)) {
				continue;
			}

			$key = __myKey($msg);

			if(isset($checked[$key])) {
				continue;
			}

			$checked[$key] = true;

			?>
				<tr valign="top">
					<th scope="row"><?php echo $msg?></th>
					<td><input type="text" name="<?php echo $key?>" value="<?php echo get_option($key); ?>" /></td>
				</tr>
			<?php
		}
	}
	fclose($fh);

	?>
		</table>

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Lưu thay đổi') ?>" />
		</p>

	</form>
	</div>
	<?php
}
?>