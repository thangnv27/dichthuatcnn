<?php
#add_action( 'after_setup_theme', 'cnn_theme_setup' );#function cnn_theme_setup()#{	// This theme uses post thumbnails	add_theme_support( 'post-thumbnails' );	// Add default posts and comments RSS feed links to head	add_theme_support( 'automatic-feed-links' );		wp_enqueue_script('jquery-ui-datepicker');		wp_enqueue_style('jquery-ui-theme', get_bloginfo('template_url') . '/css/jquery.ui/smoothness/jquery-ui-1.8.23.custom.css');#}
if (function_exists('register_sidebar'))
{
    register_sidebar(array(
        'before_widget' => '<div id="%1$s" class="whyus %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="title"> <span>',
        'after_title' => '</span></h3>',
		'name' => 'Sidebar-home'
    ));
  
   register_sidebar(array(
        'before_widget' => '<div id="%1$s" class="video %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<span>',
        'after_title' => '</span>',
		'name' => 'Video'
    ));
	
	 register_sidebar(array(
        'before_widget' => '<div id="%1$s" class="box support %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="title"><span>',
        'after_title' => '</span></h3>',
		'name' => 'Support online'
    ));
	
	
		register_sidebar(
   		array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
		'name' => 'Footer'
	));
	
	register_sidebar(
   		array(
		'before_widget' => '<div id="%1$s" class="main-menu %2$s">',
		'after_widget' => '</div>',
        'before_title' => '<h3 class="title">',
        'after_title' => '</h3>',
		'name' => 'Menu ac'
	));
	

$themename = "Dịch thuật CNN";
$shortname = str_replace(' ', '_', strtolower($themename));

  /// require_once (TEMPLATEPATH . '/includes/' . 'custom.php');

function get_theme_option($option)
{
	global $shortname;
	return stripslashes(get_option($shortname . '_' . $option));
}

function get_theme_option_multi($option)
{
	global $shortname;
	return get_option($shortname . '_' . $option);
}

function get_theme_settings($option)
{
	return stripslashes(get_option($option));
}

function theme_init(){
	$lang = 'default.mo';
	if (isset($lang) && $lang) {
		load_textdomain('themetiger', get_template_directory() . '/languages/' . $lang);
	}
}
theme_init();

function cats_to_select()
{
	$categories = get_categories('hide_empty=1');
	//$categories_array[] = array('value'=>'0', 'title'=>'Select');
	foreach ($categories as $cat) {
		if($cat->category_count == '0') {
			$posts_title = 'No posts!';
		} elseif($cat->category_count == '1') {
			$posts_title = '1 post';
		} else {
			$posts_title = $cat->category_count . ' posts';
		}
		$categories_array[] = array('value'=> $cat->cat_ID, 'title'=> $cat->cat_name . ' ( ' . $posts_title . ' )');
	  }
	return $categories_array;
}
   
$lang_dir = opendir( TEMPLATEPATH . "/languages/" );
$langs[] = array('value'=>"0", 'title'=> __("English (default)","themetiger") );
while (false !== ( $lang_folder = readdir( $lang_dir ) ) ) {
	if( $lang_folder != "." && $lang_folder != ".." && eregi("\.mo",$lang_folder)) {
		$cp_langName = $lang_folder;
		$langs[] = array( 'value'=>$lang_folder,'title'=> $cp_langName );
	}
}
closedir( $lang_dir );
$options = array (
			
	array(	"type" => "open"),
	
	
		
	array(	"name" => __('Favicon','themetiger'),
		"desc" => __('Enter the favicon full path. Leave it blank if you don\'t want to use favicon.','themetiger'),
		"id" => $shortname."_favicon",
		"std" =>  '',
		"type" => "text"),
		
		 array(	"name" => __('Gioi thieu CNN','themetiger'),
			"desc" => __('Thêm nội dung giới thiệu vào đây.)','themetiger'),
            "id" => $shortname."_about",
            "type" => "textarea",
			"std" => ''
			),
	array(	"name" => __('Show thumbnail?','themetiger'),
			"desc" => '',
			"id" => $shortname."_show_thumb",
			"std" => "true",
			"type" => "checkbox"),
			
    array(	"name" => "Twitter",
			"desc" => "Enter your Twitter account url here.",
			"id" => $shortname."_twitter",
			"std" => "http://twitter.com/themetiger",
			"type" => "text"),
	
	 array(	"name" => "Facebook",
			"desc" => "Enter your Facebook account url here.",
			"id" => $shortname."_facebook",
			"std" => "http://www.facebook.com/themetiger",
			"type" => "text"),
			
	array(	"name" => __('Chia sẻ mạng xã hội?','themetiger'),
			"desc" => __('Uncheck if you do not want to show social networks at Sidebar.','themetiger'),
			"id" => $shortname."_socialnetworks",
			"std" => "true",
			"type" => "checkbox"),
			
	array(	"name" => __('Ads logo 140x60 px','themetiger'),
		"desc" => __('Bạn thêm logo đối tác sẽ hiển thị dưới footer. ex : '),
        "id" => $shortname."_ads_125",
        "type" => "textarea",
		"std" => get_bloginfo('template_url').'/images/ads/125bnr.gif,http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com'),


array(	"name" => __('Slide show Home','themetiger'),
		"desc" => __('Bạn co the them lide show tuy y nhe. ex : '),
        "id" => $shortname."_slideshow",
        "type" => "textarea",
		"std" => get_bloginfo('template_url').'/images/ads/125bnr.gif,http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com
'.get_bloginfo('template_url').'/images/ads/125bnr.gif, http://www.themetiger.com'),
			
				
			
					
	array(	"type" => "close")
	
);
function mytheme_add_admin() {
    global $themename, $shortname, $options;
	
    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

               echo '<meta http-equiv="refresh" content="0;url=themes.php?page=functions.php&saved=true">';
               die;

        }
    }

    add_theme_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}



function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    
?>
<div class="wrap">
<h2>Cài đặt giao diện <?php echo $themename; ?> </h2>
<link href="<?php bloginfo('template_url'); ?>/css/admin.css" rel="stylesheet" type="text/css" />

<form method="post">

<?php foreach ($options as $value) {
	switch ( $value['type'] ) {
		case "open":
		?>
        <table width="100%" cellpadding="2" cellspacing="1">
		<?php break;
		
		case "close":
		?>
        </table><br />
		<?php break;
		case "title":
		?>
		<tr>
        	<td colspan="2"><h3><?php echo $value['name']; ?></h3></td>
        </tr>
		<?php break;
		case 'text':
		?>
        <tr>
            <td width="20%" valign="top"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><input size="40" class="<?php echo $value['class']; ?>" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo get_theme_settings( $value['id'] ); ?>" /> <?php echo $value['desc']; ?>
			
			</td>
        </tr>
		
		<?php
		break;
		case 'textarea':
		?>
        <tr>
            <td width="20%"  valign="top"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:100%; height:140px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php echo get_theme_settings( $value['id'] ); ?></textarea>
			<p><?php echo $value['desc']; ?></p>
			</td>
        </tr>
		
		
		<?php
		break;
		case 'select':
		?>
        <tr>
            <td width="20%" valign="top"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%">
				<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
					<?php foreach ($value['options'] as $option) {?>
						<option value="<?php echo $option['value']; ?>" <?php if(get_theme_settings( $value['id'] ) == $option['value']) { echo ' selected="selected"'; } ?>><?php echo $option['title']; ?></option>
						<?php } ?>
				</select>
				<p><?php echo $value['desc']; ?></p>
			</td>
       </tr>
		
	   <?php
		break;
		
		case 'multi-select':
		?>
        <tr>
            <td width="20%" rowspan="2" valign="top"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%">
				<select style="width:240px; height:200px" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>[]" multiple="true" size="10">
					<?php
						foreach ($value['options'] as $option) {
						if(get_option($value['id'])){
							 if( in_array( $option['value'], get_option($value['id']) ) ) $extra = ' selected="selected"';
							 else $extra = '';
						}
						?>
						<option value="<?php echo $option['value']; ?>" <?php echo $extra; ?>><?php echo $option['title']; ?></option>
						<?php } ?>
				</select>
			</td>
       </tr>
                
       <tr>
            <td><p><?php echo $value['desc']; ?></p></td>
       </tr>

		<?php
        break;
            
		case "checkbox":
		?>
            <tr>
            <td width="20%" valign="top"><strong><?php echo $value['name']; ?></strong></td>
                <td width="80%"><? if(get_theme_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        <p><?php echo $value['desc']; ?></p>
						</td>
            </tr>

            
        <?php 		break;
	
 
}
}
?>

<!--</table>-->

<p class="submit">
<input name="save" type="submit" value="<?php _e('Save changes','themetiger');?>" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<style type="text/css">
.regular-text{
	width:100%;
}
.small-text{
	width:50px;
}
</style>
<?php
}
//mytheme_admin_init();
add_action('admin_menu', 'mytheme_add_admin');

//Add Image to default RSS Feed
function addimg2rss($post_content) {
	global $wp_query;
	$postid = $wp_query->post->ID;
	$thumb = get_post_meta($postid, 'image', true);
	$thumb = str_replace(get_bloginfo('url').'/','',$thumb);
	if(is_feed()) {
			if($thumb !== '') {
				$content = '<img src="'.get_bloginfo('template_url').'/thumb.php?src='.$thumb.'&amp;w=150&amp;h=120&amp;zc=1&amp;q=90" align="left" hspace="10">';
				$content .= $post_content."<br /><br />\n";
			}
			else {
				$content = $post_content;
			}
		}
		return $content;
	}
	
add_filter('the_excerpt_rss', 'addimg2rss');



if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'menu_1' => 'Menu 1',
		  'menu_2' => 'Menu Dich vu',
		  'menu_3' => 'Menu F Col 1',
		  'menu_4' => 'Menu F Col 2',
		  'menu_5' => 'Menu F Col 3',
		  'menu_6' => 'Menu F Col 4',
		  'menu_7' => 'Menu F Col 5',
		  'menu_8' => 'Menu Footer',
		)
	);
}

function cut_string($str,$len,$more){
	if ($str=="" || $str==NULL) return $str;
	if (is_array($str)) return $str;
	$str = trim(strip_tags($str));
	if (strlen($str) <= $len) return $str;
	$str = substr($str,0,$len);
	if ($str != "") {
	  if (!substr_count($str," ")) {
			  if ($more) $str .= " ...";
			return $str;
		}
	while(strlen($str) && ($str[strlen($str)-1] != " ")) {
			$str = substr($str,0,-1);
		}
		$str = substr($str,0,-1);
		if ($more) $str .= " ...";
	}
	return $str;

}
}

function show_thumb_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = bloginfo('url')."/wp-content/themes/dichthuatcnn/images/img-macdinh.png";
  }
  return $first_img;
}
require_once get_template_directory() . '/functions/theme-options.php';require_once get_template_directory() . '/functions/quotes.php';
