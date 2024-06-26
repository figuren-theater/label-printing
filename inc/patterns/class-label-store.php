<?php
/**
 * Figuren_Theater label_printing Patterns.
 *
 * @package figuren-theater/label-printing
 */

namespace Figuren_Theater\Label_Printing\Patterns;

use WP_Post;

/**
 * This class handles the retrieval, import, and caching of label data.
 *
 * It interfaces with the WordPress database to fetch label information
 * and provides a structured format for label objects.
 */
class Label_Store {

	/**
	 * Get well-formed and already cached Labels.
	 *
	 * @return Label[] An array of Label objects.
	 */
	public function get_labels() : array {

		static::register_wp_core_pattern_category();

		$labels = static::filter_labels();

		// If $labels is still an empty list,
		// nobody has used our filter, so we query our DB instead.
		if ( empty( $labels ) ) {
			$labels = static::get_stored_labels();
		}

		return $labels;
	}

	/**
	 * Get a well-formatted Label object from its WP_Post object.
	 *
	 * @param  int|\WP_Post $post ID of a post or a WP_Post object.
	 *
	 * @return Label|null A full Label object or null if no post, or no post_meta was found.
	 */
	public static function get_label_by_post( int|\WP_Post $post ) : ?Label {

		// Prepare Data.
		$post = \get_post( $post );
		if ( ! $post instanceof \WP_Post ) {
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
	}

	/**
	 * Chance to load your own Labels.
	 *
	 * @return Label[] An array of Label objects.
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
	 * @return Label[] An array of Label objects.
	 */
	public static function get_stored_labels() : array {

		// Check if the value is already stored.
		$stored_labels = \get_transient( TRANSIENT_KEY );

		if ( empty( $stored_labels ) ) {

			// Retrieve posts from DB.
			$stored_labels = static::query_labels();

			// Store for long,
			// because this will be flushed with every new (and updated) 'wp_block' post.
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
	 * @return Label[] An array of Label objects.
	 */
	protected static function query_labels() : array {

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
	 * Get a list of well-formatted Label objects from WP_Query results.
	 *
	 * @param  \WP_Query $query Resulting Query of query_labels().
	 *
	 * @return Label[] An array of Label objects.
	 */
	protected static function label_factory_from_wp_posts( \WP_Query $query ) : array {
		return \array_filter( \array_map(
			function( $post ) {
				return static::get_label_by_post( $post );
			},
			$query->posts
		));
	}

	/**
	 * Import a Label into the DB as new 'wp_block' post.
	 *
	 * @param  string $name Human-readable title of the label.
	 * @param  mixed[] $props List of required label properties: 'width', 'height', 'a4_border_tb', 'a4_border_lr' & 'orientation'.
	 *
	 * @return Label
	 */
	public static function import_bootstrap_label( string $name, array $props ) : Label {
		$label = new Label(
			(string) $name,
			(float) $props['width'],
			(float) $props['height'],
		);

		$label->a4_border_tb = (float) $props['a4_border_tb'];
		$label->a4_border_lr = (float) $props['a4_border_lr'];
		$label->orientation  = (string) $props['orientation'];

		$label->insert();

		return $label;
	}

	/**
	 * Import static defaults into the DB as new 'wp_block' posts.
	 *
	 * @return Label[] An array of Label objects.
	 */
	public static function import_bootstrap_labels() : array {
		return \array_map(
			function( array $label ) : Label {
				return static::import_bootstrap_label( (string) $label['name'], $label );
			},
			static::get_bootstrap_labels()
		);
	}

	/**
	 * Get static default labels.
	 *
	 * @return array<int, array<string, string|int|float>> An array of static default label configurations.
	 */
	public static function get_bootstrap_labels() : array {
		$bootstrap_labels = [
			[
				'name'         => __( 'A6 Landscape', 'label-printing' ),
				'width'        => 148,
				'height'       => 105,
				'a4_border_tb' => 0,
				'a4_border_lr' => 0,
				'orientation'  => 'landscape',
			],
			[
				'name'         => __( 'A6 Landscape (with Top-Bottom-Borders)', 'label-printing' ),
				'width'        => 148,
				'height'       => 90,
				'a4_border_tb' => 15,
				'a4_border_lr' => 0,
				'orientation'  => 'landscape',
			],
			[
				'name'         => __( 'A8 Portrait', 'label-printing' ),
				'width'        => 52.5,
				'height'       => 74,
				'a4_border_tb' => 0,
				'a4_border_lr' => 0,
				'orientation'  => 'portrait',
			],
			[
				'name'         => __( 'A8 Landscape', 'label-printing' ),
				'width'        => 74,
				'height'       => 52.5,
				'a4_border_tb' => 0,
				'a4_border_lr' => 0,
				'orientation'  => 'landscape',
			],
		];

		/**
		 * Add your own labels or adjust the defaults using this filter.
		 *
		 * Use this hook: 'Figuren_Theater\Label_Printing\Patterns\bootstrap_labels'.
		 *
		 * @example Add this before the plugin is loaded.
		 * \add_filter(
		 *     'Figuren_Theater\Label_Printing\Patterns\bootstrap_labels',
		 *     function( array $default_labels ) : array {
		 *         return [
		 *             [
		 *                 'name'         => 'A6 Landscape (4 Stück)',
		 *                 'width'        => 148,
		 *                 'height'       => 105,
		 *                 'a4_border_tb' => 0,
		 *                 'a4_border_lr' => 0,
		 *                 'orientation'  => 'landscape',
		 *             ],
		 *             [
		 *                 'name'         => 'niceday (8 Stück)',
		 *                 'width'        => 105,
		 *                 'height'       => 74,
		 *                 'a4_border_tb' => 0,
		 *                 'a4_border_lr' => 0,
		 *                 'orientation'  => 'portrait',
		 *             ],
		 *             [
		 *                 'name'         => 'HERMA Neon No. 5147 (8 Stück)',
		 *                 'width'        => 96,
		 *                 'height'       => 67,
		 *                 'a4_border_tb' => 14,
		 *                 'a4_border_lr' => 9,
		 *                 'orientation'  => 'portrait',
		 *             ],
		 *             [
		 *                 'name'         => 'LABELident (64 Stück)',
		 *                 'width'        => 48,
		 *                 'height'       => 17,
		 *                 'a4_border_tb' => 13,
		 *                 'a4_border_lr' => 8,
		 *                 'orientation'  => 'portrait',
		 *             ],
		 *         ];
		 *     }
		 * );
		 * @since 0.2.0
		 *
		 * @param array $bootstrap_labels List of Labels (an array of arrays) that will be inserted into the DB on import by default.
		 *
		 * @return array                  List of Labels (an array of arrays) that will be inserted into the DB on import.
		 */
		return \apply_filters(
			__NAMESPACE__ . '\\bootstrap_labels',
			$bootstrap_labels
		);
	}

	/**
	 * Registers 'wp_pattern_category' taxonomy.
	 *
	 * THIS IS COPIED FROM WP CORE AND NEEDS TO BE REMOVED WHEN 6.4 IS SHIPPED!
	 *
	 * @todo #8 Remove custom registration of ˋwp_pattern_categoryˋ
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
					'name'          => _x( 'Pattern Categories', 'taxonomy general name', 'label-printing' ),
					'singular_name' => _x( 'Pattern Category', 'taxonomy singular name', 'label-printing' ),
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
		\register_taxonomy_for_object_type( WP_CORE_PATTERN_TAX, 'wp_block' );
	}
}
