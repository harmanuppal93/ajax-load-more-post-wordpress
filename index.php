<?php

/* add code in functions.php or plugin file */

add_action( 'wp_ajax_custom_load_more_posts', 'custom_load_more_posts' );
add_action( 'wp_ajax_nopriv_custom_load_more_posts', 'custom_load_more_posts' );

function custom_load_more_posts() {
	
	/* if(isset($_REQUEST['category']) && $_REQUEST['category'] != ''){
		$args =  array( 'post_type' => 'post','posts_per_page' => $_REQUEST['limit'],'offset' => $_REQUEST['offset'] ,'tax_query' => array( array('taxonomy' => 'magazine_category','field' => 'term_id','terms' => $_REQUEST['category'], 'include_children' => false  )));
	}else{ */
		$args = array('post_type' => 'post','posts_per_page' => $_REQUEST['limit'],'offset' => $_REQUEST['offset']	);
	/* } */
	
	
	$query = new WP_Query($args);	
	if ( $query->have_posts() ){  
		while ( $query->have_posts() ) : $query->the_post();
			$post_id = get_the_ID();	
			
			endwhile;

		wp_reset_postdata(); 
	}		

	
	die();
}


/* add this code in page template where you want to load posts */


echo '<div class="reviews-inner-section"></div><div class="load-more-append" style="display:none;"></div>';


/* Add this code in footer.php */
?>
<script>

var end_found = false;
function load_reviews_posts_data(offset , limit )
{		
	jQuery.ajax({
		url:  '<?php echo admin_url('admin-ajax.php'); ?>',
		data: {'action':'custom_load_more_posts','offset': offset , 'limit':limit }, 
		type: 'GET',						
		success: function (data) {					
			if(data == ""){	end_found =  true; }		
			
			jQuery(".reviews-inner-section").append(data);				
		}
	});			
	
}
jQuery(document).ready(function($){
		
		var offset = 0; 
		var limit = 6; /* Change it accordingly */
					
		var t_div = $('.load-more-append');		
		var loading = false;
		var scrollHandling = {allow: true,reallow: function() {	scrollHandling.allow = true; },	delay: 400	};
		
		$(window).scroll(function(){
			if( ! loading && scrollHandling.allow ) {
				
				scrollHandling.allow = false;
				setTimeout(scrollHandling.reallow, scrollHandling.delay);
				var offset1 = $(t_div).offset().top - $(window).scrollTop();
				if( 2000 > offset1  ) {
					loading = true;
					
					setTimeout(function(){												
						if(!end_found){
							offset = offset + limit;
							load_reviews_posts_data(offset , limit );							
						}
						loading = false;					
					}, 1000);					
				}
			}
		});	
	});
	

</script>
			
