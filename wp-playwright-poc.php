<?php
/**
 * Plugin Name: WP Playwright POC
 * Description: A simple reviews/testimonials display plugin for QA automation testing proof of concept.
 * Version: 1.0.0
 * Author: Zach Tirrell
 * Text Domain: wp-playwright-poc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPPOC_VERSION', '1.0.0' );
define( 'WPPOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPPOC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Default settings.
 */
function wppoc_default_settings() {
	return array(
		'layout'      => 'grid',
		'columns'     => 3,
		'max_items'   => 6,
		'show_stars'  => true,
		'show_date'   => true,
		'bg_color'    => '#ffffff',
		'text_color'  => '#333333',
		'star_color'  => '#f5a623',
	);
}

/**
 * Get plugin settings.
 */
function wppoc_get_settings() {
	$defaults = wppoc_default_settings();
	$saved    = get_option( 'wppoc_settings', array() );
	return wp_parse_args( $saved, $defaults );
}

/**
 * Sample testimonials data.
 */
function wppoc_get_testimonials() {
	return array(
		array(
			'name'    => 'Sarah Johnson',
			'rating'  => 5,
			'date'    => '2026-01-15',
			'text'    => 'Absolutely love this product! It has completely transformed how I manage my workflow.',
			'avatar'  => 'https://i.pravatar.cc/80?img=1',
		),
		array(
			'name'    => 'Mike Chen',
			'rating'  => 4,
			'date'    => '2026-01-20',
			'text'    => 'Great tool with excellent support. Minor UI quirks but overall fantastic.',
			'avatar'  => 'https://i.pravatar.cc/80?img=3',
		),
		array(
			'name'    => 'Emily Davis',
			'rating'  => 5,
			'date'    => '2026-02-01',
			'text'    => 'Best purchase I have made this year. Easy to set up and works perfectly.',
			'avatar'  => 'https://i.pravatar.cc/80?img=5',
		),
		array(
			'name'    => 'James Wilson',
			'rating'  => 3,
			'date'    => '2026-02-05',
			'text'    => 'Decent product. Does what it says but could use more customization options.',
			'avatar'  => 'https://i.pravatar.cc/80?img=7',
		),
		array(
			'name'    => 'Lisa Martinez',
			'rating'  => 5,
			'date'    => '2026-02-08',
			'text'    => 'Incredible value for money. The team behind this really cares about quality.',
			'avatar'  => 'https://i.pravatar.cc/80?img=9',
		),
		array(
			'name'    => 'David Brown',
			'rating'  => 4,
			'date'    => '2026-02-10',
			'text'    => 'Very impressed with the features. Documentation could be better but the product is solid.',
			'avatar'  => 'https://i.pravatar.cc/80?img=11',
		),
		array(
			'name'    => 'Anna Taylor',
			'rating'  => 5,
			'date'    => '2026-02-12',
			'text'    => 'A must-have! Seamless integration and beautiful output.',
			'avatar'  => 'https://i.pravatar.cc/80?img=13',
		),
		array(
			'name'    => 'Robert Lee',
			'rating'  => 4,
			'date'    => '2026-02-14',
			'text'    => 'Solid plugin. Works exactly as described with no performance issues.',
			'avatar'  => 'https://i.pravatar.cc/80?img=15',
		),
	);
}

/**
 * Register admin menu.
 */
function wppoc_admin_menu() {
	add_options_page(
		'WP Playwright POC',
		'WP Playwright POC',
		'manage_options',
		'wp-playwright-poc',
		'wppoc_settings_page'
	);
}
add_action( 'admin_menu', 'wppoc_admin_menu' );

/**
 * Register settings.
 */
function wppoc_register_settings() {
	register_setting( 'wppoc_settings_group', 'wppoc_settings', 'wppoc_sanitize_settings' );
}
add_action( 'admin_init', 'wppoc_register_settings' );

/**
 * Sanitize settings.
 */
function wppoc_sanitize_settings( $input ) {
	$sanitized = array();
	$sanitized['layout']     = in_array( $input['layout'], array( 'grid', 'list' ), true ) ? $input['layout'] : 'grid';
	$sanitized['columns']    = absint( $input['columns'] );
	$sanitized['max_items']  = absint( $input['max_items'] );
	$sanitized['show_stars'] = ! empty( $input['show_stars'] );
	$sanitized['show_date']  = ! empty( $input['show_date'] );
	$sanitized['bg_color']   = sanitize_hex_color( $input['bg_color'] ) ?: '#ffffff';
	$sanitized['text_color'] = sanitize_hex_color( $input['text_color'] ) ?: '#333333';
	$sanitized['star_color'] = sanitize_hex_color( $input['star_color'] ) ?: '#f5a623';
	return $sanitized;
}

/**
 * Settings page HTML.
 */
function wppoc_settings_page() {
	$settings = wppoc_get_settings();
	?>
	<div class="wrap" id="wppoc-settings">
		<h1>WP Playwright POC Settings</h1>

		<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ) : ?>
			<div class="notice notice-success is-dismissible" id="wppoc-save-notice">
				<p>Settings saved successfully.</p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'wppoc_settings_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="wppoc-layout">Layout</label></th>
					<td>
						<select name="wppoc_settings[layout]" id="wppoc-layout">
							<option value="grid" <?php selected( $settings['layout'], 'grid' ); ?>>Grid</option>
							<option value="list" <?php selected( $settings['layout'], 'list' ); ?>>List</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wppoc-columns">Columns (Grid only)</label></th>
					<td>
						<input type="number" name="wppoc_settings[columns]" id="wppoc-columns" value="<?php echo esc_attr( $settings['columns'] ); ?>" min="1" max="6" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wppoc-max-items">Max Items</label></th>
					<td>
						<input type="number" name="wppoc_settings[max_items]" id="wppoc-max-items" value="<?php echo esc_attr( $settings['max_items'] ); ?>" min="1" max="8" />
					</td>
				</tr>
				<tr>
					<th scope="row">Show Stars</th>
					<td>
						<label>
							<input type="checkbox" name="wppoc_settings[show_stars]" id="wppoc-show-stars" value="1" <?php checked( $settings['show_stars'] ); ?> />
							Display star ratings
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Show Date</th>
					<td>
						<label>
							<input type="checkbox" name="wppoc_settings[show_date]" id="wppoc-show-date" value="1" <?php checked( $settings['show_date'] ); ?> />
							Display review date
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wppoc-bg-color">Background Color</label></th>
					<td>
						<input type="text" name="wppoc_settings[bg_color]" id="wppoc-bg-color" value="<?php echo esc_attr( $settings['bg_color'] ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wppoc-text-color">Text Color</label></th>
					<td>
						<input type="text" name="wppoc_settings[text_color]" id="wppoc-text-color" value="<?php echo esc_attr( $settings['text_color'] ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wppoc-star-color">Star Color</label></th>
					<td>
						<input type="text" name="wppoc_settings[star_color]" id="wppoc-star-color" value="<?php echo esc_attr( $settings['star_color'] ); ?>" class="regular-text" />
					</td>
				</tr>
			</table>

			<?php submit_button( 'Save Settings' ); ?>
		</form>

		<hr />
		<h2>Shortcode</h2>
		<p>Use <code>[wppoc_reviews]</code> to display testimonials on any page or post.</p>
	</div>
	<?php
}

/**
 * Enqueue frontend styles.
 */
function wppoc_enqueue_styles() {
	wp_enqueue_style( 'wppoc-frontend', WPPOC_PLUGIN_URL . 'assets/css/frontend.css', array(), WPPOC_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wppoc_enqueue_styles' );

/**
 * Shortcode: [wppoc_reviews]
 */
function wppoc_shortcode( $atts ) {
	$settings     = wppoc_get_settings();
	$testimonials = wppoc_get_testimonials();
	$testimonials = array_slice( $testimonials, 0, $settings['max_items'] );

	$layout_class = 'wppoc-layout-' . esc_attr( $settings['layout'] );
	$columns      = $settings['layout'] === 'grid' ? $settings['columns'] : 1;

	ob_start();
	?>
	<div class="wppoc-container <?php echo $layout_class; ?>"
		 style="--wppoc-columns: <?php echo (int) $columns; ?>; --wppoc-bg: <?php echo esc_attr( $settings['bg_color'] ); ?>; --wppoc-text: <?php echo esc_attr( $settings['text_color'] ); ?>; --wppoc-star: <?php echo esc_attr( $settings['star_color'] ); ?>;"
		 data-testid="wppoc-container"
		 data-layout="<?php echo esc_attr( $settings['layout'] ); ?>">

		<?php foreach ( $testimonials as $index => $testimonial ) : ?>
			<div class="wppoc-card" data-testid="wppoc-card">
				<div class="wppoc-card-header">
					<img class="wppoc-avatar" src="<?php echo esc_url( $testimonial['avatar'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" loading="lazy" />
					<div class="wppoc-meta">
						<span class="wppoc-name" data-testid="wppoc-name"><?php echo esc_html( $testimonial['name'] ); ?></span>
						<?php if ( $settings['show_date'] ) : ?>
							<span class="wppoc-date" data-testid="wppoc-date"><?php echo esc_html( date( 'M j, Y', strtotime( $testimonial['date'] ) ) ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $settings['show_stars'] ) : ?>
					<div class="wppoc-stars" data-testid="wppoc-stars" aria-label="<?php echo (int) $testimonial['rating']; ?> out of 5 stars">
						<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							<span class="wppoc-star <?php echo $i <= $testimonial['rating'] ? 'wppoc-star-filled' : 'wppoc-star-empty'; ?>">★</span>
						<?php endfor; ?>
					</div>
				<?php endif; ?>

				<p class="wppoc-text" data-testid="wppoc-text"><?php echo esc_html( $testimonial['text'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wppoc_reviews', 'wppoc_shortcode' );
