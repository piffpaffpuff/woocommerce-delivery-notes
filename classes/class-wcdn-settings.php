<?php

/**
 * Settings class
 */
if ( ! class_exists( 'WooCommerce_Delivery_Notes_Settings' ) ) {

	class WooCommerce_Delivery_Notes_Settings {
	
		public $tab_name;
		public $hidden_submit;
		
		/**
		 * Constructor
		 */
		public function __construct() {			
			$this->tab_name = 'woocommerce-delivery-notes';
			$this->hidden_submit = WooCommerce_Delivery_Notes::$plugin_prefix . 'submit';
		}

		/**
		 * Load the class
		 */
		public function load() {
			add_action( 'admin_init', array( $this, 'load_hooks' ) );
		}

		/**
		 * Load the admin hooks
		 */
		public function load_hooks() {	
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ) );
			add_action( 'woocommerce_settings_tabs_' . $this->tab_name, array( $this, 'create_settings_page' ) );
			add_action( 'woocommerce_update_options_' . $this->tab_name, array( $this, 'save_settings_page' ) );
			add_action( 'current_screen', array( $this, 'load_screen_hooks' ) );
			add_action( 'wp_ajax_load_thumbnail', array( $this, 'load_thumbnail_ajax' ) );
		}
		
		/**
		 * Add the scripts
		 */
		public function load_screen_hooks() {
			$screen = get_current_screen();

			if( $this->is_settings_page() ) {
				add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
				add_action( 'admin_print_scripts', array( $this, 'add_scripts' ) );
				add_action( 'load-' . $screen->id, array( $this, 'add_help_tabs' ) );
			}
			
			if( $this->is_media_uploader_page() ) {
				add_filter( 'media_upload_tabs', array( $this, 'remove_media_tabs' ) );
			}
		}

		/**
		 * Add the styles
		 */
		public function add_styles() {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'woocommerce-delivery-notes', WooCommerce_Delivery_Notes::$plugin_url . 'css/style.css' );
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {
			?>
			<script type="text/javascript">
				var show_print_preview = 'yes';
			</script>
			<?php 			
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'woocommerce-delivery-notes', WooCommerce_Delivery_Notes::$plugin_url . 'js/script.js', array( 'jquery', 'media-upload', 'thickbox' ) );
		}
		
		/**
		 * Check if we are on settings page
		 */
		public function is_settings_page() {
			if( isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['tab'] == $this->tab_name ) {
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Check if we are on media uploader page
		 */
		public function is_media_uploader_page() {
			if( isset( $_GET['post_id'] ) && isset( $_GET['company_logo_image'] ) && $_GET['post_id'] == '0' && $_GET['company_logo_image'] == 'true'  ) {
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Check if sequential order plugin is activated
		 */
		public function is_woocommerce_sequential_order_numbers_activated() {
			if ( in_array( 'woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Remove the media uploader tabs
		 */
		public function remove_media_tabs( $tabs ) {
			unset( $tabs['type_url'] );
		    return $tabs;
		}
		
		/**
		 * Add the help tabs
		 */
		public function add_help_tabs() {
			// Check current admin screen
			$screen = get_current_screen();

			// Remove all existing tabs
			$screen->remove_help_tabs();
			
			// Create arrays with help tab titles
			$screen->add_help_tab(array(
				'id' => 'woocommerce-delivery-notes-usage',
				'title' => __( 'About the Plugin', 'woocommerce-delivery-notes' ),
				'content' => 
					'<h3>' . __( 'Plugin: WooCommerce Print Invoices & Delivery Notes', 'woocommerce-delivery-notes' ) . '</h3>' .
					'<h4>' . __( 'About the Plugin', 'woocommerce-delivery-notes' ) . '</h4>' .
					'<p>' . __( 'This plugin enables you to add a Invoice or simple Delivery Note page for printing for your orders in WooCommerce shop plugin. You can add your company postal address, further add personal notes, refund or other policies and a footer note/branding. This helps speed up your daily shop and order management. In some countries (e.g. in the European Union) it is also required to advice the customer with proper refund policies so this little plugin might help you a bit with that too.', 'woocommerce-delivery-notes' ) . '</p>' .
					'<p>' . sprintf( __( 'Just look under <a href="%1$s">WooCommerce > Orders</a> and there go to a single order view. On the right side you will see the Order Print meta box. Click one of the buttons and you get the invoice or delivery note printing page. Yes, it is that easy :-).', 'woocommerce-delivery-notes' ), admin_url( 'edit.php?post_type=shop_order' ) ) . '</p>'
			) );

			// Create help sidebar
			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', 'woocommerce-delivery-notes' ) . '</strong></p>'.
				'<p><a href="http://wordpress.org/extend/plugins/woocommerce-delivery-notes/faq/" target="_blank">' . __( 'Frequently Asked Questions', 'woocommerce-delivery-notes' ) . '</a></p>' .
				'<p><a href="http://wordpress.org/support/plugin/woocommerce-delivery-notes" target="_blank">' . __( 'Get Community Support', 'woocommerce-delivery-notes' ) . '</a></p>' .
				'<p><a href="http://wordpress.org/extend/plugins/woocommerce-delivery-notes/" target="_blank">' . __( 'Project on WordPress.org', 'woocommerce-delivery-notes' ) . '</a></p>' .
				'<p><a href="https://github.com/deckerweb/woocommerce-delivery-notes" target="_blank">' . __( 'Project on GitHub', 'woocommerce-delivery-notes' ) . '</a></p>' 
			);
		}
		
		/**
		 * Add a tab to the settings page
		 */
		public function add_settings_tab($tabs) {
			$tabs[$this->tab_name] = __( 'Print', 'woocommerce-delivery-notes' );
			
			return $tabs;
		}

		/**
		 * Load thumbnail with ajax
		 */
		public function load_thumbnail_ajax() {
			$attachment_id = (int)$_POST['attachment_id']; 
			
			// Verify the id
			if( !$attachment_id ) {
				die();
			}
			
			// create the thumbnail
			$this->create_thumbnail( $attachment_id );
			
			exit;
		}
		
		/**
		 * Create the thumbnail
		 */
		public function create_thumbnail( $attachment_id ) {
			$attachment_src = wp_get_attachment_image_src( $attachment_id, 'full', false );
			
			// resize the image to a 1/4 of the original size
			// to have a printing point density of about 288ppi.
			?>
			<img src="<?php echo $attachment_src[0]; ?>" width="<?php echo $attachment_src[1] / 4; ?>" height="<?php echo $attachment_src[2] / 4; ?>" alt="" />
			<?php
		}

		/**
		 * Create the settings page content
		 */
		public function create_settings_page() {
			?>
			<h3><?php _e( 'Invoices and Delivery Notes', 'woocommerce-delivery-notes' ); ?></h3>
			<table class="form-table">
				<tbody>
					<tr class="hide-if-no-js">
						<?php
						$attachment_id = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_logo_image_id' );
						?>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_logo_image_id"><?php _e( 'Company/Shop Logo', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<input id="company-logo-image-id" type="hidden" name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_logo_image_id" rows="2" class="regular-text" value="<?php echo $attachment_id ?>" />
							<span id="company-logo-placeholder"><?php if( !empty( $attachment_id ) ) : ?><?php $this->create_thumbnail( $attachment_id ); ?><?php endif; ?></span>
							<a href="#" id="company-logo-remove-button" <?php if( empty( $attachment_id ) ) : ?>style="display: none;"<?php endif; ?>><?php _e( 'Remove Logo', 'woocommerce-delivery-notes' ); ?></a>
							<a href="#" <?php if( !empty( $attachment_id ) ) : ?>style="display: none;"<?php endif; ?> id="company-logo-add-button"><?php _e( 'Set Logo', 'woocommerce-delivery-notes' ); ?></a>
							<span class="description">
								<?php _e( 'A company/shop logo representing your business.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e( 'When the image is printed, its pixel density will automatically be eight times higher than the original. This means, 1 printed inch will correspond to about 288 pixels on the screen. Example: an image with a width of 576 pixels and a height of 288 pixels will have a printed size of about 2 inches to 1 inch.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>custom_company_name"><?php _e( 'Company/Shop Name', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>custom_company_name" rows="2" class="large-text"><?php echo wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'custom_company_name' ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Your company/shop name for the Delivery Note.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e( 'Leave blank to use the default Website/ Blog title defined in WordPress settings.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_address"><?php _e( 'Company/Shop Address', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_address" rows="5" class="large-text"><?php echo wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_address' ) ); ?></textarea>
							<span class="description">
								<?php _e( 'The postal address of the company/shop, which gets printed right of the company/shop name, above the order listings.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e('Leave blank to not print an address.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>personal_notes"><?php _e( 'Personal Notes', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>personal_notes" rows="5" class="large-text"><?php echo wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'personal_notes' ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Add some personal notes, or season greetings or whatever (e.g. Thank You for Your Order!, Merry Christmas!, etc.).', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e('Leave blank to not print any personal notes.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>policies_conditions"><?php _e( 'Returns Policy, Conditions, etc.:', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>policies_conditions" rows="5" class="large-text"><?php echo wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'policies_conditions' ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Here you can add some more policies, conditions etc. For example add a returns policy in case the client would like to send back some goods. In some countries (e.g. in the European Union) this is required so please add any required info in accordance with the statutory regulations.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong> 
								<?php _e('Leave blank to not print any policies or conditions.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>footer_imprint"><?php _e( 'Footer Imprint', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>footer_imprint" rows="5" class="large-text"><?php echo wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'footer_imprint' ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Add some further footer imprint, copyright notes etc. to get the printed sheets a bit more branded to your needs.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong> 
								<?php _e('Leave blank to not print a footer.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<?php 
							// show template preview links when an order is available	
							$args = array(
								'post_type' => 'shop_order',
								'posts_per_page' => 1
							);
							$query = new WP_Query( $args );
						
							if($query->have_posts()) : ?>
								<?php
								$results = $query->get_posts();
								$test_id = $results[0]->ID;
								$invoice_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=invoice&order_id=' . $test_id ), 'generate_print_content' );
								$note_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_print_content&template_type=delivery-note&order_id=' . $test_id ), 'generate_print_content' );
								?>
								<span class="description">
									<?php printf( __( 'You can <a href="%1$s" target="%3$s" class="%4$s">preview the invoice template</a> or <a href="%2$s" target="%3$s" class="%4$s">the delivery note template</a>.', 'woocommerce-delivery-notes' ), $invoice_url, $note_url, '_blank', 'print-preview-button' ); ?>
									<?php _e( 'For more advanced control copy <code>woocommerce-delivery-notes/templates/print/style.css</code> to <code>your-theme-name/woocommerce/print/style.css</code>.', 'woocommerce-delivery-notes' ); ?>
								</span>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php _e( 'Order Numbering Options', 'woocommerce-delivery-notes' ); ?></h3>
			<table class="form-table">
				<tbody>	
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>order_number_offset"><?php _e( 'Sequential order number', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<?php if( $this->is_woocommerce_sequential_order_numbers_activated() ) : ?>
								<?php _e( 'Sequential numbering is enabled.', 'woocommerce-delivery-notes' ); ?>
							<?php else : ?>
								<?php printf( __( 'Install and activate the free <a href="%s">WooCommerce Sequential Order Numbers</a> Plugin.', 'woocommerce-delivery-notes' ), 'http://wordpress.org/extend/plugins/woocommerce-sequential-order-numbers/' ); ?>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			
			<input type="hidden" name="<?php echo $this->hidden_submit; ?>" value="submitted">
			<?php
		}
		
		/**
		 * Get the content for an option
		 */
		public function get_setting( $name ) {
			return get_option( WooCommerce_Delivery_Notes::$plugin_prefix . $name );
		}
		
		/**
		 * Save all settings
		 */
		public function save_settings_page() {
			if ( isset( $_POST[ $this->hidden_submit ] ) && $_POST[ $this->hidden_submit ] == 'submitted' ) {
				foreach ( $_POST as $key => $value ) {
					if ( $key != $this->hidden_submit && strpos( $key, WooCommerce_Delivery_Notes::$plugin_prefix ) !== false ) {
						if ( empty( $value ) ) {
							delete_option( $key );
						} else {
							if ( get_option( $key ) && get_option( $key ) != $value ) {
								update_option( $key, $value );
							}
							else {
								add_option( $key, $value );
							}
						}
					}
				}
			}
		}
	
	}
	
}

?>