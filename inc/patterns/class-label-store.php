<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

use WP_Post;

const TRANSIENT_KEY = 'label_printing';
const META_KEY      = '_label_printing';

const WP_CORE_PATTERN_TAX = 'wp_pattern_category'; // Avail. from 16.7 / 6.4 !!

const PATTERN_TAX_TERM = 'Label Printing';

/**
 * Handles retrieving, importing and caching of 'synced-patterns' Labels.
 */
class Label_Store {

	/**
	 * Get well-formed and already cached Labels.
	 *
	 * @return Label[]
	 */
	public function get_labels() : array {

		$labels = static::filter_labels();

		// If $labels is still an empty list,
		// nobody has used our filter, so we query our DB instead.
		if ( empty( $labels ) ) {
			$labels = $this->get_stored_labels();
		}

		return $labels;
	}

	/**
	 * Chance to load your own Labels.
	 *
	 * @return Label[]
	 */
	protected static function filter_labels() : array {
		return \apply_filters(
			__NAMESPACE__ . '\\labels',
			[]
		);
	}

	/**
	 * Get stored labels from the database.
	 *
	 * Uses transients to cache simplified WP_Query results.
	 *
	 * @return Label[]
	 */
	function get_stored_labels() : array {

		// Check if the value is already stored.
		$stored_labels = \get_transient( TRANSIENT_KEY );

		if ( empty( $stored_labels ) ) {

			// Retrieve posts from DB.
			$stored_labels = static::query_labels();

			// Store for long,
			// because this will beflushed with every new (and updated) 'wp_block' post.
			\set_transient(
				TRANSIENT_KEY,
				$stored_labels,
				\MONTH_IN_SECONDS
			);
		}

		return $stored_labels;
	}

	/**
	 * Delete cached, db-queried labels.
	 *
	 * @return void
	 */
	public static function delete_transient() : void {
		\delete_transient( TRANSIENT_KEY );
	}

	/**
	 * Get existing 'synced-patterns' posts or imported defaults, if none were queried.
	 *
	 * @return Label[]
	 */
	protected static function query_labels() : array {

		static::register_wp_core_pattern_category();

		$query = new \WP_Query(
			[
				'post_status'    => 'publish',
				'post_type'      => 'wp_block',
				'no_found_rows'  => true, // Useful when pagination is not needed.
				'posts_per_page' => 20,
				'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => WP_CORE_PATTERN_TAX,
						'field'    => 'name',
						'terms'    => PATTERN_TAX_TERM,
					],
				],
			]
		);

		if ( empty( $query->posts ) ) {
			// Run initial import if no results.
			$labels = static::import_bootstrap_labels();
		} else {
			// Transform into a list of Label objects.
			$labels = static::label_factory_from_wp_posts( $query );
		}

		return $labels;
	}

	/**
	 * Get a list of well-formatted Label objects.
	 *
	 * @param  \WP_Query $query Resulting Query of query_labels().
	 *
	 * @return Label[]
	 */
	protected static function label_factory_from_wp_posts( \WP_Query $query ) : array {

		return \array_filter( \array_map(
			function( int|\WP_Post $post ) : Label|null {

				// Prepare Data.
				$post = \get_post( $post );
				if ( ! $post instanceof WP_Post ) {
					return null;
				}

				$meta = \get_post_meta(
					$post->ID,
					META_KEY,
					true
				);
				if ( ! is_array( $meta ) || empty( $meta ) ) {
					return null;
				}

				$label = new Label(
					(string) $post->post_title,
					(float) $meta['width'],
					(float) $meta['height'],
				);

				$label->post_ID = $post->ID;

				$label->a4_border_tb = (float) $meta['a4_border_tb'];
				$label->a4_border_lr = (float) $meta['a4_border_lr'];
				$label->orientation  = (string) $meta['orientation'];

				return $label;
			},
			$query->posts
		));
	}

	/**
	 * Import static defaults into the DB as new 'wp_block' posts.
	 *
	 * @return Label[]
	 */
	public static function import_bootstrap_labels() : array {
		return \array_map(
			function( array $bootstrap_label ) : Label {
				$label = new Label(
					(string) $bootstrap_label['name'],
					(float) $bootstrap_label['width'],
					(float) $bootstrap_label['height'],
				);

				$label->a4_border_tb = (float) $bootstrap_label['a4_border_tb'];
				$label->a4_border_lr = (float) $bootstrap_label['a4_border_lr'];
				$label->orientation  = (string) $bootstrap_label['orientation'];

				$label->insert();

				return $label;
			},
			static::get_bootstrap_labels()
		);
	}

	/**
	 * Get static defaults
	 *
	 * @return array<int, array<string, string|int|float>>
	 */
	public static function get_bootstrap_labels() : array {
		return [
			[
				'name'         => 'A6 with Borders',
				'width'        => 148,
				'height'       => 90,
				'a4_border_tb' => 15,
				'a4_border_lr' => 0,
				'orientation'  => 'landscape',
			],
			[
				'name'         => 'A6',
				'width'        => 148,
				'height'       => 105,
				'a4_border_tb' => 0,
				'a4_border_lr' => 0,
				'orientation'  => 'landscape',
			],
			[
				'name'         => 'A7',
				'width'        => 74,
				'height'       => 52.5,
				'a4_border_tb' => 0,
				'a4_border_lr' => 0,
				'orientation'  => 'portrait',
			],
		];
	}

	/**
	 * THIS IS COPIED FROM WP CORE AND NEEDS TO BE REMOVED WHEN 6.4 IS SHIPPED!
	 *
	 * @see https://github.com/WordPress/gutenberg/pull/53163
	 *
	 * @return void
	 */
	public static function register_wp_core_pattern_category() : void {

		if ( \taxonomy_exists( WP_CORE_PATTERN_TAX ) ) {
			return;
		}

		$args = [
			[
				'public'            => false,
				'hierarchical'      => false,
				'labels'            => [
					'name'          => _x( 'Pattern Categories', 'taxonomy general name' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
					'singular_name' => _x( 'Pattern Category', 'taxonomy singular name' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				],
				'query_var'         => false,
				'rewrite'           => false,
				'show_ui'           => false,
				'_builtin'          => true,
				'show_in_nav_menus' => false,
				'show_in_rest'      => true,
			],
		];
		\register_taxonomy( WP_CORE_PATTERN_TAX, [ 'wp_block' ], $args );

	}
}
