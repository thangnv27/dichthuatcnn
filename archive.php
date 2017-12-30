<?php get_header(); ?>

<?php

/*
if ( is_category('dich-thuat') ) {
 include(TEMPLATEPATH . '/loop.php');
}
else {
 include(TEMPLATEPATH . '/archive_news.php');
}
*/
?> 

		<?php 
		
		if (is_category(4) ) {
include(TEMPLATEPATH . '/loop.php');
} 
else {
 include(TEMPLATEPATH . '/archive_news.php');
}
		 ?>

<?php get_footer(); ?>
