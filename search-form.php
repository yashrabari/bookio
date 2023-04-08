<?php 
	$query_string = bookio_get_query_string();
	parse_str($query_string, $params);
	$category_slug = isset( $params['product_cat'] ) ? $params['product_cat'] : '';
	$terms =	get_terms( 'product_cat', 
	array(  
		'hide_empty' => true,	
		'parent' => 0	
	));
	$class_ajax_search 	= "";	 
	$ajax_search 		= bookio_get_config('show-ajax-search',false);
	$limit_ajax_search 		= bookio_get_config('limit-ajax-search',5);
	if($ajax_search){
		$class_ajax_search = "ajax-search";
	}
?>
<form role="search" method="get" class="search-from <?php echo esc_attr($class_ajax_search); ?>" action="<?php echo esc_url(home_url( '/' )); ?>" data-admin="<?php echo admin_url( 'admin-ajax.php', 'bookio' ); ?>" data-noresult="<?php echo esc_html__("No Result","bookio") ; ?>" data-limit="<?php echo esc_attr($limit_ajax_search); ?>">
	<?php if($terms){ ?>
	<div class="select_category pwb-dropdown dropdown">
		<span class="pwb-dropdown-toggle dropdown-toggle" data-toggle="dropdown"><?php echo esc_html__("Category","bookio"); ?></span>
		<span class="caret"></span>
		<ul class="pwb-dropdown-menu dropdown-menu category-search">
		<li data-value="" class="<?php  echo (empty($category_slug) ?  esc_attr("active") : ""); ?>"><?php echo esc_html__("All Category","bookio"); ?></li>
			<?php foreach($terms as $term){?>
				<li data-value="<?php echo esc_attr($term->slug); ?>" class="<?php  echo (($term->slug == $category_slug) ?  esc_attr("active") : ""); ?>"><?php echo esc_html($term->name); ?></li>
				<?php
					$terms_vl1 =	get_terms( 'product_cat', 
					array( 
							'parent' => '', 
							'hide_empty' => false,
							'parent' 		=> $term->term_id, 
					));						
				?>	
				
				<?php foreach ($terms_vl1 as $term_vl1) { ?>
					<li data-value="<?php echo esc_attr($term_vl1->slug); ?>" class="children <?php  echo (($term_vl1->slug == $category_slug) ?  esc_attr("active") : ""); ?>"><?php echo esc_html($term_vl1->name); ?></li>
					<?php
						$terms_vl2 =	get_terms( 'product_cat', 
						array( 
								'parent' => '', 
								'hide_empty' => false,
								'parent' 		=> $term_vl1->term_id, 
					));	?>					
					<?php foreach ($terms_vl2 as $term_vl2) { ?>
					<li data-value="<?php echo esc_attr($term_vl2->slug); ?>" class="children <?php  echo (($term_vl2->slug == $category_slug) ?  esc_attr("active") : ""); ?>"><?php echo esc_html($term_vl2->name); ?></li>
					<?php } ?>
				<?php } ?>
				
			<?php } ?>
		</ul>	
		<input type="hidden" name="product_cat" class="product-cat" value="<?php echo esc_attr($category_slug); ?>"/>
	</div>	
	<?php } ?>
	<div class="search-box">
		<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="ss" autocomplete="off" class="input-search s" placeholder="<?php echo esc_attr__( 'Search product...', 'bookio' ); ?>" />
		<div class="result-search-products-content">
			<ul class="result-search-products">
			</ul>
		</div>
	</div>
	<input type="hidden" name="post_type" value="product" />
	<button id="searchsubmit2" class="btn" type="submit">
		<span class="search-icon">
			<i class="icon-loupe"></i>
		</span>
		<span><?php echo esc_html__("search","bookio"); ?></span>
	</button>
</form>