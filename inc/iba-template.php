<?php

//$thumbnail = _awardee_thumbnail($aid, $term_cat_slug);
$featured_img_url = get_the_post_thumbnail_url($aid); 

//Usage: aq_resize( $url, $width, $height, $crop, $single, $upscale ) 

if($featured_img_url) {
	$thumbnail = aq_resize( $featured_img_url, 240, 240, true, true, true);
	$thumbnail_tag = sprintf('<img src="%s" alt="Image" />', $thumbnail); 
} else {
	$thumbnail_tag = '<img src="https://www.bcachievement.com/wp-content/uploads/2019/05/BCAF-Logo_Solid-White.png" class="awardee-placeholder" />';
}

$title = get_the_title($aid);
$link = get_the_permalink($aid);
$city = get_field('city', $aid);
$status_label = '';

/*
if ( get_field('award_of_distinction', $aid)):
    $column_thumbnail = 'three';
    $column_class = "awardee-aod";
else:
    $column_thumbnail = 'four';	
    $column_class ='';
endif;
 */

// If outstanding business achievement is TRUE
if ( get_field('status', $aid) == 'Outstanding Business Achiever' && !get_field('award_of_distinction', $aid)):
    
    //$status_label = 'Outstanding Business Achievement';

elseif(get_field('award_of_distinction', $aid)):

	$status_label = sprintf('%s & %s', 'Award of Distinction', get_field('status', $aid));
else:

	$status_label = '';

endif;


return sprintf('

	<div class="columns four awardee-iba-card">
	<a href="%s" title="%s" class="imageref">%s</a>
		<a href="%s" title="%s"><h3 class="awardee-title">%s</h3></a>
			<h2 class="iba-award-header">%s</h2>
	</div>
		',  $link, $title, $thumbnail_tag, $link, $title, $title, $header );
