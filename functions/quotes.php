<?php

# custom post
register_post_type( 'quote-request',
	array(
		'labels' => array(
			'name' => __( 'Quote Request' ),
			'singular_name' => __( 'Quote Requests' )
		),
		'public' => true,
		'publicly_queryable' => false,
		'hierarchical' => false,
		'has_archive' => false,
		'capability_type' => 'post',
		'supports' => array('title', 'editor'),
		'exclude_from_search' => true,
		'revisions' => false,
		'menu_position' => 5,
		'rewrite' => array('slug' => 'quote-requestias'),
	)
);

register_post_type( 'linguist-application',
	array(
		'labels' => array(
			'name' => __( 'Linguist Application' ),
			'singular_name' => __( 'Linguist Applications' )
		),
		'public' => true,
		'publicly_queryable' => false,
		'hierarchical' => false,
		'has_archive' => false,
		'capability_type' => 'post',
		'supports' => array('title', 'editor'),
		'exclude_from_search' => true,
		'revisions' => false,
		'menu_position' => 5,
		'rewrite' => array('slug' => 'linguist-applicationias'),
	)
);

register_post_status( 'processed', array(
	'label' => _x( 'Processed', 'post' ),
	'public' => true,
	'internal' => false,
	'hierarchical'              => false,
	'_edit_link'                => 'post.php?post=%d',
	'exclude_from_search' => false,
	'show_in_admin_all_list' => true,
	'show_in_admin_status_list' => true,
	'label_count'               => _n_noop( 'Processed <span class="count">(%s)</span>', 'Processed <span class="count">(%s)</span>' ),
) );

add_action('admin_footer', 'extend_submitdiv_post_status');
function extend_submitdiv_post_status()
{
	// Abort if we're on the wrong post type, but only if we got a restriction
	$post_types = array('contact', 'quote-request', 'linguist-application');
	global $post_type;
	if ( is_array( $post_types ) )
	{
		if ( !in_array( $post_type, $post_types ) )
			return;
	}


	// Our post status and post type objects
	global $wp_post_statuses, $post;

	// Get all non-builtin post status and add them as <option>
	$options = $display = '';
	foreach ( $wp_post_statuses as $status )
	{
		if ( ! $status->_builtin )
		{
			// Match against the current posts status
			$selected = selected( $post->post_status, $status->name, false );

			// If we one of our custom post status is selected, remember it
			$selected AND $display = $status->label;

			// Build the options
			$options .= "<option{$selected} value='{$status->name}'>{$status->label}</option>";
		}
	}
	?>
	<script type="text/javascript">
		jQuery( document ).ready( function($)
		{
			<?php
			// Add the selected post status label to the "Status: [Name] (Edit)"
			if ( ! empty( $display ) ) :
			?>
				$( '#post-status-display' ).html( '<?php echo $display; ?>' );
			<?php
			endif;

			// Add the options to the <select> element
			?>
			$( '.edit-post-status' ).on( 'click', function()
			{
				var select = $( '#post-status-select' ).find( 'select' );
				$( select ).append( "<?php echo $options; ?>" );
			} );
			$('#inlineedit select[name="_status"]').append("<?php echo $options?>");
		} );
	</script>
	<?php
}

wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-theme', get_bloginfo('template_url') . '/css/jquery.ui/smoothness/jquery-ui-1.8.23.custom.css');
/* end custom post */

function e_post_to_string()
{
	$ignores = array(
		'MAX_FILE_SIZE', 'fm_form_submit', 'e'
	);
	$content = '';
	foreach($_POST as $key => $value) {
		if(in_array($key, $ignores)) {
			continue;
		}
		$key = str_replace('_', ' ', $key);
		$key = ucwords($key);
		if(is_array($value)) {
			$value = implode(',', $value);
		}
		$content .= '<b>' . $key . '</b>' . ': ' . nl2br($value) . "\n";
	}
	return $content;
}

function e_upload_attachments()
{
	$attachments = array();
	if(!isset($_FILES["file"]["error"][0]) && isset($_FILES['file']['error'])) {
		if($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
			if(!empty($_FILES['file']['name'])) {
				$file = WP_CONTENT_DIR .'/uploads/'.uniqid(time()) . '-' . basename($_FILES['file']['name']);
				move_uploaded_file($_FILES["file"]["tmp_name"], $file);
				$attachments[] = $file;
			}
		}
	} else {
		foreach($_FILES['file']['error'] as $i => $error) {
			if($error == UPLOAD_ERR_OK) {
				$name = $_FILES['file']['name'][$i];
				if(!empty($name)) {
					$tmp 	= $_FILES['file']['tmp_name'][$i];
					$file = WP_CONTENT_DIR .'/uploads/'.uniqid(time()) . '-' . basename($name);
					move_uploaded_file($tmp, $file);
					$attachments[] = $file;
				}
			}
		}
	}
	return $attachments;
}

function e_post_email_to_admin()
{
	$subject = __my('Website Email', 'expertrans') . ': ' . $_POST['quote_type'] . ' - ' . @$_POST['requester_name'] . ' <' . @$_POST['email'] . '>';
	$body = nl2br(e_post_to_string());
	$attachments = e_upload_attachments();
	#$post_type = @$_POST['post_type'] == 'contact' ? 'contact' : 'quote-request';
	switch(@$_POST['post_type']) {
		case 'contact':
			$post_type = 'contact';
			break;
		case 'linguist-application':
			$post_type = 'linguist-application';
			break;
		default:
			$post_type = 'quote-request';
	}
	if(!empty($attachments)) {
		foreach($attachments as $att) {
			if(empty($att)) {
				continue;
			}
			$url = get_bloginfo('siteurl') . '/wp-content' . str_replace(WP_CONTENT_DIR, '', $att);
			$body .= '<br/><br/>Attachment: ';
			$body .= '<a href="' . $url . '">' . $url . '</a>';
		}
	}

	$result = wp_insert_post($data = array(
		'post_title' => (@$_POST['quote_type'] ? $_POST['quote_type'] : 'Quote request') . ' from ' . @$_POST['requester_name'] . ' <' . @$_POST['email'] . '>',
		'post_name' => uniqid(time()),
		'post_content' => $body,
		'post_type' => $post_type,
		'post_status' => 'pending',
	), false);

	$email = @get_option(@$_POST['e']);
	if(empty($email)) {
		$email = get_option('admin_email');
	}
	return wp_mail($email , $subject, $body , array('content-type: text/html'), $attachments);
}

function e_age_range_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Child', 'expertrans'),
		__my('Teen', 'expertrans'),
		__my('Young Adult', 'expertrans'),
		__my('Middle Aged', 'expertrans'),
		__my('Senior', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_type_of_interpretation_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Conference', 'expertrans'),
		__my('Deposition', 'expertrans'),
		__my('Document Review', 'expertrans'),
		__my('Hearing', 'expertrans'),
		__my('Meeting', 'expertrans'),
		__my('Telephone', 'expertrans'),
		__my('Trial', 'expertrans'),
		__my('Training', 'expertrans'),
		__my('Cabin / Parallel', 'expertrans'),
		__my('Market Survey', 'expertrans'),
		__my('In Factory', 'expertrans'),
	);
	usort($langs, 'e_sortcb');
	$langs[] = __my('Other', 'expertrans');
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_time_options(){
	$html = '';
	for($i = 0 ; $i < 24; $i++) {
		$hour = $i < 10 ? '0' . $i : $i;
		$value = $hour . ':' . '00';
		$html .= '<option value="' . $value . '" >' . $value . '</option>';
		$value = $hour . ':' . '30';
		$html .= '<option value="' . $value . '" >' . $value . '</option>';
	}
	return $html;
}

function e_how_do_you_know_us_options($default = null){
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Returning Customers', 'expertrans'),
		__my('Website', 'expertrans'),
		__my('Google', 'expertrans'),
		__my('Email', 'expertrans'),
		__my('Friends', 'expertrans'),
		__my('Newspaper/poster', 'expertrans'),
		__my('1080 Podcast', 'expertrans'),
		__my('Other', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_gender_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Both', 'expertrans'),
		__my('Male', 'expertrans'),
		__my('Female', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_category_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Audiobooks', 'expertrans'),
		__my('Business', 'expertrans'),
		__my('Cartoons', 'expertrans'),
		__my('Documentaries', 'expertrans'),
		__my('Educational', 'expertrans'),
		__my('Internet', 'expertrans'),
		__my('Jingles', 'expertrans'),
		__my('Movie Trailers', 'expertrans'),
		__my('Music', 'expertrans'),
		__my('Podcasting', 'expertrans'),
		__my('Radio', 'expertrans'),
		__my('Telephone', 'expertrans'),
		__my('Television', 'expertrans'),
		__my('Videogames', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_budget_range_options ($default = null){
	if(!is_array($default)) $default = array($default);
	$langs = explode(',', '$100 - $250,$250 - $500,$500 - $750,$750 - $1000,$1000 - $2500,$2500 - $5000,$5000 - $7500,$7500 - $10000,$10000+');
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}
function e_lang_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = explode(',', 'English,Albanian,Arabic,Bengali,Bulgarian,Burmese,Chinese (simp.),Chinese (trad.),Croatian,Czech,Danish,Dari,Dutch,Estonian,Farsi,Finnish,French,French Can.,German,Swiss German,Greek,Gujarati,Hebrew,Hindi,Hmong,Hungarian,Icelandic,Indonesian,Italian,Japanese,Kannada,Khmer,Korean,Kurdish,Laotion,Latvian,Lithuanian,Malay,Malayalam,Marathi,Ndebele,Nepalese,Norwegian,Oriya,Pedi,Polish,Portuguese,Portuguese (Braz.),Punjabi,Pushto,Romanian,Russian,Serbian,Shangane,Shona,Singhalese,Slovak,Slovenian,Somali,Sotho,Spanish,Spanish (Arg.),Spanish (Mex.),Spanish (Ur.),Swedish,Tamil,Telugu,Thai,Tswang,Turkish,Twi,Ukrainian,Urdu,Venda,Vietnamese,Xhosa,Yiddish,Yoruba,Others');
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_translation_package_options($default = null){
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('Translate', 'expertrans'),
		__my('Translate and Edit', 'expertrans'),
		__my('Translate + Edit + Proofread', 'expertrans'),
		__my('Translate + Edit + Proofread by Native Speaker', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<label class="checkbox"><input name="translation_package[]" type="checkbox" value="' . $lang . '" ' . (in_array($lang, $default) ? 'checked="checked"' : '') . '>' . $lang . '</label>';
	}
	return $html;
}

function e_sortcb($a, $b) {
	$map = array(
		'Ă' => 'Az',
		'Ằ' => 'Azz',
		'Ắ' => 'Azzz',
		'Ẳ' => 'Azzzz',
		'Ẵ' => 'Azzzzz',
		'Ặ' => 'Azzzzzz',
		'Â' => 'Azzzzzzz',
		'Ầ' => 'Azzzzzzz',
		'Ấ' => 'Azzzzzzzz',
		'Ẩ' => 'Azzzzzzzzz',
		'Ẫ' => 'Azzzzzzzzzz',
		'Ậ' => 'Azzzzzzzzzzz',
		'ă' => 'az',
		'ằ' => 'azz',
		'ắ' => 'azzz',
		'ẳ' => 'azzzz',
		'ẵ' => 'azzzzz',
		'ặ' => 'azzzzzz',
		'â' => 'azzzzzzz',
		'ầ' => 'azzzzzzzz',
		'ấ' => 'azzzzzzzzz',
		'ẩ' => 'azzzzzzzzzz',
		'ẫ' => 'azzzzzzzzzzz',
		'ậ' => 'azzzzzzzzzzzz',
		'Đ' => 'Dz',
		'đ' => 'dz',
		'Ê' => 'Ez',
		'Ề' => 'Ezz',
		'Ế' => 'Ezzz',
		'Ể' => 'Ezzzz',
		'Ễ' => 'Ezzzzz',
		'Ệ' => 'Ezzzzzz',
		'ê' => 'ezzzzzzz',
		'ề' => 'ezzzzzzzz',
		'ê' => 'ezzzzzzzzz',
		'ể' => 'ezzzzzzzzzz',
		'ễ' => 'ezzzzzzzzzzz',
		'ệ' => 'ezzzzzzzzzzzz',
		'Ô' => 'Oz',
		'Ồ' => 'Ozz',
		'Ố' => 'Ozzz',
		'Ổ' => 'Ozzzz',
		'Ỗ' => 'Ozzzzz',
		'Ộ' => 'Ozzzzzz',
		'Ơ' => 'Ozzzzzzz',
		'Ờ' => 'Ozzzzzzzz',
		'Ớ' => 'Ozzzzzzzzz',
		'Ở' => 'Ozzzzzzzzz',
		'Ỡ' => 'Ozzzzzzzzzz',
		'Ợ' => 'Ozzzzzzzzzzz',
		'ô' => 'oz',
		'ồ' => 'ozz',
		'ố' => 'ozzz',
		'ổ' => 'ozzzz',
		'ỗ' => 'ozzzzz',
		'ộ' => 'ozzzzzz',
		'ơ' => 'ozzzzzzz',
		'ờ' => 'ozzzzzzzz',
		'ớ' => 'ozzzzzzzzz',
		'ở' => 'ozzzzzzzzzz',
		'ỡ' => 'ozzzzzzzzzzz',
		'ợ' => 'ozzzzzzzzzzzz',
		'Ư' => 'Uz',
		'Ừ' => 'Uzz',
		'Ứ' => 'Uzzz',
		'Ử' => 'Uzzzz',
		'Ữ' => 'Uzzzzz',
		'Ự' => 'Uzzzzzz',
		'ư' => 'uz',
		'ừ' => 'uzz',
		'ứ' => 'uzzz',
		'ử' => 'uzzzz',
		'ữ' => 'uzzzzz',
		'ự' => 'uzzzzzz',
	);
	$keys = array_keys($map);
	$vals = array_values($map);
	$a = str_replace($keys, $vals, $a);
	$b = str_replace($keys, $vals, $b);
	if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
	
function e_expertise_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$lang1st = array(
		__my('Art/Literary','expertrans'),
		__my('Bus/Financial','expertrans'),
		__my('Law/Patents','expertrans'),
		__my('Marketing','expertrans'),
		__my('Medical','expertrans'),
		__my('Science','expertrans'),
		__my('Social Sciences','expertrans'),
		__my('Tech/Engineering','expertrans')
	);
	usort($lang1st, 'e_sortcb');
	$lang2nd =array(__my('Other','expertrans'),'------------------');
	$lang3rd = array(
		__my('Accounting','expertrans'),
		__my('Advertising / Public Relations','expertrans'),
		__my('Aerospace / Aviation / Space','expertrans'),
		__my('Agriculture','expertrans'),
		__my('Livestock / Animal Husbandry','expertrans'),
		__my('Anthropology','expertrans'),
		__my('Archaeology','expertrans'),
		__my('Architecture','expertrans'),
		__my('Art, Arts & Crafts, Painting','expertrans'),
		__my('Astronomy & Space','expertrans'),
		__my('Finance (general)','expertrans'),
		__my('Automation & Robotics','expertrans'),
		__my('Automotive / Cars & Trucks','expertrans'),
		__my('Biology (-tech,-chem,micro-)','expertrans'),
		__my('Botany','expertrans'),
		__my('Construction / Civil Engineering','expertrans'),
		__my('Business/Commerce (general)','expertrans'),
		__my('Materials (Plastics, Ceramics, etc.)','expertrans'),
		__my('Certificates, Diplomas, Licenses, CVs','expertrans'),
		__my('Chemistry','expertrans'),
		__my('Chem Sci/Eng','expertrans'),
		__my('Poetry & Literature','expertrans'),
		__my('Cinema, Film, TV, Drama','expertrans'),
		__my('Textiles / Clothing / Fashion','expertrans'),
		__my('Telecom(munications)','expertrans'),
		__my('Computers (general)','expertrans'),
		__my('Computers: Hardware','expertrans'),
		__my('Computers: Software','expertrans'),
		__my('Computers: Systems, Networks','expertrans'),
		__my('Law: Contract(s)','expertrans'),
		__my('Cooking / Culinary','expertrans'),
		__my('Cosmetics, Beauty','expertrans'),
		__my('Medical: Dentistry','expertrans'),
		__my('Media / Multimedia','expertrans'),
		__my('Economics','expertrans'),
		__my('Education / Pedagogy','expertrans'),
		__my('Electronics / Elect Eng','expertrans'),
		__my('Energy / Power Generation','expertrans'),
		__my('Engineering (general)','expertrans'),
		__my('Engineering: Industrial','expertrans'),
		__my('Mechanics / Mech Engineering','expertrans'),
		__my('Nuclear Eng/Sci','expertrans'),
		__my('Environment & Ecology','expertrans'),
		__my('Esoteric practices','expertrans'),
		__my('Fisheries','expertrans'),
		__my('Folklore','expertrans'),
		__my('Food & Dairy','expertrans'),
		__my('Forestry / Wood / Timber','expertrans'),
		__my('Furniture / Household Appliances','expertrans'),
		__my('Games / Video Games / Gaming / Casino','expertrans'),
		__my('Mining & Minerals / Gems','expertrans'),
		__my('Genealogy','expertrans'),
		__my('General / Conversation / Greetings / Letters','expertrans'),
		__my('Genetics','expertrans'),
		__my('Geography','expertrans'),
		__my('Geology','expertrans'),
		__my('Government / Politics','expertrans'),
		__my('Photography/Imaging (& Graphic Arts)','expertrans'),
		__my('Medical: Health Care','expertrans'),
		__my('History','expertrans'),
		__my('Tourism & Travel','expertrans'),
		__my('Human Resources','expertrans'),
		__my('Idioms / Maxims / Sayings','expertrans'),
		__my('Insurance','expertrans'),
		__my('International Org/Dev/Coop','expertrans'), __my('Internet, e-Commerce','expertrans'),
		__my('Investment / Securities','expertrans'),
		__my('Metallurgy / Casting','expertrans'), __my('IT (Information Technology)','expertrans'), __my('Journalism','expertrans'),
		__my('Real Estate','expertrans'),
		__my('Law (general)','expertrans'),
		__my('Law: Taxation & Customs','expertrans'),
		__my('Linguistics','expertrans'),
		__my('Transport / Transportation / Shipping','expertrans'),
		__my('Management','expertrans'),
		__my('Manufacturing','expertrans'),
		__my('Ships, Sailing, Maritime','expertrans'),
		__my('Marketing / Market Research','expertrans'),
		__my('Mathematics & Statistics','expertrans'),
		__my('Medical (general)','expertrans'),
		__my('Medical: Cardiology','expertrans'),
		__my('Medical: Instruments','expertrans'),
		__my('Medical: Pharmaceuticals','expertrans'),
		__my('Meteorology','expertrans'),
		__my('Metrology','expertrans'),
		__my('Military / Defense','expertrans'),
		__my('Music','expertrans'),
		__my('Names (personal, company)','expertrans'),
		__my('Nutrition','expertrans'), __my('Petroleum Eng/Sci','expertrans'),
		__my('Paper / Paper Manufacturing','expertrans'), __my('Patents','expertrans'), __my('Law: Patents, Trademarks, Copyright','expertrans'),
		__my('Philosophy','expertrans'),
		__my('Physics','expertrans'),
		__my('Printing & Publishing','expertrans'),
		__my('Psychology','expertrans'),
		__my('Religion','expertrans'),
		__my('Retail','expertrans'),
		__my('Safety','expertrans'),
		__my('SAP','expertrans'),
		__my('Science (general)','expertrans'),
		__my('Slang','expertrans'),
		__my('Social Science, Sociology, Ethics, etc.','expertrans'),
		__my('Sports / Fitness / Recreation','expertrans'),
		__my('Surveying','expertrans'),
		__my('Wine / Oenology / Viticulture','expertrans'),
		__my('Zoology','expertrans'),);
	usort($lang3rd, 'e_sortcb');
	$langs = array_unique(array_merge($lang1st, $lang2nd, $lang3rd));
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_translation_format_options($default = null){
	if(!is_array($default)) $default = array($default);
	$langs = explode(',', 'Word,Excel,PDF,Powerpoint,Corel,Phototshop,AutoCAD,Other');
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_country_options($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = explode(',', 'Afghanistan,Albania,Algeria,American Samoa,Andorra,Angola,Anguilla,Antarctica,Antigua and Barbuda,Argentina,Armenia,Aruba,Australia,Austria,Azerbaijan,Bahamas,Bahrain,Bangladesh,Barbados,Belarus,Belgium,Belize,Benin,Bermuda,Bhutan,Bolivia,Bosnia and Herzegovina,Botswana,Bouvet Island,Brazil,British Indian Ocean Territory,Brunei,Bulgaria,Burkina Faso,Burundi,Cambodia,Cameroon,Canada,Cape Verde,Cayman Islands,Central African Republic,Chad,Chile,China,Christmas Island,Cocos (Keeling) Islands,Colombia,Comoros,Congo,Congo, The Democratic Republic of the,Cook Islands,Costa Rica,Cote DâIvoire,Croatia,Cuba,Cyprus,Czech Republic,Denmark,Djibouti,Dominica,Dominican Republic,Ecuador,Egypt,El Salvador,Equatorial Guinea,Eritrea,Estonia,Ethiopia,Falkland Islands (Malvinas),Faroe Islands,Fiji,Finland,France,French Guiana,French Polynesia,French Southern Territories,Gabon,Gambia,Georgia,Germany,Ghana,Gibraltar,Greece,Greenland,Grenada,Guadeloupe,Guam,Guatemala,Guinea,Guinea-Bissau,Guyana,Haiti,Heard Island and McDonald Islands,Honduras,Hong Kong S.A.R., China,Hungary,Iceland,India,Indonesia,Iran,Iraq,Ireland,Israel,Italy,Jamaica,Japan,Jordan,Kazakhstan,Kenya,Kiribati,Korea, North,Korea, South,Kuwait,Kyrgyzstan,Laos,Latvia,Lebanon,Lesotho,Liberia,Libya,Liechtenstein,Lithuania,Luxembourg,Macao,Macedonia,Madagascar,Malawi,Malaysia,Maldives,Mali,Malta,Marshall Islands,Martinique,Mauritania,Mauritius,Mayotte,Mexico,Micronesia, Federated States Of,Moldova,Monaco,Mongolia,Montenegro,Montserrat,Morocco,Mozambique,Myanmar,Namibia,Nauru,Nepal,Netherlands,Netherlands Antilles,New Caledonia,New Zealand,Nicaragua,Niger,Nigeria,Niue,Norfolk Island,Northern Mariana Islands,Norway,Oman,Pakistan,Palau,Panama,Papua New Guinea,Paraguay,Peru,Philippines,Pitcairn,Poland,Portugal,Puerto Rico,Qatar,Reunion,Romania,Russia,Rwanda,Saint Helena,Saint Kitts and Nevis,Saint Lucia,Saint Pierre and Miquelon,Saint Vincent and the Grenadines,Samoa,San Marino,Sao Tome and Principe,Saudi Arabia,Senegal,Serbia,Seychelles,Sierra Leone,Singapore,Slovakia,Slovenia,Solomon Islands,Somalia,South Africa,Spain,Sri Lanka,Sudan,Suriname,Svalbard and Jan Mayen,Swaziland,Sweden,Switzerland,Syria,Taiwan,Tajikistan,Tanzania,Thailand,Timor-Leste,Togo,Tokelau,Tonga,Trinidad and Tobago,Tunisia,Turkey,Turkmenistan,Turks and Caicos Islands,Tuvalu,Uganda,Ukraine,United Arab Emirates,United Kingdom,United States,United States Minor Outlying Islands,Uruguay,Uzbekistan,Vanuatu,Vatican City State (Holy See),Venezuela,Vietnam,Virgin Islands, British,Virgin Islands, US,Wallis and Futuna,Western Sahara,Yemen,Zambia,Zimbabwe');
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_currencies($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = explode(',', 'USD,EUR,VNĐ,GBP,JPY');
	$html = '';
	foreach($langs as $lang) {
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_payment_methods($default = null)
{
	if(!is_array($default)) $default = array($default);
	$langs = array(
		__my('E-banking', 'expertrans'),
		__my('Cash', 'expertrans'),
		__my('Paypal', 'expertrans'),
	);
	$html = '';
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<option value="' . $lang . '" ' . (in_array($lang, $default) ? 'selected="selected"' : '') . '>' . $lang . '</option>';
	}
	return $html;
}

function e_checkbox_fields($default = null)
{
	if(!is_array($default)) $default = array($default);
	$html = '';
	$langs = array(
		__my('Advertising/Marketing', 'expertrans'),
		__my('Consumer Products - Apparel', 'expertrans'),
		__my('Education', 'expertrans'),
		__my('Educational Media', 'expertrans'),
		__my('Hospitality/Restaurants', 'expertrans'),
		__my('Human Resources', 'expertrans'),
		__my('In language Copywriting', 'expertrans'),
		__my('Cultural Consultation', 'expertrans'),
		__my('Legal - Contracts', 'expertrans'),
		__my('Legal - Patents', 'expertrans'),
		__my('Patent/Intellectual Property', 'expertrans'),
		__my('Medical/Life Sciences', 'expertrans'),
		__my('Medical Devices', 'expertrans'),
		__my('Technical', 'expertrans'),
		__my('Financial - Accounting', 'expertrans'),
		__my('Financial - Investments', 'expertrans'),
		__my('Financial - Insurance', 'expertrans'),
		__my('Financial - Corporate Communications', 'expertrans'),
	);
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<label class="checkbox"><input name="fields[]" type="checkbox" value="' . $lang . '" ' . (in_array($lang, $default) ? 'checked="checked"' : '') . '>' . $lang . '</label>';
	}
	return $html;
}

function e_checkbox_services_provided($default = null)
{
	if(!is_array($default)) $default = array($default);
	$html = '';
	$langs = array(
		__my('Translation', 'expertrans'),
		__my('Proofreading/Editing', 'expertrans'),
		__my('Dubbing, Narrator, Subtitling', 'expertrans'),
		__my('Interpreting', 'expertrans'),
		__my('Transcriptions', 'expertrans'),
		__my('Others', 'expertrans'),
	);
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<label class="checkbox"><input name="services_provided[]" type="checkbox" class="'.$lang.'" value="' . $lang . '" ' . (in_array($lang, $default) ? 'checked="checked"' : '') . '>' . $lang . '</label>';
	}
	return $html;
}

function e_checkbox_interpreting()
{
	if(!is_array($default)) $default = array($default);
	$html = '';
	$langs = array(
		__my('Conference', 'expertrans'),
		__my('Deposition', 'expertrans'),
		__my('Document Review', 'expertrans'),
		__my('Hearing', 'expertrans'),
		__my('Meeting', 'expertrans'),
		__my('Telephone', 'expertrans'),
		__my('Trial', 'expertrans'),
		__my('Other', 'expertrans'),
	);
	foreach($langs as $lang) {
		$lang = __my($lang, 'expertrans');
		$html .= '<label class="checkbox"><input name="services_provided_interpreting[]" disabled="disabled" type="checkbox" value="' . $lang . '" ' . (in_array($lang, $default) ? 'checked="checked"' : '') . '>' . $lang . '</label>';
	}
	return $html;
}


function e_personal_info($class = 'no1 no-note')
{
	?>
	<li id="fm-item-note-personal-info" class="input note <?php echo $class?>">
		<label ><?php echo __my('Personal Info', 'expertrans')?></label>
	</li>
	<li id="fm-item-name" class="input text">
		<label ><?php echo __my('Name', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="requester_name" requiredz="<?php echo __my('Name is required', 'expertrans')?>">
	</li>
	<li id="fm-item-birthday" class="input text">
		<label ><?php echo __my('Birthday', 'expertrans')?></label>
		<input type="text" name="birthday"    class="datepicker">
	</li>
	<li id="fm-item-address" class="input text cleft">
		<label ><?php echo __my('Address', 'expertrans')?></label>
		<input type="text" name="address"   >
	</li>
	<li id="fm-item-phone" class="input text">
		<label ><?php echo __my('Phone', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="phone"  requiredz="<?php echo __my('Phone is required', 'expertrans')?>">
	</li>
	<li id="fm-item-email" class="input text cleft">
		<label ><?php echo __my('Email', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="email" requiredz="<?php echo __my('Email is required', 'expertrans')?>">
	</li>
	<li id="fm-item-zip_code" class="input text">
		<label ><?php echo __my('Zip code', 'expertrans')?></label>
		<input type="text" name="zip_code" >
	</li>
	<li id="fm-item-city" class="input text cleft">
		<label ><?php echo __my('City', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="city"  requiredz="<?php echo __my('City is required', 'expertrans')?>">
	</li>
	<li id="fm-item-country" class="input custom_list select">
		<label ><?php echo __my('Country', 'expertrans')?>&nbsp;<em>*</em></label>
		<select name="country" requiredz="<?php echo __my('Country is required', 'expertrans')?>">
		<option value=""><?php __my('Please Select', 'expertrans')?></option>
			<?php echo e_country_options()?>
		</select>
	</li>
	<?php
}

function e_customer_info($class = 'no1 no-note')
{
	?>
	<li id="fm-item-note-personal-info" class="input note <?php echo $class?>">
		<label ><?php echo __my('Customer Info', 'expertrans')?></label>
	</li>
	
	<li id="fm-item-name" class="input text cleft">
		<label ><?php echo __my('Name', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="customer_name" requiredz="<?php echo __my('Name is required', 'expertrans')?>">
	</li>
	<li id="fm-item-birthday" class="input text">
		<label ><?php echo __my('Birthday', 'expertrans')?></label>
		<input type="text" name="birthday"    class="datepicker">
	</li>
	
	<li id="fm-item-address" class="input text cleft">
		<label ><?php echo __my('Address', 'expertrans')?></label>
		<input type="text" name="address"   >
	</li>
	<li id="fm-item-phone" class="input text">
		<label ><?php echo __my('Phone', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="phone"  requiredz="<?php echo __my('Phone is required', 'expertrans')?>">
	</li>
	
	<li id="fm-item-email" class="input text cleft">
		<label ><?php echo __my('Email', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="email" requiredz="<?php echo __my('Email is required', 'expertrans')?>">
	</li>
	<li id="fm-item-city" class="input text">
		<label ><?php echo __my('City', 'expertrans')?>&nbsp;<em>*</em></label>
		<input type="text" name="city"  requiredz="<?php echo __my('City is required', 'expertrans')?>">
	</li>
	
	<li id="fm-item-zip_code" class="input text cleft">
		<label ><?php echo __my('Zip/Postal', 'expertrans')?></label>
		<input type="text" name="zip_or_postal" >
	</li>
	
	<li id="fm-item-country" class="input custom_list select">
		<label ><?php echo __my('Country', 'expertrans')?>&nbsp;<em>*</em></label>
		<select name="country" requiredz="<?php echo __my('Country is required', 'expertrans')?>">
		<option value=""><?php __my('Please Select', 'expertrans')?></option>
			<?php echo e_country_options()?>
		</select>
	</li>
	<?php
}

function e_quick_quote()
{
	?>
	<div id="fm-form-quick-quote" class="widget-container widget-fm-form-quick-quote">
		<div class="widget_inner">
			<h3 class="widget-title"><?php echo __my( 'Quick Quote', 'expertrans' )?></h3>
			<div class="inner-form">
				<form class="fm-form fm-form-quick-quote" method="get"
						action="<?php the_permalink()?>"
							>
					<ul>
						<li id="fm-item-type" class="input custom_list select">
							<label ></label>
							<select name="type" id="custom_list-5032d4182969d" >
								<option value="0" selected="selected"><?php echo __my( 'Translation', 'expertrans' )?></option>
								<option value="1"><?php echo __my( 'Interpretation', 'expertrans' )?></option>
								<option value="2"><?php echo __my( 'Voice-overs', 'expertrans' )?></option>
								<option value="3"><?php echo __my( 'Others', 'expertrans' )?></option>
							</select>
						</li>
						<li id="fm-item-source_language" class="input custom_list select cleft no2">
							<label ><?php echo __my( 'Source Language', 'expertrans' )?></label>
							<select name="source_language" id="custom_list-50426a4f3a0c6" >
								<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
								<?php echo e_lang_options()?>
							</select>
						</li>
						<li id="fm-item-target_languages" class="input custom_list list">
							<label ><?php echo __my( 'Target Languages', 'expertrans' )?></label>
							<select name="target_languages[]" id="custom_list-50426a50a33f1" size="79"  multiple="multiple">
								<?php echo e_lang_options()?>
							</select>
						</li>
						<li class="after"></li>
					</ul>
					<div class="submit">
						<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
					</div>
				</form>
				<div class="cl"></div>
			</div>
			<div class="cl"></div>
		</div>
	</div>

		<script type="text/javascript">
			// quick quote script
			jQuery(function(){
				var $ = jQuery;
				var translation_action = <?php echo json_encode(get_option('free_quote_translation_page'))?>;
				var interpretation_action = <?php echo json_encode(get_option('free_quote_interpretation_page'))?>;
				var voice_overs_action = <?php echo json_encode(get_option('free_quote_voice_overs_page'))?>;
				var others_action = <?php echo json_encode(get_option('free_quote_others_page'))?>;
				var $form = $('#fm-form-quick-quote form.fm-form-quick-quote:eq(0)');
				var $selects = $('#fm-item-source_language select,#fm-item-target_languages select');
				$form.attr('method', 'get').find('input[type="hidden"]').attr('disabled', true);
				$('#fm-item-type select').attr('name', 'type');
				$('#fm-item-source_language select').attr('name', 'source_language');
				$('#fm-item-target_languages select').attr('name', 'target_languages[]');
				$('#fm-item-type select').bind('change', function(){
					var val = parseInt($(this).val());
					//console.log(val);
					switch(val) {
					case 0 : // translation
						$selects.removeAttr('disabled');
						$form.attr('action', translation_action);
						break;
					case 1 : // interpretation
						$selects.removeAttr('disabled');
						$form.attr('action', interpretation_action);
						break;
					case 2 : // voice-overs
						$selects.attr('disabled', true);
						$form.attr('action', voice_overs_action);
						break;
					default: // other
						$selects.attr('disabled', true);
						$form.attr('action', others_action);
					}
				}).trigger('change');
			});
		</script>
	<?php
}

add_shortcode( 'free-quote-translation-form', 'e_translation_quote' );
function e_translation_quote()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your quote has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another quote') . '</a>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data"
		class="fm-form fm-form-free-quote-translation" method="post"
		id="free-quote-translation-form"
			action="<?php the_permalink()?>"
				>
			<input type="hidden" name="e" value="free_quote_translation_email" />
			<input type="hidden" name="quote_type" value="<?php echo __my('Quote Request - Translation', 'expertrans')?>"/>
		<ul>
			<li id="fm-item-note" class="input note">
				<label ><?php echo __my( 'Get your free translation quotation', 'expertrans' )?></label>
			</li>
			<li id="fm-item-source_language" class="input custom_list select no1">
				<label ><?php echo __my( 'Source Language', 'expertrans' )?>&nbsp;<em>*</em></label>
					<select name="source_language"  requiredz="<?php echo __my( 'Source Language is required', 'expertrans' )?>">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_lang_options(@$_GET['source_language'])?>
					</select>
			</li>
			<li id="fm-item-target_languages" class="input custom_list list">
				<label ><?php echo __my( 'Target Languages', 'expertrans' )?>&nbsp;<em>*</em>
					</label>
				<select name="target_languages"  size="79"  multiple="multiple" requiredz="<?php echo __my( 'Target Languages is required', 'expertrans' )?>">
					<?php echo e_lang_options(@$_GET['target_languages'])?>
				</select>
				<br/><i class="guide" style="font-weight: normal"><?php echo __my('Hold Ctrl to choose multiple', 'expertrans')?></i>
			</li>
			
			
			<li class="input custom_list list cleft fullwidth">
				<label ><?php echo __my( 'Translation package', 'expertrans' )?></label>
				<?php echo e_translation_package_options()?>
			</li>
			<li class="input custom_list list">
				<label ><?php echo __my( 'Expertise', 'expertrans' )?></label>
				<select name="expertise" >
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_expertise_options()?>
				</select>
			</li>
			
			<li class="input text cleft">
				<label ><?php echo __my( 'Word count', 'expertrans' )?></label>
				<input type="text" name="word_count"   >
			</li>
			<li class="input text">
				<label ><?php echo __my( 'Page count', 'expertrans' )?></label>
				<input type="text" name="page_count"   >
			</li>
			
			<li class="input custom_list list cleft fullwidth">
				<label ><?php echo __my( 'Translation format', 'expertrans' )?></label>
				<select name="translation_format" >
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_translation_format_options()?>
				</select>
			</li>
			
			<li class="input textarea cleft">
				<label ><?php echo __my( 'Translation purpose', 'expertrans' )?></label>
				<textarea name="translation_purpose"></textarea>
			</li>
			<li class="input text">
				<label ><?php echo __my( 'How many notarized copies (if needed)', 'expertrans' )?></label>
				<input type="text" name="how_many_notarized_copies"   >
			</li>

			<li class="input text cleft">
				<label ><?php echo __my('Deadline', 'expertrans')?></label>
				<input type="text" name="deadline"  class="datepicker">
			</li>
			<li class="input custom_list list">
				<label ><?php echo __my( 'Delivery over', 'expertrans' )?></label>
				<label class="checkbox"><input type="checkbox" name="delivery_over[]" value="<?php echo __my('Email', 'expertrans')?>"/><?php echo __my('Email', 'expertrans')?></label>
				<label class="checkbox"><input type="checkbox" name="delivery_over[]" value="<?php echo __my('Directly', 'expertrans')?>"/><?php echo __my('Directly', 'expertrans')?></label>
			</li>
			
			<li id="fm-item-file_upload" class="input file cleft">
				<label ><?php echo __my( 'Attachments', 'expertrans' )?></label>
				<input type="hidden" name="MAX_FILE_SIZE" value="10240000">
				<input name="file"  type="file">
			</li>
			
			<li class="input textarea cleft fullwidth">
				<label ><?php echo __my( 'Delivery address', 'expertrans' )?></label>
				<textarea name="delivery_address"></textarea>
			</li>
			
			<li class="input custom_list select cleft">
				<label ><?php echo __my( 'Payment currency', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="payment_currentcy" requiredz="<?php echo __my( 'Payment currency is required', 'expertrans' )?>">
					<option value="" selected="selected"><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_currencies()?>
				</select>
			</li>
			<li class="input custom_list select ">
				<label ><?php echo __my( 'How do you know us', 'expertrans' )?></label>
					<select name="how_do_you_know_us">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_how_do_you_know_us_options()?>
					</select>
			</li>
			
			<li class="input custom_list select cleft">
				<label ><?php echo __my( 'Payment method', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="payment_method" requiredz="<?php echo __my( 'Payment method is required', 'expertrans' )?>">
					<option value="" selected="selected"><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_payment_methods()?>
				</select>
			</li>
			<li class="input text">
				<label ><?php echo __my( 'Which Google keywords/Who tell you?', 'expertrans' )?></label>
				<input type="text" name="which_google_keywords_or_who_tell_you_about_us"   >
			</li>
			
			<?php echo e_customer_info('no2 no-note')?><li>
			
			<li id="fm-item-other_requirements" class="input textarea cleft no3">
				<label ><?php echo __my( 'Order or comment/further inquiry', 'expertrans' )?></label>
				<textarea name="order_or_comment_or_further_inquiry"></textarea>
			</li>
			<li class="after"></li>
		</ul>
		<div class="submit">
		<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#free-quote-translation-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#free-quote-translation-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( 'free-quote-voice-overs-form', 'e_voice_overs_quote' );
function e_voice_overs_quote()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your quote has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another quote') . '</a>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data"
		class="fm-form fm-form-free-quote-voice-overs" method="post"
		id="free-quote-voice-overs-form"
			action="<?php the_permalink()?>"
				>
			<input type="hidden" name="e" value="free_quote_voice_overs_email" />
			<input type="hidden" name="quote_type" value="<?php echo __my('Quote Request - Voice-overs', 'expertrans')?>"/>
		<ul>
			<?php echo e_personal_info()?>
			<li id="fm-item-note-project-info" class="input note no2 no-note">
				<label ><?php echo __my( 'Describe what and who you need', 'expertrans' )?></label>
			</li>
			<li id="fm-item-title_of_project" class="input text">
				<label ><?php echo __my( 'Title of Project', 'expertrans' )?>&nbsp;<em>*</em></label>
				<input type="text" name="title_of_project" requiredz="<?php echo __my( 'Title of Project is required', 'expertrans' )?>" >
			</li>
			<li id="fm-item-category" class="input text">
				<label ><?php echo __my( 'Category', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="category" requiredz="<?php echo __my( 'Category is required', 'expertrans' )?>" >
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_category_options();?>
				</select>
			</li>
			<li id="fm-item-language" class="input custom_list select cleft">
				<label ><?php echo __my( 'Language', 'expertrans' )?>&nbsp;<em>*</em></label>
					<select name="language" requiredz="<?php echo __my( 'Language is required', 'expertrans' )?>">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_lang_options(@$_GET['source_language'])?>
					</select>
			</li>
			<li id="fm-item-age_range" class="input custom_list select">
				<label ><?php echo __my( 'Age range', 'expertrans' )?>&nbsp;<em>*</em></label>
					<select name="age_range" requiredz="<?php echo __my( 'Age range is required', 'expertrans' )?>">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_age_range_options()?>
					</select>
			</li>
			<li id="fm-item-gender" class="input text cleft">
				<label ><?php echo __my( 'Gender', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="gender" requiredz="<?php echo __my( 'Gender is required', 'expertrans' )?>" >
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_gender_options();?>
				</select>
			</li>
			<li id="fm-item-budget_range" class="input custom_list select">
				<label ><?php echo __my( 'Budget range', 'expertrans' )?>&nbsp;<em>*</em></label>
					<select name="budget_range" requiredz="<?php echo __my( 'Budget range is required', 'expertrans' )?>">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_budget_range_options()?>
					</select>
			</li>

			<li id="fm-item-voice-id" class="input text cleft">
				<label ><?php echo __my( 'Voice ID', 'expertrans' )?>&nbsp;<em>*</em></label>
				<input type="text" name="voice-id" requiredz="<?php echo __my( 'Voice ID is required', 'expertrans' )?>"
					placeholder="Inser the ID of the voice you want in the Gallery, ie: ENM01"
				>
			</li>
			<li id="fm-item-voice-id-link" class="input text">
				<label >&nbsp;</label>
				<a href="http://studio.expertrans.net/search-voice.aspx" target="_blank"><?php echo __my('See the Voice Gallery here', 'expertrans')?></a>
			</li>

			<li id="fm-item-deadline-voice-overs" class="input text cleft">
				<label ><?php echo __my('Deadline', 'expertrans')?></label>
				<input type="text" name="deadline"  class="datepicker">
			</li>
			<li id="fm-item-order_or_comment" class="input textarea cleft no3">
				<label ><?php echo __my( 'Order or comment', 'expertrans' )?></label>
				<textarea name="order_or_comment"></textarea>
			</li>
			<li class="after"></li>
		</ul>
		<div class="submit">
		<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#free-quote-voice-overs-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#free-quote-voice-overs-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}


add_shortcode( 'free-quote-others-form', 'e_others_quote' );
function e_others_quote()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your quote has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another quote') . '</a>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data"
		class="fm-form fm-form-free-quote-others" method="post"
		id="free-quote-others-form"
			action="<?php the_permalink()?>"
				>
			<input type="hidden" name="e" value="free_quote_others_email" />
			<input type="hidden" name="quote_type" value="<?php echo __my('Quote Request - Others', 'expertrans')?>"/>
		<ul>
			<?php echo e_personal_info()?>
			<li id="fm-item-order_or_comment" class="input textarea no2">
				<label ><?php echo __my( 'Order or comment', 'expertrans' )?></label>
				<textarea name="order_or_comment"></textarea>
			</li>
			<li class="after"></li>
		</ul>
		<div class="submit">
		<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#free-quote-others-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#free-quote-others-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( 'free-quote-interpretation-form', 'e_interpretation_quote' );
function e_interpretation_quote()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your quote has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another quote') . '</a>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data"
		class="fm-form fm-form-free-quote-interpretation" method="post"
		id="free-quote-interpretation-form"
			action="<?php the_permalink()?>"
				>
			<input type="hidden" name="e" value="free_quote_interpretation_email" />
			<input type="hidden" name="quote_type" value="<?php echo __my('Quote Request - Interpretation', 'expertrans')?>"/>
		<ul>

			<li id="fm-item-note-project-info" class="input note no1 no-note">
				<label ><?php echo __my( 'Project Info', 'expertrans' )?></label>
			</li>

			<li id="fm-item-source_language-interpretation" class="input custom_list select cleft">
				<label ><?php echo __my( 'Source Language', 'expertrans' )?>&nbsp;<em>*</em></label>
					<select name="source_language"  requiredz="<?php echo __my( 'Source Language is required', 'expertrans' )?>">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_lang_options(@$_GET['source_language'])?>
					</select>
			</li>
			<li id="fm-item-target_language-interpretation" class="input custom_list list">
				<label ><?php echo __my( 'Target language', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="target_language" requiredz="<?php echo __my( 'Target Languages is required', 'expertrans' )?>">
					<?php echo e_lang_options(@$_GET['target_languages'])?>
				</select>
			</li>

			<li class="input custom_list list cboth">
				<label><?php echo __my('Interpreters amount needed')?></label>
				<select name="interpreters_amount_needed">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php for($i=1; $i<=30; $i++) : ?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
					<?php endfor;?>
				</select>
			</li>
			
			<li class="input custom_list list cleft">
				<label ><?php echo __my( 'Expertise', 'expertrans' )?></label>
				<select name="expertise" >
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_expertise_options(@$_GET['target_languages'])?>
				</select>
			</li>
			<li id="fm-item-type_of_interpretation" class="input custom_list select">
				<label ><?php echo __my( 'Type of Interpretation', 'expertrans' )?>&nbsp;<em>*</em></label>
				<select name="type_of_interpretation" requiredz="<?php echo __my( 'Type of Interpretation is required', 'expertrans' )?>">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_type_of_interpretation_options()?>
				</select>
			</li>
			
			<li class="input custom_list select cleft">
				<label ><?php echo __my('Start Time', 'expertrans')?></label>
				<select name="start_time">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_time_options()?>
				</select>
			</li>
			<li class="input custom_list select">
				<label ><?php echo __my('Estimated End Time', 'expertrans')?></label>
				<select name="estimated_end_time">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_time_options()?>
				</select>
			</li>
			
			<li class="input text cleft">
				<label ><?php echo __my('Start Date', 'expertrans')?></label>
				<input type="text" name="start_date"  class="datepicker">
			</li>
			<li class="input text">
				<label ><?php echo __my('End Date', 'expertrans')?></label>
				<input type="text" name="end_date"  class="datepicker">
			</li>
			
			<li class="input text cleft">
				<label ><?php echo __my('Target audience', 'expertrans')?></label>
				<textarea name="target_audience"></textarea>
			</li>
			<li class="input text">
				<label ><?php echo __my('Amount of audience', 'expertrans')?></label>
				<textarea name="amount_of_audience"></textarea>
			</li>

			<li class="input custom_list select">
				<label ><?php echo __my( 'How do you know us', 'expertrans' )?></label>
					<select name="how_do_you_know_us">
						<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
						<?php echo e_how_do_you_know_us_options()?>
					</select>
			</li>
			<li class="input text">
				<label ><?php echo __my( 'Which Google keywords/Who tell you?', 'expertrans' )?></label>
				<input type="text" name="which_google_keywords_or_who_tell_you_about_us"   >
			</li>

			<li id="fm-item-file_upload" class="input file cleft">
				<label ><?php echo __my( 'Reference document (if availabe)', 'expertrans' )?></label>
				<input type="hidden" name="MAX_FILE_SIZE" value="10240000">
				<input name="file"  type="file">
			</li>

			<?php echo e_customer_info('no2 no-note')?>
			
			<li id="fm-item-order_or_comment" class="input textarea no3">
				<label ><?php echo __my( 'Order or comment/futher inquiry', 'expertrans' )?></label>
				<textarea name="order_or_comment_or_futher_inquiry"></textarea>
			</li>
			<li class="after"></li>
		</ul>
		<div class="submit">
		<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#free-quote-interpretation-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#free-quote-interpretation-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( 'linguist-application-form', 'e_linguist_application_form' );
function e_linguist_application_form()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your application has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another application') . '</a>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data"
		class="fm-form fm-form-linguist-application-form" method="post"
		id="linguist-application-form"
			action="<?php the_permalink()?>"
				>
			<input type="hidden" name="e" value="linguist_application_email" />
			<input name="post_type" value="linguist-application" type="hidden" />
			<input type="hidden" name="quote_type" value="<?php echo __my('Linguist Application', 'expertrans')?>"/>
		<ul>
			<?php echo e_personal_info()?>

			<li id="fm-item-skype_id" class="input text cleft">
				<label ><?php echo __my('Skype ID', 'expertrans')?></label>
				<input type="text" name="skype_id" />
			</li>

			<li id="fm-item-note-education" class="input note no2 no-note">
				<label ><?php echo __my( 'Education', 'expertrans' )?></label>
			</li>

			<li class="s3-blocks s3-blocks-education">
				<label>
					<?php echo __my( 'Name of Institution', 'expertrans' )?>
				</label>
				<label>
					<?php echo __my( 'Degree/Certificate', 'expertrans' )?>
				</label>
				<label>
					<?php echo __my( 'Date of Attendance', 'expertrans' )?>
				</label>

				<input type="text" name="name_of_institution_1" />
				<input type="text" name="degree_certificate_1" />
				<input type="text" name="date_of_attendance_1" class="datepicker"/>

				<input type="text" name="name_of_institution_2" />
				<input type="text" name="degree_certificate_2" />
				<input type="text" name="date_of_attendance_2" class="datepicker"/>

				<input type="text" name="name_of_institution_3" />
				<input type="text" name="degree_certificate_3" />
				<input type="text" name="date_of_attendance_3" class="datepicker"/>

				<input type="text" name="name_of_institution_4" />
				<input type="text" name="degree_certificate_4" />
				<input type="text" name="date_of_attendance_4" class="datepicker"/>
			</li>

			<li id="fm-item-note-language-spoken" class="input note no3 no-note">
				<label ><?php echo __my('Language Spoken', 'expertrans')?></label>
			</li>
			<li>
				<label><?php echo __my( '1st Language', 'expertrans' )?></label>
				<select name="language_spoken_1">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_lang_options()?>
				</select>
			</li>
			<li>
				<label><?php echo __my( '2nd Language', 'expertrans' )?></label>
				<select name="language_spoken_2">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_lang_options()?>
				</select>
			</li>
			<li>
				<label><?php echo __my( '3rd Language', 'expertrans' )?></label>
				<select name="language_spoken_3">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_lang_options()?>
				</select>
			</li>
			<li>
				<label><?php echo __my( '4th Language', 'expertrans' )?></label>
				<select name="language_spoken_4">
					<option value=""><?php echo __my( 'Please Select', 'expertrans' )?></option>
					<?php echo e_lang_options()?>
				</select>
			</li>

			<li id="fm-item-note-experiences" class="input note no4 no-note">
				<label ><?php echo __my('Experiences', 'expertrans')?></label>
			</li>

			<li class="s3-blocks s3-blocks-experiences">
				<label>
					<?php echo __my( 'Name of Employer', 'expertrans' )?>
				</label>
				<label>
					<?php echo __my( 'Job Title', 'expertrans' )?>
				</label>
				<label>
					<?php echo __my( 'Duration of Service', 'expertrans' )?>
				</label>

				<input type="text" name="name_of_employer_1" />
				<input type="text" name="job_title_1" />
				<input type="text" name="duration_of_service_1" />

				<input type="text" name="name_of_employer_2" />
				<input type="text" name="job_title_2" />
				<input type="text" name="duration_of_service_2" />

				<input type="text" name="name_of_employer_3" />
				<input type="text" name="job_title_3" />
				<input type="text" name="duration_of_service_3" />

				<input type="text" name="name_of_employer_4" />
				<input type="text" name="job_title_4" />
				<input type="text" name="duration_of_service_4" />
			</li>

			<li id="fm-item-note-expertise" class="input note no5">
				<label ><?php echo __my('Expertise', 'expertrans')?></label>
			</li>

			<li class="expertise-blocks">
				<label class="expertise-field-label"><?php echo __my('Fields', 'expertrans')?></label>
				<?php echo e_checkbox_fields();?>
				<div class="cl"></div>
				<label class="expertise-field-label"><?php echo __my('Services Provided', 'expertrans')?></label>
				<div id="services-provided-checkboxes">
				<?php echo e_checkbox_services_provided();?>
				</div>
				<div id="interpreting-checkboxes" style="display:none">
					<?php echo e_checkbox_interpreting();?>
					<div class="cl"></div>
				</div>

				<label class="expertise-field-label"><?php echo __my('Rate (USD)', 'expertrans')?></label>
				<div class="input"><?php echo __my('Per word', 'expertrans')?><input type="text" class="rate" name="rate_per_word" /></div>
				<div class="input"><?php echo __my('Per hour', 'expertrans')?><input type="text" class="rate" name="rate_per_hour" /></div>
				<div class="input"><?php echo __my('Per day', 'expertrans')?><input type="text" class="rate" name="rate_per_day" /></div>
			</li>

			<li  id="fm-item-note-resume"class="input note no6 no-note">
				<label ><?php echo __my('Resume', 'expertrans')?></label>
			</li>

			<li id="fm-item-file_upload_apply1" class="input file cleft">
				<label ><?php echo __my( 'Please upload your resume here', 'expertrans' )?>&nbsp;<em>*</em></label>
				<input type="hidden" name="MAX_FILE_SIZE" value="10240000">
				<input name="file[]" type="file" requiredz="<?php echo __my('Resume document is required')?>">
			</li>

			<li id="fm-item-file_upload_apply2" class="input file">
				<label ><?php echo __my( 'Please upload your translation text sample (for translators)', 'expertrans' )?></label>
				<input name="file[]" type="file">
			</li>

			<li id="fm-item-file_upload_apply3" class="input file cleft">
				<label ><?php echo __my( 'Please upload your voice sample (for voice-overs freelancers)', 'expertrans' )?></label>
				<input name="file[]" type="file">
			</li>

			<li id="fm-item-note-cover_letter" class="input note no7">
				<label ><?php echo __my('Cover letter', 'expertrans')?></label>
				<div class="guide"><?php echo __my('You can use the text area for a cover letter and any supplementary information you would like to provide about your career goals, availability, best times to contact you, etc.', 'expertrans')?></div>
				<textarea name="cover_letter"></textarea>
			</li>

			<li class="after"></li>
		</ul>
		<div class="submit">
		<input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my( 'Submit', 'expertrans' )?>">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#linguist-application-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#services-provided-checkboxes .Interpreting:eq(0)').bind('click', function(){
				if($(this).is(':checked')) {
					$('#interpreting-checkboxes').show().find('input').removeAttr('disabled');;
				} else {
					$('#interpreting-checkboxes').hide().find('input').attr('disabled', true);
				}
			});
			$('#linguist-application-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}


add_shortcode( 'contact-us-form', 'e_contact_us_form' );
function e_contact_us_form()
{
	if(!empty($_POST))
	{
		// save data
		e_post_email_to_admin();
		$content = __my('Thank you! Your message has been sent to us. CNN Translation will give you a feedback soon!', 'expertrans');
		$content .= '&nbsp;<a style="text-decoration: underline; color: #08f" href="' . get_permalink() . '">' . __my('Click here to make another message') . '</a>';
		$content .= '<div class="cl"></div>';
		return $content;
	}
	ob_start();
	?>
	<div class="content-quote">
	<form enctype="multipart/form-data" class="fm-form fm-form-contact-us" method="post" action="<?php the_permalink()?>"
		name="fm-form-3" id="contact-us-form">
		<input type="hidden" name="e" value="contact_message_email" />
		<input name="post_type" value="contact" type="hidden" />
		<input type="hidden" name="quote_type" value="<?php echo __my('Contact Message', 'expertrans')?>"/>
		<ul>
			<li id="fm-item-note" class="input note">
				<label ><?php echo __my('Please leave a message', 'expertrans')?>:</label>
			</li>
			<li id="fm-item-message" class="input textarea">
				<label ><?php echo __my('Message', 'expertrans')?>&nbsp;<em>*</em></label>
				<textarea name="message" requiredz="<?php echo __my('Message is required', 'expertrans')?>" placeholder=""></textarea>
			</li>
			<li id="fm-item-name" class="input text cleft">
				<label ><?php echo __my('Name', 'expertrans')?>&nbsp;<em>*</em></label>
				<input type="text" name="requester_name" requiredz="<?php echo __my('Name is required', 'expertrans')?>" placeholder="">
			</li>
			<li id="fm-item-email" class="input text">
				<label ><?php echo __my('Email', 'expertrans')?>&nbsp;<em>*</em></label>
				<input type="text" name="email" requiredz="<?php echo __my('Email is required', 'expertrans')?>" placeholder="">
			</li>
			<li class="after"></li>
		</ul>
		<div class="submit">
		 <input type="submit" name="fm_form_submit" id="fm_form_submit" class="submit" value="<?php echo __my('Submit', 'expertrans')?>" onclick="return fm_submit_onclick(3)">
		</div>
	</form>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			var $ = jQuery;
			$('#contact-us-form .datepicker').datepicker({
				 dateFormat : 'm/d/yy'
			});
			$('#contact-us-form').bind('submit', function(){
				var error = '';
				$(this).find('input,select,textarea').each(function(){
					var requiredz = $(this).attr('requiredz');
					if(requiredz && !$(this).val()) {
						error += requiredz + "\n";
					}
				});
				if(error != '') {
					alert(error); return false;
				}
			});
		});
	</script>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}