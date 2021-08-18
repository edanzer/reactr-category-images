<?php
/*
 * Plugin Name: Imagely Category Images
 * Description: Adds featured image for category, uses it on category pages.
 * Version: 1.0.0
 * Author: Erick Canzer
 * Author URI: http://erickdanzer.com
 * 
 * Copyright 2021 Erick Danzer
 * 
 * This program is free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Main plugin class
class Imagely_Category_Images
{

	// Contructor
	public function __construct() {
		// Back end hooks
		add_action( 'category_add_form_fields', [$this, 'new_category_image_field'] );
		add_action( 'category_edit_form_fields', [$this, 'edit_category_image_field'] );
		add_action( 'created_category', [$this, 'save_category_image'] );
		add_action( 'edited_category', [$this, 'save_category_image'] );

		// Front end hooks
		add_action('wp_head', [$this, 'show_category_image'], 100);
	}

	// Show image field when adding a new category
	public function new_category_image_field() {
		?>
		    <div class="form-field term-image-wrap">
				<label for="imagely_category_image"><?php _e('Add Image URL'); ?></label>
				<input type='text' name='imagely_category_image' id='imagely_category_image' value=''>
				<p>Enter an image url (ie, from the media library). The image may show behind the page title on category pages, depending on your Imagely theme settings.</p>
			</div>
	    <?php
	}

	// Show image field when editing an existing category
	public function edit_category_image_field( $tag ) {
		$category_image = get_term_meta( $tag->term_id, 'imagely_category_image', true ); ?>

	    <tr class='form-field'>
	        <th scope='row'><label for='imagely_category_image'><?php _e('Add Image URL'); ?></label></th>
	        <td>
	            <input type='text' name='imagely_category_image' id='imagely_category_image' value='<?php echo $category_image ?>'>
	        </td>
	    </tr> 

	    <?php
	}

	// Save image to category meta when saving category
	public function save_category_image( $term_id ) {
	    if ( isset( $_POST['imagely_category_image'] ) )
	        update_term_meta( $term_id , 'imagely_category_image', $_POST['imagely_category_image'] );
	}

	// Replace page header image for Imagely theme on category pages
	public function show_category_image() {
		if ( is_category() ) {
			$category_image = get_term_meta(get_queried_object_id())["imagely_category_image"][0];
			if ($category_image) {
				echo "
					<style>
						.category .page-header {
			    			background-image: url(" . $category_image . ") !important;
			    		}{}
					</style>
				";
			}
		}
	}

}

new Imagely_Category_Images();