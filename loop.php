<?php get_header(); ?>
<br />
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
                    	<p class="title"><strong><span><a href="<?php echo get_category_link('5'); ?>">Dịch thuật</a></span></strong></p>
                         <div class="detail">    
     					<div class="first-content">                    	
                 <?php query_posts("showposts=6&cat=5");?>
				<?php $p=0;?>
		
				<?php while (have_posts()) : the_post(); ?>
				<?php $p++;$width=170;$height=93;
				if($p<2){
				$excerpt = cut_string(get_the_excerpt(),150,'[...]');
				?>
				<div class="images">
				
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>
				
				</div><div class="text">
                                <h2>	<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>
                                    <p><?php echo $excerpt; ?></p>
                                  <a rel="nofollow" class="more" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php _e('Xem chi tiết','themetiger');?> >></a>
                                </div>
				
                </div>
                <div class="category">
                <ul class="main">
				<?php } else { ?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></li>
				<?php }?>
				<?php endwhile; ?>
                    </ul>
                    </div>               
			 </div>
        <!-- END list-services -->   
        
        
        
        <div class="list-services">
                    	<p class="title"><strong><span><a href="<?php echo get_category_link('6'); ?>">Phiên dịch</a></span></strong></p>
                         <div class="detail">    
     					<div class="first-content">                    	
                 <?php query_posts("showposts=6&cat=6");?>
				<?php $p=0;?>
		
				<?php while (have_posts()) : the_post(); ?>
				<?php $p++;$width=170;$height=93;
				if($p<2){
				$excerpt = cut_string(get_the_excerpt(),200,'[...]');
				?>
				<div class="images">
				
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>
				
				</div><div class="text">
                                <h2>	<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>
                                    <p><?php echo $excerpt; ?></p>
                                    <a rel="nofollow" class="more" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php _e('Xem chi tiết','themetiger');?> >></a>
                                </div>
				
                </div>
                <div class="category">
                <ul class="main">
				<?php } else { ?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></li>
				<?php }?>
				<?php endwhile; ?>
                    </ul>
                    </div>               
			 </div>
        <!-- END list-services -->       
        
        
        <div class="list-services">
                    	<p class="title"><strong><span><a href="<?php echo get_category_link('30'); ?>">Thu âm lồng tiếng chèn phụ đề</a></span></strong></p>
                         <div class="detail">    
     					<div class="first-content">                    	
                 <?php query_posts("showposts=6&cat=30");?>
				<?php $p=0;?>
		
				<?php while (have_posts()) : the_post(); ?>
				<?php $p++;$width=170;$height=93;
				if($p<2){
				$excerpt = cut_string(get_the_excerpt(),200,'[...]');
				?>
				<div class="images">
				
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>
				
				</div><div class="text">
                                <h2>	<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>
                                    <p><?php echo $excerpt; ?></p>
                                   <a rel="nofollow" class="more" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php _e('Xem chi tiết','themetiger');?> >></a>
                                </div>
				
                </div>
                <div class="category">
                <ul class="main">
				<?php } else { ?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></li>
				<?php }?>
				<?php endwhile; ?>
                    </ul>
                    </div>               
			 </div>
        <!-- END list-services -->       
        
     
           <div class="list-services last">
                    	<p class="title"><strong><span><a href="<?php echo get_category_link('43'); ?>">Dịch theo ngôn ngữ</a></span></strong></p>
                         <div class="detail">    
     					<div class="first-content">                    	
                 <?php query_posts("showposts=6&cat=43");?>
				<?php $p=0;?>
		
				<?php while (have_posts()) : the_post(); ?>
				<?php $p++;$width=170;$height=93;
				if($p<2){
				$excerpt = cut_string(get_the_excerpt(),200,'[...]');
				?>
				<div class="images">
				
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>
				
				</div><div class="text">
                                <h2>	<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>
                                    <p><?php echo $excerpt; ?></p>
                                     <a rel="nofollow" class="more" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php _e('Xem chi tiết','themetiger');?> >></a>
                                </div>
				
                </div>
                <div class="category">
                <ul class="main">
				<?php } else { ?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></li>
				<?php }?>
				<?php endwhile; ?>
                    </ul>
                    </div>               
			 </div>
        <!-- END list-services -->       
                           
                     
                </div><!-- end .category-page -->
            </div><!-- end .mright -->
        	</div>       
                 
                </div><!-- end .category-page -->
            </div><!-- end .mright -->
        	</div>
        </div><!-- end .mainbody -->
        
     </div>
<?php get_footer(); ?>