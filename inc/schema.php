<?php

add_shortcode( 'awardee_schema','_awardee_schema');

function _awardee_schema() {

if (is_singular('awardee')):

// Get Awardee Category Slug and Name
$terms_award_cat = get_the_terms( $post->ID, 'award_categories' );
	foreach($terms_award_cat as $term) {
          $term_cat_id = $term->term_id;
          $term_cat_slug = $term->slug;
	}

// Common Schema Definitions
$name = get_the_title();
$url = get_the_permalink();
$image = get_the_post_thumbnail_url(get_the_ID());
$fb = get_field('facebook_link');
$twitter = get_field('twitter_link');
$youtube = get_field('youtube_link');
$instagram = get_field('instagram_link');
$linkedin = get_field('linked_link');

// Generate comma separated list
$social_media_links_arr = array($fb,$twitter,$youtube,$instagram,$linkedin);
$social_media_links = array_filter($social_media_links_arr);
$links = implode(',', $social_media_links);

if ( get_field('art_speciality') ) {
	$job = $term_cat_slug;//get_field('art_speciality');
}



// Taxonomy Specific Schema Definitions

// For IBA profiles, treat these as local businesses
if ( $term_cat_slug == 'bc-indigenous-business-award') {

$phone = get_field('business_phone_number');
$address = get_field('business_address');
$city = get_field('business_city');
$postal = get_field('business_postal');
$url = get_field('awardee_website');

// Local Business Schema

$schema = sprintf('
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "%s",
  "image": "%s",
  "@id": "",
  "url": "%s",
  "telephone": "%s",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "%s",
    "addressLocality": "%s",
    "postalCode": "%s",
    "addressCountry": "Canada",
    "addressRegion": "BC"
  },
	  "sameAs": [
		  %s
  ]
}
</script>',$name,$image,$url,$phone,$address,$city,$postal, $links);	

} else {

// Person Schema
$schema = sprintf('
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Person",
  "name": "%s",
  "url": "%s",
  "image": "%s",
  "jobTitle": "%s",
  "sameAs": [
	  %s
  ]
}
</script>', $name, $url,$image,$job, $links );

}

return $schema;

endif;

}
