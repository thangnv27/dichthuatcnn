<?php get_header(); ?>

<div style="position:absolute;left:-9999px" title="Công ty dịch thuật, Dịch thuật"><h1 title="phiên dịch"><a href="http://cnntranslation.com/" title="Công ty dịch thuật, Dịch thuật">Công ty dịch thuật, Dịch thuật</a></h1></div>

<div id="main">

    	<div class="services clearfix">

        	<div class="wrapper">

            	<ul class="service">

                	<li>

<a href="http://cnntranslation.com/dich-vu-phien-dich.htm" title="dịch vụ phiên dịch">



                    <p class="images"><img src="<?php bloginfo('template_url'); ?>/images/img_sv1.jpg" /></p>

                    

</a>



<p class="title"><span><a href="http://cnntranslation.com/" title="phiên dịch">Phiên dịch</a></span></p>

                    </li>

                    <li>



<a href="http://cnntranslation.com/dich-vu-dich-thuat.htm" title="dịch vụ dịch thuật">



                    <p class="images"><img src="<?php bloginfo('template_url'); ?>/images/img_sv2.jpg" /></p>

</a>

                    <p class="title" title="dịch thuật"><span><a href="http://cnntranslation.com/" title="dịch thuật">Dịch thuật</a></span></p>

                    </li>

                    <li>

<a href="http://cnntranslation.com/dich-vu-dich-thuat-htm/cnn-studio/thu-am-long-tieng" title="thu âm lồng tiếng">

                    <p class="images"><img src="<?php bloginfo('template_url'); ?>/images/img_sv3.jpg" /></p>

</a>

                    <p class="title"><span><a href="http://cnntranslation.com/dich-vu-dich-thuat-htm/cnn-studio/thu-am-long-tieng" title="dịch vụ thu âm lồng tiếng chèn phụ đề">Thu âm lồng tiếng chèn phụ đề</a></span></p>

                    </li>

                    <li class="last">

<a href="http://cnntranslation.com/lien-he">

                    <p class="images"><img src="<?php bloginfo('template_url'); ?>/images/img_sv4.jpg" /></p>

</a>

                    <p class="title"><span><a href="http://cnntranslation.com/lien-he" title="báo giá dịch thuật">Báo giá dịch vụ</a></span></p>

                    </li>

                </ul>

            </div>

        </div><!-- end .services -->

        <div class="status clearfix">

        	<div class="wrapper">

                <div class="content">

                <div class="images">

                    <img src="<?php bloginfo('template_url'); ?>/images/status_img.png" />

                </div><!-- end .images -->

                <div class="info">

                    <p class="title"><span>Welcome!</span>GIỚI THIỆU DỊCH THUẬT CNN</p>

                    <p class="text">

                    <?php if ( get_theme_option( "about" )){?>

					<?php echo get_theme_option( "about" );?><?php } ?>

                    

                   </p>

                </div><!-- end .info -->

                </div>

            </div>

        </div><!-- end .status -->

        <div class="mainbody clearfix">

        	<div class="wrapper">

        	<div class="mleft">

            	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>

				<?php endif; ?>

              

            </div><!-- end .mleft -->

            <div class="mright">

            	<div class="home-news" style="margin-bottom:10px;">

                	<ul class="main">

                    	<li class="sv1">

                        	<h3 class="title" title="Dịch vụ Phiên dịch"><strong><span><a href="http://cnntranslation.com/dich-vu-phien-dich.htm">Dịch vụ Phiên dịch</a></span></strong></h3>

                            <div class="content">

<?php query_posts("showposts=5&cat=6");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>

				<p>

			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>

				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else {  ?>

				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></li>

				<?php }?>

				<?php endwhile; ?>

                    </ul>           

                <a rel="nofollow" href="<?php echo get_category_link('6'); ?>" class="more">Xem tất cả >></a>



                            </div>

                            

                        </li>

                        <li class="sv2 last">

                        	<h3 class="title" title="Dịch vụ dịch thuật"><strong><span><a href="http://cnntranslation.com/dich-vu-dich-thuat.htm">Dịch vụ Dịch thuật</a></span></strong></h3>

                            <div class="content">

                            	<?php query_posts("showposts=5&cat=5");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></h2>

				<p>

			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>

				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else { ?>

				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></li>

				<?php }?>

				<?php endwhile; ?>

                    </ul>           

                <a rel="nofollow" href="<?php echo get_category_link('5'); ?>" class="more">Xem tất cả >></a>

                            </div>

                        </li>

                        <li class="sv3">

                        	<h3 class="title"><strong><span><a  href="<?php echo get_category_link('30'); ?>" >Thu âm lồng tiếng chèn phụ đề</a></span></strong></h3>

                            <div class="content">

                            	<?php query_posts("showposts=5&cat=30");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>

				<p>

			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>

				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else { ?>

				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></li>

				<?php }?>

				<?php endwhile; ?>

                    </ul>           

                <a rel="nofollow" href="<?php echo get_category_link('30'); ?>" class="more">Xem tất cả >></a>

                            </div>

                        </li>

                        <li class="sv4 last">
                        	<h3 class="title"><strong><span><a href="http://cnntranslation.com/bao-gia-khac" >Báo giá dịch thuật</a></span></strong></h3>

                            <div class="content">

                            		<?php query_posts("showposts=5&cat=295");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="http://cnntranslation.com/bao-gia-dich-thuat" rel="bookmark" title="báo giá khác">Báo giá dịch thuật</a></h2>

				<p>

			<a href="http://cnntranslation.com/lien-he" rel="bookmark" title="Liên hệ với chúng tôi"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>
				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else { ?>

				<li><a href="http://cnntranslation.com/bao-gia-phien-dich" rel="bookmark" title="báo giá phiên dịch">Xem ngay Báo giá</a></li>
<?php }?>

				<?php endwhile; ?>

                    </ul>       
				    

                <a rel="nofollow" href="<?php echo get_category_link('34'); ?>" class="more">Xem tất cả >></a>

                            </div>

                        </li>

						
		

						<li class="sv3">

                        	<h3 class="title"><strong><span><a  href="<?php echo get_category_link('9'); ?>" >Dự án của chúng tôi</a></span></strong></h3>

                            <div class="content">

                            	<?php query_posts("showposts=5&cat=9");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>

				<p>

			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>

				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else { ?>

				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></li>

				<?php }?>

				<?php endwhile; ?>

                    </ul>           

                <a rel="nofollow" href="<?php echo get_category_link('9'); ?>" class="more">Xem tất cả >></a>

                            </div>

                        </li>

                        <li class="sv4 last">

                        	<h3 class="title" title="Phiên dịch theo ngôn ngữ"><strong><span><a  href="<?php echo get_category_link('43'); ?>" >Phiên dịch theo ngôn ngữ</a></span></strong></h3>

                            <div class="content">

                            		<?php query_posts("showposts=5&cat=43");?>

				<?php $p=0;?>

				<ul class="simlist">

				<?php while (have_posts()) : the_post(); ?>

				<?php $p++;

				if($p<2){

				$excerpt = cut_string(get_the_excerpt(),320,'...');

				$width=120;$height=90;

				?>

				<div class="featured">

				<h2><a  class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h2>

				<p>

			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><img src="<?php bloginfo('template_url');?>/thumb.php?src=<?php echo show_thumb_image() ?>&w=<?php echo $width;?>&h=<?php echo $height;?>&zc=1&q=95" alt="<?php the_title_attribute(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" class="post_thumbnail alignleft" /></a>

				<?php echo $excerpt; ?>

                </p>

				<div class="clearfix"></div>

				</div>

                <ul class="child">

				<?php } else { ?>

				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php  $title = cut_string(get_the_title(),60,'...'); echo $title; ?></a></li>

				<?php }?>

				<?php endwhile; ?>

                    </ul>           

                <a rel="nofollow" href="<?php echo get_category_link('43'); ?>" class="more">Xem tất cả >></a>

                            </div>

                        </li>

                        	
						

                    </ul>

                </div><!-- end .home-news -->

            </div><!-- end .mright -->

        	</div>

        </div><!-- end .mainbody -->

        <div class="box-bottom clearfix">

        	<div class="wrapper">

           

            </div>

            </div>

        </div><!-- end .box-bottom -->

<?php get_footer(); ?>
