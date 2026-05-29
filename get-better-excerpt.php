<?php
/**
 * Plugin Name:       Get Better Excerpt
 * Plugin URI:        https://thisismyurl.com/plugins/get-better-excerpt/
 * Description:       Returns the excerpt using whole words instead of partial words.
 * Version:           2.6148.2110
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       get-better-excerpt
 * Tags:              excerpt, get excerpt, get better excerpt
 *
 * @package GetBetterExcerpt
 */

declare( strict_types=1 );

namespace ThisIsMyURL\GetBetterExcerpt;

defined( 'ABSPATH' ) || exit;

/**
 * Build a trimmed excerpt for the current post in the loop.
 *
 * @param array|string|null $args {
 *     Optional. Arguments to control excerpt output.
 *
 *     @type int|false $sentence     Number of sentences to return, or false to use word count. Default false.
 *     @type int       $words        Number of words when $sentence is false. Default 20.
 *     @type string    $before       Raw HTML prepended to the excerpt. Caller is responsible for escaping. Default ''.
 *     @type string    $after        Raw HTML appended to the excerpt. Caller is responsible for escaping. Default ''.
 *     @type bool      $show         Deprecated. Echo the excerpt when true. Prefer returning and echoing at call site. Default false.
 *     @type bool      $skipexcerpt  Use post content instead of manual excerpt field. Default true.
 *     @type bool      $link         Wrap the excerpt in a permalink anchor. Default false.
 *     @type string    $trail        Ellipsis appended when content is trimmed. Default ' &#8230;'.
 * }
 * @return string|void Returns the excerpt string, or echoes it when $show is true (deprecated).
 */
function thisismyurl_get_better_excerpt( $args = null ) {
	$defaults = array(
		'sentence'    => false,
		'words'       => 20,
		'before'      => '',
		'after'       => '',
		'show'        => false,
		'skipexcerpt' => true,
		'link'        => false,
		'trail'       => ' &#8230;',
	);

	$option = wp_parse_args( $args, $defaults );

	$words    = absint( $option['words'] );
	$sentence = $option['sentence'] ? absint( $option['sentence'] ) : false;
	$trail    = (string) $option['trail'];

	// Determine source text: post content or manual excerpt field.
	$source_text = $option['skipexcerpt'] ? get_the_content() : get_the_excerpt();

	if ( $sentence ) {
		// Sentence-based trim: split on period into at most N sentences plus the
		// remaining tail. explode( '.', $text, N + 1 ) means the final element is
		// the overflow tail only when the text actually has more than N sentences.
		$parts = explode( '.', wp_strip_all_tags( $source_text ), $sentence + 1 );

		// Drop the tail fragment only when one exists: the limit produced N + 1
		// elements, so element N is genuine overflow. When the text had fewer
		// sentences than requested, every element is a real sentence — keep them
		// all rather than discarding the final one.
		$trimmed = count( $parts ) > $sentence;
		if ( $trimmed ) {
			array_pop( $parts );
		}

		// Re-join, then normalise the tail to exactly one period (the source may
		// already end in one, and the trailing split element is empty).
		$excerpt = rtrim( implode( '.', $parts ), " ." ) . '.';
		if ( $trimmed ) {
			$excerpt .= $trail;
		}
	} else {
		$excerpt = wp_trim_words( $source_text, $words, $trail );
	}

	if ( $option['link'] ) {
		$excerpt = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url( (string) get_permalink() ),
			esc_attr( (string) get_the_title() ),
			$excerpt
		);
	}

	// $before / $after are developer-supplied raw HTML; callers must escape their own values.
	$excerpt = $option['before'] . $excerpt . $option['after'];

	if ( $option['show'] ) {
		// Deprecated: use the return value and echo at the call site instead.
		_deprecated_argument(
			__FUNCTION__,
			'2.0.0',
			esc_html__( "The 'show' parameter is deprecated. Use the return value and echo at the call site.", 'get-better-excerpt' )
		);
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- caller-supplied before/after; see docblock.
		echo $excerpt;
		return;
	}

	return $excerpt;
}

/**
 * Shortcode handler: [get_better_excerpt].
 *
 * Accepts the same attributes as thisismyurl_get_better_excerpt(), except
 * 'show' is ignored (shortcodes must always return).
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function shortcode_handler( $atts ): string {
	$atts = shortcode_atts(
		array(
			'sentence'    => false,
			'words'       => 20,
			'before'      => '',
			'after'       => '',
			'skipexcerpt' => true,
			'link'        => false,
			'trail'       => ' &#8230;',
		),
		$atts,
		'get_better_excerpt'
	);

	// Force show=false so the template function always returns.
	$atts['show'] = false;

	// Shortcodes are a lower-trust surface than template-tag callers: an author or
	// contributor can supply before/after/trail in post content. Escape them with
	// wp_kses_post so injected <script> is stripped while safe formatting markup survives.
	$atts['before'] = wp_kses_post( (string) $atts['before'] );
	$atts['after']  = wp_kses_post( (string) $atts['after'] );
	$atts['trail']  = wp_kses_post( (string) $atts['trail'] );

	return (string) thisismyurl_get_better_excerpt( $atts );
}
add_shortcode( 'get_better_excerpt', __NAMESPACE__ . '\shortcode_handler' );
