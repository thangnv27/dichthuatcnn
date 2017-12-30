<div class="mleft">
            	
                <div id="accordion-1" class="main-menu accordion">
                	<h3 class="title">
                    	<span>dịch vụ</span>
                    </h3>
                    <div class="content"  id="accordion-1">
                    	 <?php 
             if(function_exists('wp_nav_menu')){ 
				        wp_nav_menu(array('theme_location'=>'menu_2',
										  'fallback_cb'=>'alert_menu',
										   'menu_class'=>'main'
										  ));
			}else{
            ?>
          	<?php wp_list_pages('title_li='); ?>
      	    <?php }?>
                       
                    </div>
                </div>
               
                <!--
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(5) ) : ?>
				<?php endif; ?> -->
                <!-- end .main-menu -->
               
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
				<?php endif; ?>
                
                
                <!-- end .box -->
                
                
                  
                <!-- end .box -->
                
                 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : ?>
				<?php endif; ?>
            </div><!-- end .mleft -->