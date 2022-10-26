<?php
/**
 * @package bcaf
 */


// ADD SHORTCODES
add_shortcode( 'past_programs', '_past_programs' );

// Line 314
add_shortcode( 'award_sponsors', '_award_sponsors' );
add_shortcode( 'awardees', '_awardees_by_taxonomy' );

add_shortcode( 'awardees_banner', '_awardees_banner' );
add_shortcode( 'awardees_aod', '_awardee_aod' );
add_shortcode( 'home_features','_home_features');
add_shortcode( 'awardee_profile','_awardee_profile');
add_shortcode( 'awardee_profile_awards','_awardee_profile_awards');
add_shortcode( 'social_media_icons','_social_media_icons');


// Awardee Profile: Elementor Icons based on ACF Fields
function _social_media_icons() {

$css = NULL;

if ( !get_field('facebook_link')) {
	$css = '#awardee-social-icons .elementor-social-icon-facebook { display: none;}';
}

if ( !get_field('twitter_link')) {
        $css .= '#awardee-social-icons .elementor-social-icon-twitter { display: none;}';
}

if ( !get_field('youtube_link')) {
        $css .= '#awardee-social-icons .elementor-social-icon-youtube { display: none;}';
}

if ( !get_field('linkedin_link')) {
        $css .= '#awardee-social-icons .elementor-social-icon-linkedin { display: none;}';
}

if ( !get_field('instagram_link')) {
        $css .= '#awardee-social-icons .elementor-social-icon-instagram { display: none;}';
}

if ( !get_field('youtube_video_url') && !get_field('youtube_video_url_2')) {
	$css .= '.single-awardee section#awardee-videos { display: none;}';
}

$css_out =  sprintf('<style>%s</style>', $css);

return $css_out;


}



// Awardee Profile: Display award logo 

function _awardee_profile_awards() {

	   $term_list = wp_get_post_terms( get_the_ID(), 'award_categories', array( 'fields' => 'ids' ) );
                $current_term_id = $term_list[0];


                // COM
                if ($current_term_id == 2) {
			$image = $image = sprintf('<img src="%s/wp-content/uploads/2019/05/BCAF-Community-Award_logo.png" alt="Award Logo" />',site_url());
	
		}
                // AAD
                if ($current_term_id == 3) {
			$image = sprintf('<img src="%s/wp-content/uploads/2019/04/BCAF-Carter-Wosk-Applied-Art-Design-Logo_color-e1557866569710-1024x264.png" alt="Award Logo" />',site_url());
		} 
	      	// FNA	
                if ($current_term_id == 4) {
			$image = sprintf('<img src="%s/wp-content/uploads/2019/05/BCAF-Fulmer-Awards-First-Nations-Art-Logo.png" alt="Award Logo" />',site_url());
		}
		//IBA
                if ($current_term_id == 5) {
			$image = sprintf('<img src="%s/wp-content/uploads/2019/05/BCAF-Indigenous-Business-Award-Logo.png" alt="Award Logo" />',site_url());
		}
		// REC
		if ($current_term_id == 146) {
			$image = sprintf('<img src="%s/wp-content/uploads/2020/10/BCRA-Logo.png" alt="Award Logo" />',site_url());
		
		}

	//return  $current_term_id;
	return $image;
}


// Displays awardee profile snippet from ACF Post Object
function _awardee_profile() {

if (!get_field('select_awardee_profile')) {
	return;
}
// Return one object
	wp_reset_postdata();
	$awardee_id = get_field('select_awardee_profile');	
	$title = get_the_title($awardee_id);
	$permalink = get_permalink($awardee_id);
	$bio = wp_trim_words(get_post_field('post_content', $awardee_id), 50);//wp_trim_words(get_the_content($awardee_id));
	$image = get_the_post_thumbnail($awardee_id,'medium',array('class'=>'clip-circle') );
	$out = sprintf('<div class="row"><div class="columns four"><a href="%s">%s</a></div><div class="columns six"><h2>%s</h2>%s<br /><br /><a href="%s" class="button">%s\'s Profile</a></div></div>', $permalink,$image,$title,$bio,$permalink,$title);
	return $out;
}

// Output shortcode based on ACF field grid_type_select from homepage
function _home_features(){

	$current = get_field('grid_type_select');

	if ($current == 'option2'){
	// One + 3 Blogs
		echo do_shortcode('[elementor-template id="12750"]');
		echo do_shortcode('[elementor-template id="12792"]');

	} elseif ($current == 'option3') {
	// Two + 3 Blogs
		echo do_shortcode('[elementor-template id="12754"]');
		echo do_shortcode('[elementor-template id="12792"]');

	} elseif ($current == 'option1') {
	// Default 6 Blogs
		echo do_shortcode('[elementor-template id="12746"]');
	// Video + 3 Blocks
	} elseif ( $current == 'option4') {
		echo do_shortcode('[elementor-template id="12743"]');
		echo do_shortcode('[elementor-template id="12792"]');
	} elseif ( $current == 'option5') {
                echo do_shortcode('[elementor-template id="12760"]');
        }	
}




// ACF OPTIONS
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Global Settings',
		'menu_title'	=> 'Global Settings',
		'menu_slug' 	=> 'general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
/*	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
 */	
}


function _awardee_aod($atts) {

    if (is_singular('awardee')) {


        // Default recipient title
        $title = "Recipient";

        //if (get_field('custom_recipient_title') && $current_term_id !== 5) {
        //    $title = get_field('custom_recipient_title');
        //}


        // Get current term ID
        $term_list = wp_get_post_terms( get_the_ID(), 'award_categories', array( 'fields' => 'ids' ) );
        $current_term_id = $term_list[0];

	

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


        $out = sprintf('%s', $title);

        return $out;

    }

}


/* Award Program Category Banner */
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

// Shortcode: [award_sponsors pid=]

function _award_sponsors($atts) {

// Default value for shortcode is current post ID
 extract(shortcode_atts(array(
      'pid' => $post->ID,
   ), $atts));


if ( get_field('display_sponsors', $pid)):


        // award_year term ID
        $terms_award_year = get_the_terms( $pid, 'award_year' ); 

        // Get Award Category Slug and Name
        $terms_award_cat = get_the_terms( $pid, 'award_categories' ); 
        
        foreach($terms_award_cat as $term) {
          $term_cat_id = $term->term_id;
          //$term_cat_slug = $term->slug;
        }


        // Default Sponsor Levels 
       /* 
		if (get_field('sponsors_section_header')) {
			$sponsors_section_header = get_field('sponsors_section_header', $pid);
		} else {
			$sponsors_section_header = "With thanks to our program partners";	// Default
		}
        */


        $supporting_orgs = "Organizations";
        $sponsor_header = "Sponsors";
        $presentation_header = "Presentation Sponsor";
        $strategic_header = "Strategic Partners";
        $event_header = "Event Partners";
        $donor_header = "Donors";
        $supporting_header = "Organizations";
        $community_partners_header = "Community Partners";

        // Override IBA Sponsor Headers
        if (get_field('override_sponsor_level_labels', $pid) == 1) {
            $is_iba = TRUE;

            if (get_field('rename_sponsor_header', $pid)) {
                $sponsor_header = get_field('rename_sponsor_header', $pid);
            }

            if (get_field('rename_strategic_partner_header', $pid)) {
                $strategic_header = get_field('rename_strategic_partner_header', $pid);
            }

            if (get_field('rename_event_partner_header', $pid)) {
                $event_header = get_field('rename_event_partner_header', $pid);
            }

            if (get_field('rename_donor_header', $pid)) {
                $donor_header = get_field('rename_donor_header', $pid);
           }
            if (get_field('rename_presentation_sponsors_header', $pid)) {
                $presentation_header = get_field('rename_presentation_sponsors_header', $pid);
            }                       
        
            if (get_field('rename_wine_sponsor_header', $pid)) {
                $wine_sponsor_header = get_field('rename_wine_sponsor_header', $pid);
            }  

            if (get_field('rename_professional_development_sponsor', $pid)) {
                $professional_development_header = get_field('rename_professional_development_sponsor', $pid);
            }  

            //if (get_field('rename_community_partners', $pid)) {
            //    $community_partners_header = get_field('rename_community_partners', $pid);
            //}              


        }  



        // HEADER //


            $out = NULL;

            /*
            $sponsor_header_copy = get_field('sponsors_section_header', $pid); 

            if ( $term_cat_id === 5 ):
                $bar_color = 'style="background-color: #DF8627;';
            endif;


            $out = sprintf('<div class="row sponsors" style="margin-top: 30px;"><h2 class="typeface2" %s padding: 5px 15px; color: #fff;">%s</h2></div>', $bar_color, $sponsor_header_copy);
            */




        // If IBA 
        if ( $term_cat_id === 5 ):



        //Block 1 Supporting Organizations (BCAFN, FNS, MNBC, UBCIC)

            if ( get_field('supporting_organizations', $pid)) {

                $supporting = get_field('supporting_organizations', $pid);


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

                 $out .= sprintf('<div class="row sponsors supporting-organizations"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $supporting_orgs, $supporting_markup);

                
            }  


        //****** BC GOVT ******//        

            $bc_govt_sponsor_pid = 15854; 
            $bc_govt_sponsor_logo = get_field('sponsor_logo', $bc_govt_sponsor_pid);
            $bc_govt_sponsor_url = get_field('sponsor_website_url', $bc_govt_sponsor_pid);

            $govt_logo = sprintf('<div class="row sponsors"><a href="%s"><img src="%s" alt="BC Government" style="max-width: 260px;" /></a></div>',$bc_govt_sponsor_url, $bc_govt_sponsor_logo );

            $gov = sprintf('<div class="columns tweleve sponsors" style="margin-top: 30px;">%s</div>', $govt_logo);

            $group_gov = sprintf('<div class="columns four sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', "Program Partner", $gov);


        // ****** LEAD SPONSOR ******* //


            if ( get_field('partner_type_sponsor', $pid)) {

              
                $sponsors = get_field('partner_type_sponsor', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup1 .= sprintf('<div class="columns twelve"><a href="%s" target="_blank"><img src="%s" alt="" style="max-width: 240px;" /></a></div>', $url, $logo );
            
                endforeach;        



                // Output entire markup
                $group1 = sprintf('<div class="columns four sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $sponsor_header, $sponsors_markup1);
            }



                // Block 2 Program Partner: Govt BC Lead Sponsor: Vancity
                $out .= sprintf('<div class="row sponsors">%s%s</div>', $group_gov, $group1);





            //***** PRESENTATION SPONSOR *****//


            if ( get_field('presentation_sponsors', $pid)) {


                $sponsors = get_field('presentation_sponsors', $pid);

                // Returns sponsor post ID 
                foreach( $sponsors as $sid ):

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $presentation_sponsor_markup .= sprintf('<div class="columns twelve"><a href="%s" target="_blank"><img src="%s" alt="" style="max-width: 240px;" /></a></div>', $url, $logo );

                endforeach;



                // Output entire markup
                $group2 = sprintf('<div class="columns four sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $presentation_header, $presentation_sponsor_markup);

            }



        // ****** ALUMNI SPONSOR PROGRAM ******* //


            if ( get_field('alumni_program_sponsor', $pid)) {

              
                $sponsors = get_field('alumni_program_sponsor', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup .= sprintf('<div class="columns twelve sponsors" style="margin-left: 0 !important;"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;        



                // Output entire markup
                $group3 = sprintf('<div class="columns four sponsors"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', 'Alumni Program Sponsor', $sponsors_markup);

                $sponsors = null;
                $sponsors_markup = null;
            }



            

            //***** PRESENTATION SPONSOR: TEK *****//


            if ( get_field('partner_type_strategic_partner', $pid)) {
                
                $sponsors = get_field('partner_type_strategic_partner', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup .= sprintf('<div class="columns twelve sponsors" style="margin-left: 0 !important;"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                $group4 = sprintf('<div class="columns four sponsors"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $strategic_header, $sponsors_markup);

            }


        /**** Group together Block 3 - Enbridge, TD, Teck ****/

            $out .= sprintf('<div class="row sponsors">%s%s%s</div>', $group2, $group3, $group4 );




        /* FILM SPONSORS */

            if ( get_field('partner_type_event_partner', $pid)) {
                
                $event_partners = get_field('partner_type_event_partner', $pid);



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

                 $out .= sprintf('<div class="row sponsors partner-event"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $event_header, $event_partners_markup);


            }



        /* SUPPORTING SPONSORS */


         if ( get_field('supporting_sponsors', $pid)) {


                $supporting_sponsors = get_field('supporting_sponsors', $pid);


                // Returns sponsor post ID
                foreach( $supporting_sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $supporting_sponsors_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" style="max-width: 330px;" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                 $out .= sprintf('<div class="row sponsors partner-donor"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', 'Supporting Sponsors', $supporting_sponsors_markup);

                
            }        



        /* MEDIA SPONSORS */

            if ( get_field('partner_type_donor', $pid)) {

                $donors = get_field('partner_type_donor', $pid);

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

                 $out .= sprintf('<div class="row sponsors partner-donor"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $donor_header, $donors_markup);

            }        


        /* PRO DEV SPONSORS */

            if ( get_field('professional_development_sponsor', $pid)) {

                $donors = get_field('professional_development_sponsor', $pid);

                // Returns sponsor post ID
                foreach( $donors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $professional_development_sponsor_markup .= sprintf('<div class="columns twelve"><a href="%s" target="_blank"><img style="max-width: 330px;" src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                 $pro_dev_sponsors .= sprintf('<div class="sponsors partner-donor columns five"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $professional_development_header, $professional_development_sponsor_markup);

            }        


        /* WINE SPONSORS */

            if ( get_field('wine_sponsors', $pid)) {

                $donors = get_field('wine_sponsors', $pid);

                // Returns sponsor post ID
                foreach( $donors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $wine_sponsors_sponsor_markup .= sprintf('<div class="columns twelve"><a href="%s" target="_blank"><img src="%s" style="max-width: 330px;" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                 $wine_sponsors .= sprintf('<div class="sponsors partner-donor columns five"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $wine_sponsor_header, $wine_sponsors_sponsor_markup);

            }    



        /* COMMUNITY PARTNERS */

            if ( get_field('community_partners', $pid)) {

                $donors = get_field('community_partners', $pid);

                // Returns sponsor post ID
                foreach( $donors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $community_partners_markup .= sprintf('<div class="columns twelve"><a href="%s" target="_blank"><img src="%s" style="max-width: 330px;" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                 $community_partners .= sprintf('<div class="sponsors partner-donor columns five"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $community_partners_header, $community_partners_markup);

            }                


            $out .= sprintf('<div class="row sponsors">%s%s%s</div>', $pro_dev_sponsors, $wine_sponsors, $community_partners);



            // Return final output
            return $out;




        endif;


        // If not IBA Category
        if ( $term_cat_id !== 5 ):

        // ****** LEAD SPONSOR ******* //


            if ( get_field('partner_type_sponsor', $pid)) {

              
                $sponsors = get_field('partner_type_sponsor', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup1 .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" style="max-width: 240px;" /></a></div>', $url, $logo );
            
                endforeach;        

            }


                $group1 = sprintf('<div class="columns twelve sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $sponsor_header, $sponsors_markup1);



                $out .= sprintf('<div class="row sponsors">%s</div>', $group1);




            //***** PRESENTATION SPONSOR *****//


            if ( get_field('presentation_sponsors', $pid)) {


                $sponsors = get_field('presentation_sponsors', $pid);

                // Returns sponsor post ID 
                foreach( $sponsors as $sid ):

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $presentation_sponsor_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" style="max-width: 240px;" /></a></div>', $url, $logo );

                endforeach;



                // Output entire markup
                $group2 = sprintf('<div class="columns twelve sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $presentation_header, $presentation_sponsor_markup);

            }



            $out .= sprintf('<div class="row sponsors">%s</div>',$group2);




// ****** ALUMNI SPONSOR PROGRAM ******* //


            if ( get_field('alumni_program_sponsor', $pid)) {

              
                $sponsors = get_field('alumni_program_sponsor', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup .= sprintf('<div class="columns three sponsors" style="margin-left: 0 !important;"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;        



                // Output entire markup
                $group3 = sprintf('<div class="columns twelve sponsors" style="margin-left: 0 !important;"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $presentation_header, $presentation_sponsor_markup);

            }


            $out .= sprintf('<div class="row sponsors">%s</div>', $group3);


            

            if ( get_field('partner_type_strategic_partner', $pid)) {
                
                $sponsors = get_field('partner_type_strategic_partner', $pid);

                // Returns sponsor post ID
                foreach( $sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $sponsors_markup .= sprintf('<div class="columns three sponsors" style="margin-left: 0 !important;"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                $group4 = sprintf('<div class="columns twelve sponsors"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $strategic_header, $sponsors_markup);

            }


            $out .= sprintf('<div class="row sponsors">%s</div>', $group4);




        /* FILM SPONSORS */

            if ( get_field('partner_type_event_partner', $pid)) {
                
                $event_partners = get_field('partner_type_event_partner', $pid);



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

                 $out .= sprintf('<div class="row sponsors partner-event"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', $event_header, $event_partners_markup);


            }



        /* SUPPORTING SPONSORS */


         if ( get_field('supporting_sponsors', $pid)) {


                $supporting_sponsors = get_field('supporting_sponsors', $pid);


                // Returns sponsor post ID
                foreach( $supporting_sponsors as $sid ): 

                    $logo = get_field('sponsor_logo', $sid);
                    $url = get_field('sponsor_website_url', $sid);
                    if (empty($url)) {
                        $url = 'javascript:;';
                    }
                    $alt_title = get_field(get_the_title($sid));

                    $supporting_sponsors_markup .= sprintf('<div class="columns three"><a href="%s" target="_blank"><img src="%s" alt="" /></a></div>', $url, $logo );
            
                endforeach;  

                 $out .= sprintf('<div class="sponsors partner-donor"><h4>%s</h4><hr style="background-color:#00000012; margin-bottom: 15px;" />%s</div>', 'Supporting Sponsors', $supporting_sponsors_markup);

                
            }        




            return $out;

        endif;



    endif; // If display_sponsors boolean


} // end function


function _awardee_thumbnail($id, $term_cat_slug) {

$featured_img_url = get_the_post_thumbnail_url($id);

if($featured_img_url) {
        $thumbnail = aq_resize( $featured_img_url, 240, 240, true, true, true);
        $thumbnail_tag = sprintf('<img src="%s" alt="Image" />', $thumbnail);
} else {
	$thumbnail_tag = sprintf('<img src="%s/images/awardee-placeholder-%s.jpg" class="awardee-image-placeholder" alt="Placeholder" />', get_template_directory_uri(), $term_cat_slug);
}

    return $thumbnail_tag;
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

	// If display_awardees ACF boolean is enabled
	if ( get_field('display_awardees') == 1) {

                    // Young Entrepreneur of the Year
                    $yeoty = get_field('young_entrepreneur_of_the_year', $post->ID);
                    if ( $yeoty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $yeoty as $aid ):
                             $header = 'Young Entrepreneur of the Year';
                             $temp9.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp9);

                    endif; 

        /*** BUSINESS OF THE YEAR ****/

                    // Business of the Year - one to two person enterprise
                    $business_oty_two_person = get_field('business_of_the_year_-_one_to_two_person_enterprise', $post->ID);
                    if ( $business_oty_two_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_two_person as $aid ):
                             $header = 'Business of the Year <span>one to two person enterprise</span>';
                             $temp4.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s',$temp4);

                    endif;                    


                    // Business of the Year - three to 10 person enterprise
                    $business_oty_three_ten_person = get_field('business_of_the_year_-_three_to_10_person_enterprise', $post->ID);
                    if ( $business_oty_three_ten_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_three_ten_person as $aid ):
                             $header = 'Business of the Year <span>three to 10 person enterprise</span>';
                             $temp5.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp5);

                    endif;    


                    // Business of the Year – 10+ person enterprise
                    $business_oty_ten_plus_person = get_field('business_of_the_year_–_10+_person_enterprise', $post->ID);
                    if ( $business_oty_ten_plus_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_ten_plus_person as $aid ):
                             $header = 'Business of the Year <span>10+ person enterprise</span>';
                             $temp6.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp6);

                    endif; 
   
                    //Business of the Year – 11+ person enterprise
                    $business_oty_eleven_plus_person = get_field('business_of_the_year_–_11+_person_enterprise', $post->ID);
                    if ( $business_oty_eleven_plus_person ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_oty_eleven_plus_person as $aid ):
                             $header = 'Business of the Year <span>11+ person enterprise</span>';
                             $temp7.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp7);

                    endif; 


                    // Community-Owned Business of the Year
                    $coboty = get_field('community-owned_business_of_the_year', $post->ID);
                    if ( $coboty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty as $aid ):
                             $header = 'Community-Owned Business of the Year';
                             $temp8.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp8);

                    endif; 


        /*** COMMUNITY OWNED BUSINESS ****/

                    // Community-Owned Business of the Year – one entity
                    $coboty_one_entity = get_field('community-owned_business_of_the_year_–_one_entity', $post->ID);
                    if ( $coboty_one_entity ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty_one_entity as $aid ):
                             $header = 'Community-Owned Business of the Year <span>one entity</span>';
                             $temp10.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp10);

                    endif; 


                    // Community-Owned – two or more entities
                    $coboty_two_more_entity = get_field('community-owned_–_two_or_more_entities', $post->ID);
                    if ( $coboty_two_more_entity ):
                        // Loop ACF relationship and return post ID
                        foreach ( $coboty_two_more_entity as $aid ):
                             $header = 'Community-Owned Business of the Year <span>two or more entities</span>';
                             $temp11.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp11);

                    endif; 


        /*** ABORIGINAL BUSINESSES ****/


                    // Aboriginal-Aboriginal Business Partnership of the Year
                    $abpy = get_field('aboriginal-aboriginal_business_partnership_of_the_year', $post->ID);
                    if ( $abpy ):
                        // Loop ACF relationship and return post ID
                        foreach ( $abpy as $aid ):
                            $header = 'Aboriginal-Aboriginal Business <span>Partnership of the Year</span>';
                            $temp1 .= include(locate_template('inc/iba-template.php', false, false));

                        endforeach;

                        $out .= sprintf('%s', $temp1);

                    endif;


                    // Aboriginal-Industry Business Partnership of the Year
                    $aibpy = get_field('aboriginal-industry_business_partnership_of_the_year', $post->ID);
                    if ( $aibpy ):
                        // Loop ACF relationship and return post ID
                        foreach ( $aibpy as $aid ):
                             $header = 'Aboriginal-Industry Business <span>Partnership of the Year</span>';
                             $temp2.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;
                       
                         $out .= sprintf('%s', $temp2);

                    endif;                    

                    // Business Partnership of the Year
                    $business_poty = get_field('business_partnership_of_the_year', $post->ID);
                    if ( $business_poty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $business_poty as $aid ):
                             $header = 'Business Partnership of the Year';
                             $temp3.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                         $out .= sprintf('%s', $temp3);

                    endif;                    


                    // Joint Venture Business Partnership of the Year
                    $jvbpoty = get_field('joint_venture_business_partnership_of_the_year', $post->ID);
                    if ( $jvbpoty ):
                        // Loop ACF relationship and return post ID
                        foreach ( $jvbpoty as $aid ):
                             $header = 'Joint Venture Business Partnership of the Year';
                             $temp12.= include(locate_template('inc/iba-template.php', false, false));
                        endforeach;

                       
                        $out .= sprintf('%s', $temp12);

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

                        $out .= sprintf('%s', $aod_iba);

                    endif;



         // Return final markup for IBA Awardees
         $html = sprintf('<h2 class="typeface2">%s AWARDEES</h2><div class="program-awardees-list" style="margin-bottom:30px;float:left;">%s</div>', $term_year_name, $out);                    
	 return $html;

	} // End display_awardees ACF boolean if statement

        } else {


        /********************************************************
        // COM, FNA and CW Conditional; Run query
        ********************************************************/            


	$awardees_header = '';

	// Check boolean display awardees or not
	if ( get_field('display_awardees') == 1) {
	     $awardees_header = sprintf('<h2 class="typeface2">%s AWARDEES</h2>', $term_year_name);
 
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



            foreach ($value as $id) {

                $title = get_the_title($id);
                $link = get_the_permalink($id);
                $city = get_field('city', $id);
                $status = '';

                /* Removed 04/22
                if (get_field('crabtree_mclennan_emerging_artist_award', $id) === true) {
                    $status = sprintf('<em>%s</em>', 'Crabtree McLennan Emerging Artist');
                }                

                if (get_field('judson_beaumont_emerging_artist', $id) === true) {
                    $status = sprintf('<em>%s</em>', 'Judson Beaumont Emerging Artist');
                }
                */



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
                    /*
                    if ( get_field('award_of_distinction', $id)) {

                        $aod_header = sprintf('<h3>%s %s:</h3>', $term_year_name, $award_recipient_header);

			$bio = wp_trim_words(get_post_field('post_content', $id), 40);
			
                        $aod_awardees .= sprintf('<li class="rows awardee-aod"><div class="columns three"><a href="%s" title="%s">%s</a></div><div class="columns eight"><a href="%s" title="%s">%s &raquo;</a><div class="awardee-city">%s</div><p>%s</p></div></li>', $link, $title, $thumbnail, $link, $title, $title, $city, $bio);

                    }
                    */

                    // ***************
                    // ALL AWARDEES
                    
                    $aod_label = NULL;
                    //if ( get_field('award_of_distinction', $id)) {
                    //    $aod_label = sprintf('Award of Distinction');
                    //}

                        // If COM set second column class
                    /*
                        if (  $term_cat_id === 2 ) { 
                            $column_class = 'four';
                        } else {
                            $column_class = 'two';
                        }   
                    */
                        $awardees  .= sprintf('
                            <div class="columns three awardee">
                            <a href="%s">%s<h3 style="margin-bottom: 0; margin-top: 15px;">%s</h3><div class="status">%s%s</div></a>
                            </div>', $link,$thumbnail,$title, $status,$aod_label);
                    //}

            } // End foreach loop and return markup


            // Markup for AOD Awardees
            //$aod_awardees_out = sprintf('<div id="aod-wrapper">%s<div class="program-awardees-list"><ul>%s</ul></div></div>', $aod_header, $aod_awardees);

            $awardees_out =  sprintf('<div id="awardees-wrapper">%s<div class="program-awardees-list"><div class="rows">%s</div></div></div>', $awardees_header, $awardees);


            // Output final markup

            $out = sprintf('%s', $awardees_out);


            return $out; 


        } // End else statement

}
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




