<?php 

function tiger_metabox_create() {
    global $post;
    $tiger_metaboxes = get_option('tiger_custom_template');     
    $output = '';
    $output .= '<table class="tiger_metaboxes_table">'."\n";
    foreach ($tiger_metaboxes as $tiger_id => $tiger_metabox) {
    if(        
            $tiger_metabox['type'] == 'text' 
    OR      $tiger_metabox['type'] == 'select' 
    OR      $tiger_metabox['type'] == 'checkbox' 
    OR      $tiger_metabox['type'] == 'textarea'
    OR      $tiger_metabox['type'] == 'radio'
    )
            $tiger_metaboxvalue = get_post_meta($post->ID,$tiger_metabox["name"],true);
            
            if ($tiger_metaboxvalue == "" || !isset($tiger_metaboxvalue)) {
                $tiger_metaboxvalue = $tiger_metabox['std'];
            }
            if($tiger_metabox['type'] == 'text'){
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_id.'">'.$tiger_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input class="tiger_input_text" type="'.$tiger_metabox['type'].'" value="'.$tiger_metaboxvalue.'" name="tiger_'.$tiger_metabox["name"].'" id="'.$tiger_id.'"/>';
                $output .= '<span class="tiger_metabox_desc">'.$tiger_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'<td></td></tr>'."\n";  
                              
            }
            
            elseif ($tiger_metabox['type'] == 'textarea'){
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_metabox.'">'.$tiger_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td colspan="2"><textarea class="tiger_input_textarea" name="tiger_'.$tiger_metabox["name"].'" id="'.$tiger_id.'">' . $tiger_metaboxvalue . '</textarea>';
                $output .= '<span class="tiger_metabox_desc">'.$tiger_metabox['desc'].'</span></td>'."\n";
                $output .= "\t".'<td></td></tr>'."\n";  
                              
            }

            elseif ($tiger_metabox['type'] == 'select'){
                       
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_id.'">'.$tiger_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><select class="tiger_input_select" id="'.$tiger_id.'" name="tiger_'. $tiger_metabox["name"] .'">';
                $output .= '<option value="">Select to return to default</option>';
                
                $array = $tiger_metabox['options'];
                
                if($array){
                
                    foreach ( $array as $id => $option ) {
                        $selected = '';
                       
                                                       
                        if($tiger_metabox['default'] == $option && empty($tiger_metaboxvalue)){$selected = 'selected="selected"';} 
                        else  {$selected = '';}
                        
                        if($tiger_metaboxvalue == $option){$selected = 'selected="selected"';}
                        else  {$selected = '';}  
                        
                        $output .= '<option value="'. $option .'" '. $selected .'>' . $option .'</option>';
                    }
                }
                
                $output .= '</select><span class="tiger_metabox_desc">'.$tiger_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
            
            elseif ($tiger_metabox['type'] == 'checkbox'){
            
                if($tiger_metaboxvalue == 'true') { $checked = ' checked="checked"';} else {$checked='';}

                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_id.'">'.$tiger_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td><input type="checkbox" '.$checked.' class="tiger_input_checkbox" value="true"  id="'.$tiger_id.'" name="tiger_'. $tiger_metabox["name"] .'" />';
                $output .= '<span class="tiger_metabox_desc" style="display:inline">'.$tiger_metabox['desc'].'</span></td></td><td></td>'."\n";
                $output .= "\t".'</tr>'."\n";
            }
            
            elseif ($tiger_metabox['type'] == 'radio'){
            
                $array = $tiger_metabox['options'];
            
            if($array){
            
            $output .= "\t".'<tr>';
            $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_id.'">'.$tiger_metabox['label'].'</label></th>'."\n";
            $output .= "\t\t".'<td>';
            
                foreach ( $array as $id => $option ) {
                              
                    if($tiger_metaboxvalue == $option) { $checked = ' checked';} else {$checked='';}

                        $output .= '<input type="radio" '.$checked.' value="' . $id . '" class="tiger_input_radio"  id="'.$tiger_id.'" name="tiger_'. $tiger_metabox["name"] .'" />';
                        $output .= '<span class="tiger_input_radio_desc" style="display:inline">'. $option .'</span><div class="tiger_spacer"></div>';
                    }
                    $output .=  '</td></td><td></td>'."\n";
                    $output .= "\t".'</tr>'."\n";    
                 }
            }
            
            elseif($tiger_metabox['type'] == 'upload')
            {
            
                $output .= "\t".'<tr>';
                $output .= "\t\t".'<th class="tiger_metabox_names"><label for="'.$tiger_id.'">'.$tiger_metabox['label'].'</label></th>'."\n";
                $output .= "\t\t".'<td class="tiger_metabox_fields">'. tiger_uploader_custom_fields($post->ID,$tiger_metabox["name"],$tiger_metabox["default"],$tiger_metabox["desc"]);
                $output .= '</td>'."\n";
                $output .= "\t".'</tr>'."\n";
                
            }
        }
    
    $output .= '</table>'."\n\n";
    echo $output;
}



/*-----------------------------------------------------------------------------------*/
// tiger_uploader_custom_fields
/*-----------------------------------------------------------------------------------*/

function tiger_uploader_custom_fields($pID,$id,$std,$desc){

    // Start Uploader
    $upload = get_post_meta( $pID, $id, true);
	$href = str_replace(get_bloginfo('url').'/','',$upload);
	//$href = cleanSource($upload);
    $uploader .= '<input class="tiger_input_text" name="'.$id.'" type="text" value="'.$upload.'" />';
    $uploader .= '<div class="clear"></div>'."\n";
    $uploader .= '<input type="file" name="attachement_'.$id.'" />';
    $uploader .= '<input type="submit" class="button button-highlighted" value="'.__('Upload & Save','themetiger').'" name="save"/>';
    $uploader .= '<span class="tiger_metabox_desc"><b>'.$desc.'</b></span></td>'."\n".'<td class="tiger_metabox_image"><a href="'. $upload .'"><img src="'.get_bloginfo('template_url').'/thumb.php?src='.$href.'&w=150&h=80&zc=1" alt="" /></a>';

return $uploader;
}



/*-----------------------------------------------------------------------------------*/
// tiger_metabox_handle
/*-----------------------------------------------------------------------------------*/

function tiger_metabox_handle(){   
    
    global $globals;
    $tiger_metaboxes = get_option('tiger_custom_template');     
    $pID = $_POST['post_ID'];
    $upload_tracking = array();
    
    if ($_POST['action'] == 'editpost'){                                   
        foreach ($tiger_metaboxes as $tiger_metabox) { // On Save.. this gets looped in the header response and saves the values submitted
            if($tiger_metabox['type'] == 'text' OR $tiger_metabox['type'] == 'select' OR $tiger_metabox['type'] == 'checkbox' OR $tiger_metabox['type'] == 'textarea' ) // Normal Type Things...
                {
                    $var = "tiger_".$tiger_metabox["name"];
                    if (isset($_POST[$var])) {            
                        if( get_post_meta( $pID, $tiger_metabox["name"] ) == "" )
                            add_post_meta($pID, $tiger_metabox["name"], $_POST[$var], true );
                        elseif($_POST[$var] != get_post_meta($pID, $tiger_metabox["name"], true))
                            update_post_meta($pID, $tiger_metabox["name"], $_POST[$var]);
                        elseif($_POST[$var] == "") {
                           delete_post_meta($pID, $tiger_metabox["name"], get_post_meta($pID, $tiger_metabox["name"], true));
                        }
                    }
                    elseif(!isset($_POST[$var]) && $tiger_metabox['type'] == 'checkbox') { 
                        update_post_meta($pID, $tiger_metabox["name"], 'false'); 
                    }      
                    else {
                          delete_post_meta($pID, $tiger_metabox["name"], get_post_meta($pID, $tiger_metabox["name"], true)); // Deletes check boxes OR no $_POST
                    }    
                }
          
            elseif($tiger_metabox['type'] == 'upload') // So, the upload inputs will do this rather
                {
                $id = $tiger_metabox['name'];
                $override['action'] = 'editpost';
                    if(!empty($_FILES['attachement_'.$id]['name'])){ //New upload          
                           $uploaded_file = wp_handle_upload($_FILES['attachement_' . $id ],$override); 
                           $uploaded_file['option_name']  = $tiger_metabox['label'];
                           $upload_tracking[] = $uploaded_file;
                           update_post_meta($pID, $id, $uploaded_file['url']);
                    }
                    elseif(empty( $_FILES['attachement_'.$id]['name']) && isset($_POST[ $id ])){
                        update_post_meta($pID, $id, $_POST[ $id ]); 
                    }
                    elseif($_POST[ $id ] == '')  { delete_post_meta($pID, $id, get_post_meta($pID, $id, true));
                    }
                }
               // Error Tracking - File upload was not an Image
               update_option('tiger_custom_upload_tracking', $upload_tracking);
            }
        }
}



/*-----------------------------------------------------------------------------------*/
// tiger_metabox_add
/*-----------------------------------------------------------------------------------*/

function tiger_metabox_add() {
	global $themename;
    if ( function_exists('add_meta_box') ) {
        add_meta_box('themetiger-settings',$themename.' '.__('Settings','themetiger'),'tiger_metabox_create','post','normal','high');
        //add_meta_box('themetiger-settings',$themename.' '.__('Settings','themetiger'),'tiger_metabox_create','page','normal','high'); This for page. Not use here
    }
}



/*-----------------------------------------------------------------------------------*/
// tiger_metabox_header
/*-----------------------------------------------------------------------------------*/

function tiger_metabox_header(){
?>
<script type="text/javascript">

    jQuery(document).ready(function(){
	
        jQuery('form#post').attr('enctype','multipart/form-data');
        jQuery('form#post').attr('encoding','multipart/form-data');
        jQuery('.tiger_metaboxes_table th:last, .tiger_metaboxes_table td:last').css('border','0');
        var val = jQuery('input#title').attr('value');
        if(val == ''){ 
        jQuery('.tiger_metabox_fields .button-highlighted').after("<br><em class='tiger_red_note'><?php _e('You need add a Title for this post before upload photo','themetiger');?></em>");
        };
        <?php //Errors
        $error_occurred = false;
        $upload_tracking = get_option('tiger_custom_upload_tracking');
        if(!empty($upload_tracking)){
        $output = '<div style="clear:both;height:20px;"></div><div class="errors"><ul>' . "\n";
            $error_shown == false;
            foreach($upload_tracking as $array )
            {
                 if(array_key_exists('error', $array)){
                        $error_occurred = true;
                        ?>
                        jQuery('form#post').before('<div class="updated fade"><p>themetiger Upload Error: <strong><?php echo $array['option_name'] ?></strong> - <?php echo $array['error'] ?></p></div>');
                        <?php
                }
            }
        }
		
        delete_option('tiger_upload_custom_errors');
        ?>
    });

</script>
<style type="text/css">

.tiger_input_text { margin:0 0 10px 0; background:#f4f4f4; color:#444; width:80%; font-size:11px; padding: 5px;}
.tiger_input_select { margin:0 0 10px 0; background:#f4f4f4; color:#444; width:60%; font-size:11px; padding: 5px;}
.tiger_input_checkbox { margin:0 10px 0 0; }
.tiger_input_radio { margin:0 10px 0 0; }
.tiger_input_radio_desc { font-size: 12px; color: #666 ; }
.tiger_spacer { display: block; height:5px}
.tiger_metabox_desc { font-size:11px; color:#333; display:block; line-height:22px;}
.tiger_metabox_desc b{color:#FF0000}
.tiger_metaboxes_table{ border-collapse:collapse; width:100%}
.tiger_metaboxes_table tr:hover th,
.tiger_metaboxes_table tr:hover td { background:#f8f8f8}
.tiger_metaboxes_table th,
.tiger_metaboxes_table td{ padding:10px 10px;text-align: left; vertical-align:top}

.tiger_metabox_image { text-align: right;}
.tiger_red_note { margin-left: 5px; font-size: 11px; color:#FF0000}
.tiger_input_textarea { width:100%; height:120px;margin:0 0 10px 0;font-size:12px;padding: 5px;}
</style>
<?php
}
add_action('edit_post', 'tiger_metabox_handle');
 add_action('admin_menu', 'tiger_metabox_add'); // Triggers tiger_metabox_create
add_action('admin_head', 'tiger_metabox_header');
?>