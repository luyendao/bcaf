<?php

$thumbnail = _awardee_thumbnail($aid, $term_cat_slug);

$title = get_the_title($aid);
$link = get_the_permalink($aid);
$city = get_field('city', $aid);
$status_label = '';

// If AOD 
if ( get_field('award_of_distinction', $aid)):
    $column_thumbnail = 'three';
    $column_class = "awardee-aod";
else:
    $column_thumbnail = 'four';	
    $column_class ='';
endif;


// If outstanding business achievement is TRUE
if ( get_field('status', $aid) == 'Outstanding Business Achiever' && !get_field('award_of_distinction', $aid)):
    
    $status_label = 'Outstanding Business Achievement';

elseif(get_field('award_of_distinction', $aid)):

	$status_label = sprintf('%s & %s', 'Award of Distinction', get_field('status', $aid));
else:

	$status_label = '';

endif;


return sprintf('

<li class="rows awardee-iba %s">
	<div class="columns %s"><a href="%s" title="%s">%s</a></div>
		<div class="columns seven"><a href="%s" title="%s">%s &raquo;</a>
			<div class="awardee-city">%s</div>
			<div class="awardee-status"><em>%s</em>
		</div>
	</div>
</li>', 

$column_class,$column_thumbnail, $link, $title, $thumbnail, $link, $title, $title, $city, $status_label );
