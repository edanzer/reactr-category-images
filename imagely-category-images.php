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

// Define constants
define( 'ICI_DEV_MODE', true );
define( 'ICI_VERSION', ICI_DEV_MODE ? time() : '1.0.0' );
define( 'ICI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Main plugin class
final class Imagely_Category_Images {

	// Contructor
	public function __construct() {
		// Back end hooks
		add_action( 'category_add_form_fields', [$this, 'new_category_image_field'] );
		add_action( 'category_edit_form_fields', [$this, 'edit_category_image_field'] );
		add_action( 'created_category', [$this, 'save_category_image'] );
		add_action( 'edited_category', [$this, 'save_category_image'] );
		add_action( 'admin_enqueue_scripts', [$this, 'admin_assets'] );

		// Front end hooks
		add_action('wp_head', [$this, 'show_category_image'], 100);
	}

	// Show image field when adding a new category
	public function new_category_image_field() { ?>
		<div class='form-field term-image-wrap'>
			<label for="imagely-category-image">Category Featured Image</label>
			<a href='#' class='imagely-category-image-button button button-secondary'><?php _e('Upload'); ?></a>
       		<input type='text' name='imagely-category-image' id='imagely-category-image' value='' />
       		<p>Upload or select a featured image for this category. You can also just copy and past the url to the image in the text field.</p>
		</div> <?php
	}

	// Show image field when editing an existing category
	public function edit_category_image_field( $tag ) {
		$category_image = get_term_meta( $tag->term_id, 'imagely-category-image', true ); ?>

           	<tr class='form-field term-image-wrap'>
				<th scope='row'><label for='imagely-category-image'>Category Featured Image</label></th>
				<td>
					<a href='#' class='imagely-category-image-button button button-secondary' ><?php _e('Upload'); ?></a>
	           		<input type='text' name='imagely-category-image' id='imagely-category-image' value='<?php echo $category_image; ?>' />
					<p class='description'>Upload or select a featured image for this category. You can also just copy and past the url to the image in the text field.</p>
				</td>
			</tr><?php
	}

	// Enqueue admin scripts and styles
	public function admin_assets() {
		
		// Register styles and scripts
		wp_register_script(
			'imagely-category-image-js',
			ICI_PLUGIN_URL . 'assets/admin.js',
			['jquery','media-upload','thickbox'],
			ICI_VERSION,
			true
		);
		wp_register_style(
			'imagely-category-image-css',
			ICI_PLUGIN_URL . 'assets/admin.css',
			[],
			ICI_VERSION
		);

		// Enqueue WordPress assets
		wp_enqueue_media();
		wp_enqueue_script('media-upload');
    	wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

		// Enque our assets
		wp_enqueue_script('imagely-category-image-js');
		wp_enqueue_style('imagely-category-image-css');
	}

	// Save image to category meta when saving category
	public function save_category_image( $term_id ) {
	    if ( isset( $_POST['imagely-category-image'] ) )
	        update_term_meta( $term_id , 'imagely-category-image', $_POST['imagely-category-image'] );
	}

	// Replace page header image for Imagely theme on category pages
	public function show_category_image() {
		if ( is_category() ) {
			//$category_image = get_term_meta(get_queried_object_id())['imagely-category-image'][0];
			$category_image = get_term_meta(get_queried_object_id())['imagely-category-image'][0];

			if ($category_image) {
				echo '	<style>
						.category .page-header {
			    			background-image: url(' . $category_image . ') !important;
			    		}{}
						</style>';
			}
		}
	}

}

new Imagely_Category_Images();