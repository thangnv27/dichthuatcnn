<div class="mright">
 					<div class="connect-forum">
                    	<h3 class="title utmavo">KẾT NỐI VỚI DIỄN ĐÀN</h3>
                        <div class="content">
                        	  <p class="button">
                            <a href="#" class="register">Đăng ký</a>
                            <a href="#" class="login">Đăng nhập</a>
                            </p>
                           
                           
                        </div>
                    </div>
                    
                    
                    <div class="huongdan">
                    	<h3 class="title-slidebar">Hướng dẫn <span> <a href="http://vietnamwordpress.com/wordpress/" class="m04"> Xem tất cả </a></span></h3>
                        <div class="content">
                        	<ul>
                             <?php query_posts("showposts=2&cat=6");?>
							<?php while (have_posts()) : the_post(); ?>
                            	<li>
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                               <?php $excerpt = cut_string(get_the_excerpt(),100,'...'); ?>
                                <p><?php echo $excerpt; ?> </p>
                               </li>
                                 	
                             <?php endwhile; ?>
                            </ul>
                           
                        </div>
                   </div>
                   <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
				   <?php endif; ?>    
 </div>
                <style type="text/css">
				.mright .connect-forum {background:url(<?php bloginfo('template_url'); ?>/images/connect_forum.png) no-repeat top; margin-top:80px; height:auto;}
				.mright .connect-forum  p.button { float:left;margin-left: 20px; margin-top: 10px; padding-bottom:10px; margin-bottom:10px;}
				</style>