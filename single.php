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

                    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

                	<div class="detail-services">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td height="30"><h1 title="<?php the_title(); ?>"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><font color="#333333"><?php the_title(); ?></font></a></h1></td>
					  </tr>
					</table>

						<p></p>

                        <div class="content">

                        	<?php the_content(); ?>

                        </div>
<br>
<hr>   

<div class="baogia" style="width:100%; float:left; margin:10px 0;">                     

                        <?php 
		if (in_category(4) ) { ?>
<a class="baogia" style="background:#F00; border:0; border-radius:10px; padding:6px 10px; color:#FFF; text-align:center" href="<?php bloginfo('url'); ?>/bao-gia-truc-tuyen/"> Báo giá trực tuyến</a>

<?php

}

		 ?>

                        

          

                        </div>

                        

                        <div class="tags">

                            <p><span>Tags :</span> <?php the_tags('', ', ', ''); ?>  <b>  | <?php _e('Chuyên mục : ','themetiger');?> </b><?php echo (get_the_category_list( ', ' ) ); ?> 

							</p>

                        </div><!-- end .tags -->

                        

                     

                        <div class="share">

                        </div><!-- end .share -->

                       

	

							<?php  edit_post_link(__('Edit','themetiger'),'','.'); ?>

	

													</div><!--/post-34-->

													

		<div class="baivietlienquan">

        											<?php endwhile; // end of the loop. ?>

                                                    <?php

        

            $socialicons_folder = get_bloginfo('template_url').'/images/socialicons';

            ?>
                        <?php

$categories = get_the_category($post->ID);

if ($categories)

{

$category_ids = array();

foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;



$args=array(

'category__in' => $category_ids,

'post__not_in' => array($post->ID),

'showposts'=>5, // Số bài viết bạn muốn hiển thị.

'caller_get_posts'=>1

);

$my_query = new wp_query($args);

if( $my_query->have_posts() )

{

echo '<h3>DỊCH VỤ KHÁC</h3><ul>';

while ($my_query->have_posts())

{

$my_query->the_post();

?>

<li><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>

<?php

}

echo '</ul>';

}

}

wp_reset_query();

?> 

</div>

                        <div class="comment">

                        	<h3 class="title"><hr></h3>

                            <?php comments_template(); ?>

                        </div><!-- end .comment -->

                    </div><!-- end .list -->

                    

                </div><!-- end .category-page -->

                

            </div><!-- end .mright -->

        	</div>

        </div><!-- end .mainbody -->   


<?php get_footer(); ?>