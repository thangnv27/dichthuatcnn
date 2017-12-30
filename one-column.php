<?php
/*
Template Name: One Column 
*/
?>
<?php get_header(); ?>

    			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    			<div class="post clearfix" id="post-<?php the_ID(); ?>">
    			<h2 class="title"><?php the_title(); ?></h2>
    				<div class="entry">
                        <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(300,225), array("class" => "alignleft post_thumbnail")); } ?>
    					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
    	
    					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    	
    				</div>
    			</div>
               
    			<?php endwhile; endif; ?>
    		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
    		
<!-- You can start editing here. -->
<?php comments_template(); ?>
<?php get_footer(); ?>