<?php
/**
Plugin Name: DB search
Plugin URI: 
Description: Built to find "http://facultyenlight.com/" on the BU site
Author: BU - IS&T
Version: Theta 0.0.1
*/

		
$stringToSearch = function ( $args, $args_assoc ) {
			global $wpdb;
			$blogs = $wpdb->get_results( 'SELECT * FROM wp_blogs' );
			if ( ! $blogs ) {
				\WP_CLI::error( 'No blogs found' );
			}

			// Setup a table to return the data.
			$output = new \cli\Table();
			$output->setHeaders(
				array(
					'blog_id',
					'url',
					'str_instances',
				)
			);

			foreach ( $blogs as $blog ) {
				$site_url = 'http://' . $blog->domain . $blog->path;


				// Get a count of protected pages.
				$post_query  = sprintf( "SELECT COUNT(*) FROM wp_%s_posts WHERE post_content LIKE '%http\:\/\/facultyenlight.com\/%';", $blog->blog_id );
				$post_result = $wpdb->get_results( $post_query, ARRAY_A );
				$str_instances  = $post_result[0]['COUNT(*)'];


				$row = array(
					$blog->blog_id,
					$site_url,
					$str_instances,
				);

				$output->addRow( $row );
			}
			
	WP_CLI::success( $output );
}

WP_CLI::add_command( 'find-string', $stringToSearch);


