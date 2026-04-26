<?php
/*
Plugin Name: Get Better Excerpt
Plugin URI: http://thisismyurl.com/plugins/get-better-excerpt/
Description: Returns the excerpt using whole words instead of partial
Author: Christopher Ross
Tags: get excerpt, get better excerpt, excerpt
Author URI: http://thisismyurl.com/
Version: 2.0.0
*/

/**
 *  Get Better Excerpt core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/get-better-excerpt/
 *
 * @package 		Get Better Excerpt
 * @copyright		Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Get Better Excerpt 1.0
 */

function thisismyurl_get_better_excerpt( $args = NULL ) {

	$defaults = 	array(
						'sentence' => false,
						'words' => '20',
						'before'  => '',
						'after' => '',
						'show' => false,
						'skipexcerpt' => true,
						'link' => false,
						'trail' => ' &#8230;',
					);
	$option = wp_parse_args( $args, $defaults );


	/** check to see this is at least WordPress 3.3 **/
	if ( function_exists( 'wp_trim_words' ) ) {

		if ( $option['skipexcerpt'] ) {

			if ( $option['sentence'] )
				$excerpt_array = explode( '.', strip_tags( get_the_content() ), $option['sentence']+1 );
			else
				$excerpt = wp_trim_words( get_the_content(), $option['words'], $option['trail'] );

		} else {

			if ( $option['sentence'] )
				$excerpt_array = explode( '.', strip_tags( get_the_excerpt() ), $option['sentence']+1 );
			else
				$excerpt = wp_trim_words( get_the_excerpt(), $option['words'], $option['trail'] );

		}

		if ( $excerpt_array )
			$excerpt = $excerpt_array[0] . ' ' . $option['trail'] ;


		if ( $option['link'] )
			$excerpt = '<a href="' . get_permalink() . '" title="' . esc_attr( get_the_title() ) . '">'.$excerpt.'</a>';

		$excerpt = $option['before'] . $excerpt . $option['after'];

		if ( $option['show'] )
			echo $excerpt;
		else
			return $excerpt;

	} else {

		/** the wp_trim_words() function doesn't exist **/

		if ( $option['show'] )
			echo get_the_excerpt();
		else
			return get_the_excerpt();

	}
}
