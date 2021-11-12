<?php

// Replace menu links 
function update_menu_link($items){


	/* Menu IDs
	 *
	 * COM : 143
	 * IBA : 144
	 * FNA : 156
	 * AAD : 157
	 * REC : 9542
	 **/

    //look through the menu for items with Label "Link Title"
foreach($items as $item){

	if ($item->ID === 143 ){ 
		// If URL isn't set
		if ($item->url == '#' || $item->url == NULL ) {
			$item->url = get_field('com_link','option');
		}	
        }
	

        if ($item->ID === 144 ){
                if ($item->url == '#' || $item->url == NULL ) {
			$item->url = get_field('iba_link','option');
		}       
        }

        if ($item->ID === 156 ){
                if ($item->url == '#' || $item->url == NULL ) {         
                        $item->url = get_field('fna_link','option');

                }
        }
        
        if ($item->ID === 157 ){
                if ($item->url == '#' || $item->url == NULL ) {     
                        $item->url = get_field('aad_link','option');

                }
	}

        if ($item->ID === 9542 ){
                if ($item->url == '#' || $item->url == NULL ) {     
                        $item->url = get_field('rec_link','option');

                }
        }	


}


	return $items;
}

add_filter('wp_nav_menu_objects', 'update_menu_link', 10,2);
