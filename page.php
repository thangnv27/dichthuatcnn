<?php get_header(); ?>



<?php if (is_page('250')){ ?>



<div id="main dangkytuyendung250">

	<div class="wrapper">

        <div class="formtuyendung clearfix">

                    <div class="breadcrumbs">

                        <ul class="main">

							<?php if(function_exists('bcn_display'))

                            {

                                bcn_display();

                            }?>

                        </ul>

                    </div><!-- end .breadcrumbs -->

                    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

                	<div class="form-new">

                    	<h1 class="title" title="<?php the_title(); ?>"><strong><span><?php the_title(); ?></span></strong></h1>

                        <div class="content">

                        	<?php the_content(); ?>

                        </div>

							<?php  edit_post_link(__('Edit','themetiger'),'','.'); ?>

                    </div><!-- end .form-new -->

					<?php endwhile; // end of the loop. ?>

        </div><!-- end .formtuyendung -->

    </div>

</div><!-- end #main -->

<?php } else { ?>

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

                    	<h1 class="pagetitle" title="<?php the_title(); ?>"><p class="title"><strong><span><?php the_title(); ?></span></strong></p></h1>

                        <div class="content">

                        	<?php the_content(); ?>

                        </div>

                        

                        <div class="share">

                        </div><!-- end .share -->

                       

	

							<?php  edit_post_link(__('Edit','themetiger'),'','.'); ?>

	

													</div><!--/post-34-->

													

													<?php endwhile; // end of the loop. ?>

                        

                        

                    </div><!-- end .list -->

                </div><!-- end .category-page -->

            </div><!-- end .mright -->

        	</div>

        </div><!-- end .mainbody -->

        





                 <?php } ?>

      

<?php get_footer(); ?>