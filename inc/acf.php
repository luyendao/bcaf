<?php
/**
 * @package bcaf
 */


// ADD SHORTCODES
add_shortcode( 'past_programs', '_past_programs' );
add_shortcode( 'award_sponsors', '_award_sponsors' );
add_shortcode( 'awardees', '_awardees_by_taxonomy' );

add_shortcode( 'awardees_banner', '_awardees_banner' );
add_shortcode( 'awardees_aod', '_awardee_aod' );

function _awardee_aod($atts) {

    if (is_singular('awardee')) {


        // Default recipient title
        $title = "Recipient";

        if (get_field('custom_recipient_title') && $current_term_id !== 5) {
            $title = get_field('custom_recipient_title');
        }

        // Get current term ID
        $term_list = wp_get_post_terms( get_the_ID(), 'award_categories', array( 'fields' => 'ids' ) );
        $current_term_id = $term_list[0];


        if (get_field('award_of_distinction') == 1) {
            $title = sprintf('<div class="aod awardee-meta-color-fill elementor-post-info__terms-list-item">Award of Distinction</div>');
        }

        if (get_field('crabtree_mclennan_emerging_artist_award') == 1) {
            $title = sprintf('<div class="crabtree">Crabtree McLennan Emerging Artist Award</div>');
        }              

        // IBA ONLY

        if ( $current_term_id == 5 ) {

            if (get_field('status') && !have_rows('multiple_status')) {
                $title = sprintf('<div class="awardee-iba-status">%s</div>', get_field('status')); 
            }

            if( have_rows('multiple_status') ):

                // Loop through rows.
                while( have_rows('multiple_status') ) : the_row();

                    // Load sub field value.
                    $ms_year = get_sub_field('multiple_status_year');
                    $ms_status = get_sub_field('multiple_status_selection');
                
                    // Match Repeaster Year value with Awardee Category Year value
                    $title .= sprintf('<div class="awardee-iba-status" style="font-size: 15px;">%s (%s)</div>', $ms_status, $ms_year ); 
                    
                // End loop.
                endwhile;

            endif;

        }




        //var_dump(get_field('multiple_status'));

        $out = sprintf('%s', $title);

        return $out;

    }

}


function _awardees_banner($atts) {
    if (get_field('show_awardees_honoured_banner') && is_singular('award')) {

            $copy = get_field('awardees_honoured_banner_copy');

            // Generate copy if no custom copy entered
            if ( empty( $copy ) == TRUE ) {

                $term_list = wp_get_post_terms( get_the_ID(), 'award_categories', array( 'fields' => 'ids' ) );
                $current_term_id = $term_list[0];
            
                // award_year term ID
                $terms_award_year = get_the_terms( get_the_ID(), 'award_year' ); 

               // Get Award Year Slug and Name
                foreach($terms_award_year as $term) {
                  //$term_year_id = $term->term_id;
                  //$term_year_slug = $term->slug;
                  $term_year_name = $term->name;
                }

                // COM
                if ($current_term_id == 2) {
                    $copy = sprintf('%s Community Award', $term_year_name);
                }
                // AAD
                if ($current_term_id == 3) {
                    $copy = sprintf('%s Applied Art + Design Award', $term_year_name);
                }   
                if ($current_term_id == 4) {
                    $copy = sprintf('%s First Nations Art Award', $term_year_name);
                }    
                if ($current_term_id == 5) {
                    $copy = sprintf('%s Indigenous Business Award', $term_year_name);
                }                         
            }

        $out = sprintf('<div class="awardees-banner"><span>%s</span></div>', $copy);

        return $out;


   } else {
        return;
   }
}



/* FUNCTION: LIST PAST PROGRAMS QUERY */

function _past_programs($atts){


    $current_taxonomy = '';

    if (is_singular('award')) {
        $term_list = wp_get_post_terms( get_the_ID(), 'award_categories', array( 'fields' => 'ids' ) );
        $current_term_id = $term_list[0];
    }

        $past_programs = get_posts(array(
            'post_type'      => 'award',
            'posts_per_page' => -1,
            'orderby'        => 'publish_date',
            'fields'         => 'ids',
            'post_status'  => 'publish',

            'tax_query' => array(
                    array(
                        'taxonomy' => 'award_categories',
                        'field'    => 'term_id',
                        'terms'    =>  $current_term_id,
                    ),
                ),
        )); 

        foreach ($past_programs as $pid) {

            $list .= sprintf('<li><a href="%s">%s</a></li>', get_the_permalink($pid), get_the_title($pid));
        }

        $markup = sprintf('<strong>Past Programs</h4><ul>%s</strong>', $list );

        return $markup;

}





/* FUNCTION: LIST SPONSORS BY ACF */

function _award_sponsors($atts) {

if ( get_field('display_sponsors')) {


 // award_year term ID
        $terms_award_year = get_the_terms( $post->ID, 'award_year' ); 

        // Get Award Category Slug and Name
        $terms_award_cat = get_the_terms( $post->ID, 'award_categories' ); 
        foreach($terms_award_cat as $term) {
          $term_cat_id = $term->term_id;
          //$term_cat_slug = $term->slug;
        }


        // Default Sponsor Levels 
        
		if (get_field('sponsors_section_header')) {
			$sponsors_section_header = get_field('sponsors_section_header');
		} else {
			$sponsors_section_header = "With thanks to our program partners";	// Default
		}


        // If IBA 
        if ( $term_cat_id === 5 ) {
            $govt_header = sprintf('<div class="row sponsors"><a href="%s"><img src="%s" alt="BC Government" style="width: 200px;" /></a></div>',  'https://www2.gov.bc.ca/gov/content/governments/organizational-structure/ministries-organizations/ministries/indigenous-relations-reconciliation', 'https://www.bcachievement.com/wp-content/uploads/2020/05/Logos_0031_BC-Government.jpg');
        } else {
            $govt_header ='';
        }
        $supporting_orgs = "Organizations";
        $sponsor_header = "Sponsors";
        $strategic_header = "Strategic Partners";
        $event_header = "Event Partners";
        $donor_header = "Donors";
        $supporting_header = "Organizations";


        // Override IBA Sponsor Headers
        if (get_field('override_sponsor_level_labels') == 1) {
            $is_iba = TRUE;

            if (get_field('rename_sponsor_header')) {
                $sponsor_header = get_field('rename_sponsor_header');
            }

            if (get_field('rename_strategic_partner_header')) {
                $strategic_header = get_field('rename_strategic_partner_header');
            }

            if (get_field('rename_event_partner_header')) {
                $event_header = get_field('rename_event_partner_header');
            }

            if (get_field('rename_donor_header')) {
                $donor_header = get_field('rename_donor_header');
            }
                                                
        }  


    $out = sprintf('<div class="row sponsors" style="margin-top: 30px;"><h2 style="color:#000;">%s</h2><hr style="background-color:#00000040; margin-bottom: 15px;" /></div>', $sponsors_section_header);

    $out .= $govt_header;


    if ( get_field('supporting_organizations')) {


        $supporting = get_field('supporting_organizations');


        // Returns sponsor post ID
        foreach( $supporting as $sid ): 

            $logo = get_field('sponsor_logo', $sid);
            $url = get_field('sponsor_website_url', $sid);
            if (empty($url)) {
                $url = 'javascript:;';
            }
            $alt_title = get_field(get_the_title($sid));

            $supporting_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
    
        endforeach;  

         $out .= sprintf('<div class="row sponsors supporting-organizations"><h4>%s</h4><hr style="background-color:#00000040; margin-bottom: 15px;" />%s</div>', $supporting_orgs, $supporting_markup);

        
    }  


    if ( get_field('partner_type_sponsor')) {

      
        $sponsors = get_field('partner_type_sponsor');

        // Returns sponsor post ID
        foreach( $sponsors as $sid ): 

            $logo = get_field('sponsor_logo', $sid);
            $url = get_field('sponsor_website_url', $sid);
            if (empty($url)) {
                $url = 'javascript:;';
            }
            $alt_title = get_field(get_the_title($sid));

            $sponsors_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
    
        endforeach;        



        // Output entire markup
        $out .= sprintf('<div class="row sponsors partner-sponsor"><h4>%s</h4><hr style="background-color:#00000040; margin-bottom: 15px;" />%s</div>', $sponsor_header, $sponsors_markup);


     

    }


    if ( get_field('partner_type_strategic_partner')) {
        
        $strategic_partners = get_field('partner_type_strategic_partner');

        // Returns sponsor post ID
        foreach( $strategic_partners as $sid ): 

            $logo = get_field('sponsor_logo', $sid);
            $url = get_field('sponsor_website_url', $sid);
            if (empty($url)) {
                $url = 'javascript:;';
            }
            $alt_title = get_field(get_the_title($sid));

            $strategic_partners_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
    
        endforeach;  

        $out .= sprintf('<div class="row sponsors partner-strategic-sponsor"><h4>%s</h4><hr style="background-color:#00000040; margin-bottom: 15px;" />%s</div>', $strategic_header, $strategic_partners_markup);

    }

    if ( get_field('partner_type_event_partner')) {
        
        $event_partners = get_field('partner_type_event_partner');



        // Returns sponsor post ID
        foreach( $event_partners as $sid ): 

            $logo = get_field('sponsor_logo', $sid);
            $url = get_field('sponsor_website_url', $sid);
            if (empty($url)) {
                $url = 'javascript:;';
            }
            $alt_title = get_field(get_the_title($sid));

            $event_partners_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
    
        endforeach;  

         $out .= sprintf('<div class="row sponsors partner-event"><h4>%s</h4><hr style="background-color:#00000040; margin-bottom: 15px;" />%s</div>', $event_header, $event_partners_markup);


    }

    if ( get_field('partner_type_donor')) {


        $donors = get_field('partner_type_donor');


        // Returns sponsor post ID
        foreach( $donors as $sid ): 

            $logo = get_field('sponsor_logo', $sid);
            $url = get_field('sponsor_website_url', $sid);
            if (empty($url)) {
                $url = 'javascript:;';
            }
            $alt_title = get_field(get_the_title($sid));

            $donors_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
    
        endforeach;  

         $out .= sprintf('<div class="row sponsors partner-donor"><h4>%s</h4><hr style="background-color:#00000040; margin-bottom: 15px;" />%s</div>', $donor_header, $donors_markup);

        
    }        


  

       // if ( get_the_ID() == 6177 ) {
         //   var_dump("This is: 6177");
          //  var_dump( $out);
        //}   

    return $out;
    
    }

}



function _awardee_thumbnail($id, $term_cat_slug) {

    if ( get_the_post_thumbnail($id)) {

        if (get_field('award_of_distinction', $id) || $is_aod) {
            $size = 'medium';
        } else {
            $size = 'thumbnail';
        }

        $thumbnail = get_the_post_thumbnail($id , $size);     
    

    }  else {
        // Concantenate a bunch of things to pull placeholder based on taxonomy
        $thumbnail = sprintf('<img src="%s/images/awardee-placeholder-%s.jpg" alt="Placeholder" />', get_template_directory_uri(), $term_cat_slug);
    }

    return $thumbnail;
}



function _award_year($term_id=false, $term_slug=false, $term_name=false) {

    $terms_award_year = get_the_terms( get_the_ID(), 'award_year' ); 

   // Get Award Year Slug and Name
    foreach($terms_award_year as $term) {
      $term_year_id = $term->term_id;
      $term_year_slug = $term->slug;
      $term_year_name = $term->name;
    }

    if ($term_id) {
        return $term_year_id;
    }    
    elseif ($term_slug) {
        return $term_year_slug;
    }
    elseif ($term_name) {
        return $term_year_name;
    } else {
        return;
    }

}



/* FUNCTION: LIST AWARDEES BY TAXONOMY */

function _awardees_by_taxonomy( $atts ){

    // Display Awardees if boolean is TRUE or if user is logged in for debugging
    if ( is_user_logged_in() || !is_user_logged_in()):


        // award_year term ID
        $terms_award_year = get_the_terms( $post->ID, 'award_year' ); 

       // Get Award Year Slug and Name
        foreach($terms_award_year as $term) {
          $term_year_id = $term->term_id;
          $term_year_slug = $term->slug;
          $term_year_name = $term->name;
        }

        // Get Award Category Slug and Name
        $terms_award_cat = get_the_terms( $post->ID, 'award_categories' ); 
        foreach($terms_award_cat as $term) {
          $term_cat_id = $term->term_id;
          $term_cat_slug = $term->slug;
        }





        /********************************************************
        // IBA Conditional; No query is run here, use ACF fields
        ********************************************************/

        if ($term_cat_id === 5 && $term_cat_id  !== NULL ) {

                    $out = NULL;


                    // Young Entrepreneur of the Year
                    $yeoty = get_field('young_entrepreneur_of_the_year', $post->ID);
                    if ( $yeoty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $yeoty as $aid ):
                             $header = 'Young Entrepreneur of the Year';
                             $temp9.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp9);

                    endif; 

        /*** BUSINESS OF THE YEAR ****/


                    // Business of the Year - one to two person enterprise
                    $business_oty_two_person = get_field('business_of_the_year_-_one_to_two_person_enterprise', $post->ID);
                    if ( $business_oty_two_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_two_person as $aid ):
                             $header = 'Business of the Year – one to two person enterprise';
                             $temp4.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp4);

                    endif;                    


                    // Business of the Year - three to 10 person enterprise
                    $business_oty_three_ten_person = get_field('business_of_the_year_-_three_to_10_person_enterprise', $post->ID);
                    if ( $business_oty_three_ten_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_three_ten_person as $aid ):
                             $header = 'Business of the Year – three to 10 person enterprise';
                             $temp5.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp5);

                    endif;    


                    // Business of the Year – 10+ person enterprise
                    $business_oty_ten_plus_person = get_field('business_of_the_year_–_10+_person_enterprise', $post->ID);
                    if ( $business_oty_ten_plus_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_ten_plus_person as $aid ):
                             $header = 'Business of the Year – 10+ person enterprise';
                             $temp6.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp6);

                    endif; 
   
                    //Business of the Year – 11+ person enterprise
                    $business_oty_eleven_plus_person = get_field('business_of_the_year_–_11+_person_enterprise', $post->ID);
                    if ( $business_oty_eleven_plus_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_eleven_plus_person as $aid ):
                             $header = 'Business of the Year – 11+ person enterprise';
                             $temp7.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp7);  

                    endif; 


                    // Community-Owned Business of the Year
                    $coboty = get_field('community-owned_business_of_the_year', $post->ID);
                    if ( $coboty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty as $aid ):
                             $header = 'Community-Owned Business of the Year';
                             $temp8.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp8);

                    endif; 


        /*** COMMUNITY OWNED BUSINESS ****/

                    // Community-Owned Business of the Year – one entity
                    $coboty_one_entity = get_field('community-owned_business_of_the_year_–_one_entity', $post->ID);
                    if ( $coboty_one_entity ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty_one_entity as $aid ):
                             $header = 'Community-Owned Business of the Year – one entity';
                             $temp10.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp10);

                    endif; 


                    // Community-Owned – two or more entities
                    $coboty_two_more_entity = get_field('community-owned_–_two_or_more_entities', $post->ID);
                    if ( $coboty_two_more_entity ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty_two_more_entity as $aid ):
                             $header = 'Community-Owned Business of the Year – two or more entities';
                             $temp11.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp11);

                    endif; 


        /*** ABORIGINAL BUSINESSES ****/


                    // Aboriginal-Aboriginal Business Partnership of the Year
                    $abpy = get_field('aboriginal-aboriginal_business_partnership_of_the_year', $post->ID);
                    if ( $abpy ):
                        // Loop ACF relationship and return post ID
                        foreach ( $abpy as $aid ):
                            $header = 'Aboriginal-Aboriginal Business Partnership of the Year';
                            $temp1 .= include(locate_template('inc/iba-template.php', false, false));

                        endforeach;

                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp1);

                    endif;


                    // Aboriginal-Industry Business Partnership of the Year
                    $aibpy = get_field('aboriginal-industry_business_partnership_of_the_year', $post->ID);
                    if ( $aibpy ):
                        // Loop ACF relationship and return post ID
                        foreach ( $aibpy as $aid ):
                             $header = 'Aboriginal-Industry Business Partnership of the Year';
                             $temp2.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;
                       
                         $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp2);

                    endif;                    

                    // Business Partnership of the Year
                    $business_poty = get_field('business_partnership_of_the_year', $post->ID);
                    if ( $business_poty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_poty as $aid ):
                             $header = 'Business Partnership of the Year';
                             $temp3.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                         $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp3);

                    endif;                    


                    // Joint Venture Business Partnership of the Year
                    $jvbpoty = get_field('joint_venture_business_partnership_of_the_year', $post->ID);
                    if ( $jvbpoty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $jvbpoty as $aid ):
                             $header = 'Joint Venture Business Partnership of the Year';
                             $temp12.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $temp12);

                    endif; 


                    // IBA AOD
                    $aod = get_field('iba_award_of_distinction', $post->ID);
                    if ( $aod ):
                        // Loop ACF relationship and return post ID
                        foreach ( $aod as $aid ):
                            $header = 'Award of Distinction';
                            $is_aod = TRUE;
                            $aod_iba .= include(locate_template('inc/iba-template.php', false, false));

                        endforeach;

                        $out .= sprintf('<h3 class="iba-award-header">%s</h3>%s', $header, $aod_iba);

                    endif;


         // Return final markup for IBA Awardees
         return '<div class="program-awardees-list" style="margin-bottom:30px;float:left;"><ul>' . $out . '</ul></div>';                    



        } else {


        /********************************************************
        // COM, FNA and CW Conditional; Run query
        ********************************************************/            


            // Get all awardees 
            $value = get_posts(array(
                'post_type'      => 'awardee',
                'posts_per_page' => -1,
                'meta_key'   => 'last_name',
                'orderby'    => 'last_name',
                'order'          => 'ASC',
                'fields'         => 'ids',
                'post_status'  => 'publish',

                'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'award_year',
                            'field'    => 'term_id',
                            'terms'    => array( $term_year_id ),
                            'operator' => 'IN',
                        ),
                        array(
                            'taxonomy' => 'award_categories',
                            'field'    => 'term_id',
                            'terms'    => array( $term_cat_id ),
                            'operator' => 'IN',
                        ),
                    ),
            ));



            $aod_header ='';
            if (get_field('display_awardees') == 1) {
                $awardees_header = sprintf('<h3>%s Awardees:</h3>', $term_year_name);     
            } else {
                $awardees_header = '';
            }
            

            foreach ($value as $id) {

                $title = get_the_title($id);
                $link = get_the_permalink($id);
                $city = get_field('city', $id);
                $status = '';

                if (get_field('crabtree_mclennan_emerging_artist_award', $id) === true) {
                    $status = sprintf('<em>%s</em>', 'Crabtree McLennan Emerging Artist Award');
                }                

                // awardee_category term ID
                $terms_award_cat = get_the_terms( $id, 'awardee_category' ); 

                // Check if is_array, otherwise foreach generates a warning
                if (is_array($terms_award_cat))  {
                    foreach($terms_award_cat as $term) {
                    $term_award_cat_id = $term->term_id;
                    $term_award_cat_slug = $term->slug;
                    } 
                }


                // Set headers based on Award category ID
                if ( get_field('award_of_distinction', $id)) {
                    // COM
                    if (  $term_cat_id === 2 ) {
                        $award_recipient_header = "Mitchell Award";

                    } 
                    
                    // FNA
                    elseif (  $term_cat_id === 4) {
                        $award_recipient_header = "Award of Distinction";

                    } 
                    
                    // Carter Wosk or anything else
                    else {
                        $award_recipient_header = "Award of Distinction";
                    }
                }
             

                    // Get awardee thumbnail 
                    $thumbnail = _awardee_thumbnail($id, $term_cat_slug);
                    
                    
                    // ***************
                    // AOD AWARDEE

                    if ( get_field('award_of_distinction', $id)) {

                        $aod_header = sprintf('<h3>%s %s:</h3>', $term_year_name, $award_recipient_header);

                        $bio = wp_trim_words(get_post_field('post_content', $id), 40);

                        $aod_awardees .= sprintf('<li class="rows awardee-aod"><div class="columns three"><a href="%s" title="%s">%s</a></div><div class="columns eight"><a href="%s" title="%s">%s &raquo;</a><div class="awardee-city">%s</div><p>%s</p></div></li>', $link, $title, $thumbnail, $link, $title, $title, $city, $bio);
                    }
                    // ***************
                    // ALL AWARDEES
                    
                    if ( !get_field('award_of_distinction', $id)) {

                        // If COM set second column class
                        if (  $term_cat_id === 2 ) { 
                            $column_class = 'four';
                        } else {
                            $column_class = 'two';
                        }   

                        $awardees  .= sprintf('<li class="rows awardee"><div class="columns %s"><a href="%s" title="%s">%s</a></div><div class="columns eight"><a href="%s" title="%s">%s &raquo;</a><div class="awardee-city">%s</div>%s</div></li>', $column_class, $link, $title, $thumbnail, $link, $title, $title, $city, $status);
                    }

            } // End foreach loop and return markup


            // Markup for AOD Awardees
            $aod_awardees_out = sprintf('<div id="aod-wrapper">%s<div class="program-awardees-list"><ul>%s</ul></div></div>', $aod_header, $aod_awardees);

            $awardees_out =  sprintf('<div id="awardees-wrapper">%s<div class="program-awardees-list"><ul>%s</ul></div></div>', $awardees_header, $awardees);


            // Output final markup

            $out = sprintf('%s%s', $aod_awardees_out,  $awardees_out);


            return $out; 


        } // End else statement


        else:
            return;

        endif;

}



// Populate ACF relationship 
//add_filter('acf/fields/relationship/query/key=field_5ec84903286d3', '_iba_relationship_load_value',10,3);

function _iba_relationship_load_value($args, $field, $post_id) {


        // Get IBA Award Year 
        $terms_award_year = get_the_terms( $post_id, 'award_year' ); 

       // Get Award Year Slug and Name
        foreach($terms_award_year as $term) {
          $term_year_slug = $term->slug;
        }


        $args['post_status'] = 'publish';
        $args['post_type'] = 'awardee';

          // custom args
          $args['tax_query'][] = 
                array (
                    
                        'taxonomy' => 'award_year',
                        'field'    => 'term_id',
                        'terms'    => 71,
                        'operator' => 'LIKE'
                );

          // return
          return $args;
};



// ACF: Admin set a boolean programmatically to toggle display of Past Programs button
// If award year (term year) is equal to current year (PHP date) set boolean to false. By default boolean set to true.

function _acf_show_past_programs_boolean ( $value, $post_id, $field ) {


        // If admin 
        if (is_admin()) {

            $current_year = date("Y");

            // Get current award year
            $terms_award_year = get_the_terms( $post_id, 'award_year' ); 

           // Get Award Year Slug
            foreach($terms_award_year as $term) {
              $term_year_slug = $term->slug;
            }

            if ( $current_year === $term_year_slug ) {
                // Return boolean value 0 or false
                $value = 0;
            } else {
                $value = 1;
            }


        }



    return $value;
}

add_filter('acf/load_value/name=show_past_programs', '_acf_show_past_programs_boolean', 10, 3);




