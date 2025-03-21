<?php
/**
 * Add Theme info Page
 */

function creazione_fse_menu() {
	add_theme_page( esc_html__( 'Creazione FSE', 'creazione-fse' ), esc_html__( 'About Creazione FSE', 'creazione-fse' ), 'edit_theme_options', 'about-creazione-fse', 'creazione_fse_theme_page_display' );
}
add_action( 'admin_menu', 'creazione_fse_menu' );

function creazione_fse_admin_theme_style() {
	wp_enqueue_style('creazione-fse-custom-admin-style', esc_url(get_template_directory_uri()) . '/assets/css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'creazione_fse_admin_theme_style');

/**
 * Display About page
 */
function creazione_fse_theme_page_display() {
	$theme = wp_get_theme();

	if ( is_child_theme() ) {
		$theme = wp_get_theme()->parent();
	} ?>

		<div class="Grace-wrapper">
			<div class="Grcae-info-holder">
				<div class="Grcae-info-holder-content">
					<div class="Grace-Welcome">
						<h1 class="welcomeTitle"><?php esc_html_e( 'About Theme Info', 'creazione-fse' ); ?></h1>                        
						<div class="featureDesc">
							<?php echo esc_html__( 'The Creazione FSE is a free Luxury WordPress theme for architecture, building, construction, interior design, decorator, furniture, interior art and dcor, interior planners and renovators.', 'creazione-fse' ); ?>
						</div>
						
                        <h1 class="welcomeTitle"><?php esc_html_e( 'Theme Features', 'creazione-fse' ); ?></h1>

                        <h2><?php esc_html_e( 'Block Compatibale', 'creazione-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'creazione-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'Responsive Ready', 'creazione-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'creazione-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'Cross Browser Compatible', 'creazione-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'creazione-fse' ); ?>
                        </div>
                        
                        <h2><?php esc_html_e( 'E-commerce', 'creazione-fse' ); ?></h2>
                        <div class="featureDesc">
                            <?php echo esc_html__( 'Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'creazione-fse' ); ?>
                        </div>

					</div> <!-- .Grace-Welcome -->
				</div> <!-- .Grcae-info-holder-content -->
				
				
				<div class="Grcae-info-holder-sidebar">
                        <div class="sidebarBX">
                            <h2 class="sidebarBX-title"><?php echo esc_html__( 'Get Creazione PRO', 'creazione-fse' ); ?></h2>
                            <p><?php echo esc_html__( 'More features availbale on Premium version', 'creazione-fse' ); ?></p>
                            <a href="<?php echo esc_url( 'https://gracethemes.com/themes/luxury-interior-wordpress-theme/' ); ?>" target="_blank" class="button"><?php esc_html_e( 'Get the PRO Version &rarr;', 'creazione-fse' ); ?></a>
                        </div>


						<div class="sidebarBX">
							<h2 class="sidebarBX-title"><?php echo esc_html__( 'Important Links', 'creazione-fse' ); ?></h2>

							<ul class="themeinfo-links">
                                <li>
									<a href="<?php echo esc_url( 'https://gracethemesdemo.com/creazione/' ); ?>" target="_blank"><?php echo esc_html__( 'Demo Preview', 'creazione-fse' ); ?></a>
								</li>                               
								<li>
									<a href="<?php echo esc_url( 'https://gracethemesdemo.com/documentation/creazione/#homepage-lite' ); ?>" target="_blank"><?php echo esc_html__( 'Documentation', 'creazione-fse' ); ?></a>
								</li>
								
								<li>
									<a href="<?php echo esc_url( 'https://gracethemes.com/wordpress-themes/' ); ?>" target="_blank"><?php echo esc_html__( 'View Our Premium Themes', 'creazione-fse' ); ?></a>
								</li>
							</ul>
						</div>

						<div class="sidebarBX">
							<h2 class="sidebarBX-title"><?php echo esc_html__( 'Leave us a review', 'creazione-fse' ); ?></h2>
							<p><?php echo esc_html__( 'If you are satisfied with Creazione FSE, please give your feedback.', 'creazione-fse' ); ?></p>
							<a href="https://wordpress.org/support/theme/creazione-fse/reviews/" class="button" target="_blank"><?php esc_html_e( 'Submit a review', 'creazione-fse' ); ?></a>
						</div>

				</div><!-- .Grcae-info-holder-sidebar -->	

			</div> <!-- .Grcae-info-holder -->
		</div><!-- .Grace-wrapper -->
<?php } ?>