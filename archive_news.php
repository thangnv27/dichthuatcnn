<?php get_header(); ?>



<div id="main">

        <div class="mainbody clearfix">

        	<div class="wrapper">

        	<?php get_sidebar(); ?>

            <div class="mright">

            	<div class="category-page">

                    <div class="breadcrumbs">

                        <ul class="main">

                     

						<?php if(function_exists('bcn_display'))

                        {

                            bcn_display();

                        }?>

						

                        </ul>

                    </div><!-- end .breadcrumbs -->

                	<div class="list-services">

                    

	<?php if (have_posts()) : ?>

	

	 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

 	  <?php /* If this is a category archive */ if (is_category()) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span><?php single_cat_title(); ?></span></strong></p></h1>

 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span><?php single_tag_title(); ?></span></strong></p></h1>

 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>

		<<h1 class="pagetitle"><p class="title"><strong><span><?php the_time('F jS, Y'); ?></span></strong></p></h1>

 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span><?php the_time('F, Y'); ?></span></strong></p></h1>

 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span> <?php the_time('Y'); ?></span></strong></p></h1>

	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span><?php _e('Author Archive','themetiger');?></span></strong></p></h1>

 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>

		<h1 class="pagetitle"><p class="title"><strong><span><?php _e('Blog Archives','themetiger');?></h2>

 	  <?php } elseif (is_search()){ ?>

	 <h1 class="pagetitle"><p class="title"><strong><span><?php _e('Search Results','themetiger');?></span></strong></p></h1>

	  <?php }  ?>

	                  <div class="detail">   <ul class="list"> 

     					  <?php  $width = 168;$height =110;

                          while (have_posts()) : the_post(); ?>

     <li>

                                    <div class="images">

                                       <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a> 

                                    </div>

                                    <div class="text">

                                      <a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a>

                                        <span class="date"><?php the_time('F jS, Y') ?></span>

                                      <?php $excerpt = cut_string(get_the_content(),150,'[...]'); ?>

            <p>  <?php echo $excerpt; ?></p>

                                        <a class="more" href="<?php the_permalink() ?>" rel="nofollow" title="<?php the_title_attribute(); ?>"><?php _e('Xem chi tiết','themetiger');?> ...</a>

                                    </div>

                                </li>



<?php endwhile; ?>

</ul>

<div class="navigation">

	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>

	<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>

	<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>

	<?php } ?>

</div>



<?php else : ?>

	<?php

	if ( is_category() ) { // If this is a category archive

			printf("<h2 class='generic'>".__('Sorry, but there aren\'t any posts in the %s category yet','themetiger')."</h2>", single_cat_title('',false));

		} else if ( is_date() ) { // If this is a date archive

			echo("<h2 class='generic'>".__('Sorry, but there aren\'t any posts with this date.','themetiger')."</h2>");

		} else if ( is_author() ) { // If this is a category archive

			$userdata = get_userdatabylogin(get_query_var('author_name'));

			printf("<h2 class='generic'>".__('Sorry, but there aren\'t any posts by %s yet..','themetiger')."</h2>", $userdata->display_name);

		} else {

			echo('<h2 class="generic">'.__('No posts found.','themetiger').'</h2><p class="center">'.__('Sorry, but you are looking for something that isn\'t here.','themetiger').'</p>');

		}

	?>		

	<?php get_search_form(); ?>



<?php endif; ?>

                  

                                   

			        </div>

        <!-- END list-services -->   

        

        

           

                           

                     

                </div><!-- end .category-page -->

            </div><!-- end .mright -->

        	</div>       

                 

                </div><!-- end .category-page -->

            </div><!-- end .mright -->

        	</div>

        </div><!-- end .mainbody -->

        

      

<?php get_footer(); ?>