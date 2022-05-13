<?php

$thumbnail = _awardee_thumbnail($aid, $term_cat_slug);

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

	<div class="awardee-iba-card">
	<a href="%s" title="%s" class="imageref">%s</a>
		<a href="%s" title="%s"><h3 class="awardee-title">%s</h3></a>
			<h2 class="iba-award-header">%s</h2>
			<div class="awardee-status"><em>%s</em></div>
	</div>
		',  $link, $title, $thumbnail, $link, $title, $title, $header, $status_label );
