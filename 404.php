<?php 
	get_header(); 
	$bookio_settings = bookio_global_settings();
?>
<div class="page-404">
	<div class="content-page-404">
		<div class="title-error">
			<?php if(isset($bookio_settings['title-error']) && $bookio_settings['title-error']){
				echo esc_html($bookio_settings['title-error']);
			}else{
				echo esc_html__("404", "bookio");
			}?>
		</div>
		<div class="sub-title">
			<?php if(isset($bookio_settings['sub-title']) && $bookio_settings['sub-title']){
				echo esc_html($bookio_settings['sub-title']);
			}else{
				echo esc_html__("Oops! That page can't be found.", "bookio");
			}?>
		</div>
		<div class="sub-error">
			<?php if(isset($bookio_settings['sub-error']) && $bookio_settings['sub-error']){
				echo esc_html($bookio_settings['sub-error']);
			}else{
				echo esc_html__("We're really sorry but we can't seem to find the page you were looking for.", "bookio");
			}?>
		</div>
		<a class="btn" href="<?php echo esc_url( home_url('/') ); ?>">
			<?php if(isset($bookio_settings['btn-error']) && $bookio_settings['btn-error']){
				echo esc_html($bookio_settings['btn-error']);}
			else{
				echo esc_html__("Back The Homepage", "bookio");
			}?>
		</a>
	</div>
</div>
<?php
get_footer();