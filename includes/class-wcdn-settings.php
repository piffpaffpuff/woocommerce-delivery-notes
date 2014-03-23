<?php

/**
 * Settings class
 */
if ( !class_exists( 'WooCommerce_Delivery_Notes_Settings' ) ) {

	class WooCommerce_Delivery_Notes_Settings {
			
		public $tab_name;
		public $hidden_submit;
		
		/**
		 * Constructor
		 */
		public function __construct() {		
			// Define default variables
			$this->tab_name = 'woocommerce-delivery-notes';
			$this->hidden_submit = WooCommerce_Delivery_Notes::$plugin_prefix . 'submit';
			
			// Load the hooks
			register_activation_hook( WooCommerce_Delivery_Notes::$plugin_basefile_path, array( $this, 'activation_hooks' ) );
			add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );
		}

		/**
		 * Activation hooks
		 */
		public function activation_hooks() {
			// Define default settings
			$option = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint' );
			if( !$option ) {
				update_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint', 'print-order' );
			}
			$option = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_view_order_page' );
			if( !$option ) {
				update_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_view_order_page', 1 );
			}
		}
		
		/**
		 * Load the admin hooks
		 */
		public function load_admin_hooks() {	
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 100 );
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
				add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );
			}
		}

		/**
		 * Add the styles
		 */
		public function add_styles() {
			wp_enqueue_style( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'css/admin.css' );
		}
		
		/**
		 * Add the scripts
		 */
		public function add_scripts() {		
			wp_enqueue_media();
			wp_enqueue_script( 'woocommerce-delivery-notes-print-link', WooCommerce_Delivery_Notes::$plugin_url . 'js/jquery.print-link.js', array( 'jquery' ) );
			wp_enqueue_script( 'woocommerce-delivery-notes-admin', WooCommerce_Delivery_Notes::$plugin_url . 'js/admin.js', array( 'jquery', 'custom-header', 'woocommerce-delivery-notes-print-link' ) );
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
		 * Add a tab to the settings page
		 */
		public function add_settings_tab( $tabs ) {
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
			$attachment_src = wp_get_attachment_image_src( $attachment_id, 'medium', false );
			
			?>
			<img src="<?php echo $attachment_src[0]; ?>" alt="" />
			<?php
		}
		
		/**
		 * Create the settings page content
		 */
		public function create_settings_page() {
			?>
			<h3><?php _e( 'Print Order Template', 'woocommerce-delivery-notes' ); ?></h3>
			<p>
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
					$invoice_url = wcdn_get_print_permalink( $test_id, 'invoice' );
					$note_url = wcdn_get_print_permalink( $test_id, 'delivery-note' );
					?>
					<input type="hidden" id="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>show_print_preview" />
					<span class="description">
						<?php printf( __( 'You can preview the <a href="%1$s" target="%3$s" class="%4$s">invoice template</a> or <a href="%2$s" target="%3$s" class="%4$s">delivery note template</a>.', 'woocommerce-delivery-notes' ), $invoice_url, $note_url, '_blank', '' ); ?>
						<?php _e( 'To customize the template read the FAQ in the plugins read-me.', 'woocommerce-delivery-notes' ); ?>
					</span>
				<?php endif; ?>
			</p>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label><?php _e( 'Company/Shop Name', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<input type="text" name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>custom_company_name" class="large-text" value="<?php echo stripslashes( wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'custom_company_name' ) ) ); ?>" />
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
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_address" rows="5" class="large-text"><?php echo stripslashes( wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_address' ) ) ); ?></textarea>
							<span class="description">
								<?php _e( 'The postal address of the company/shop, which gets printed right of the company/shop name, above the order listings.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e('Leave blank to not print an address.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr class="hide-if-no-js">
						<?php
						$attachment_id = get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'company_logo_image_id' );
						?>
						<th>
							<label><?php _e( 'Company/Shop Logo', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<input id="company-logo-image-id" type="hidden" name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>company_logo_image_id" class="regular-text" value="<?php echo $attachment_id ?>" />
							<div id="company-logo-placeholder"><?php if( !empty( $attachment_id ) ) : ?><?php $this->create_thumbnail( $attachment_id ); ?><?php endif; ?></div>
							<div id="company-logo-buttons">
								<a href="#" id="company-logo-remove-button" class="button" <?php if( empty( $attachment_id ) ) : ?>style="display: none;"<?php endif; ?>><?php _e( 'Remove Logo', 'woocommerce-delivery-notes' ); ?></a>
								<a href="#" id="company-logo-add-button" class="button" <?php if( !empty( $attachment_id ) ) : ?>style="display: none;"<?php endif; ?> data-uploader-title="<?php _e( 'Set Logo', 'woocommerce-delivery-notes' ); ?>" data-uploader-button-title="<?php _e( 'Set Logo', 'woocommerce-delivery-notes' ); ?>"><?php _e( 'Set Logo', 'woocommerce-delivery-notes' ); ?></a>
								<span id="company-logo-loader" class="spinner"></span>
							</div>
							<span class="description">
								<?php _e( 'A company/shop logo representing your business.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e( 'When the image is printed, its pixel density will automatically be eight times higher than the original. This means, 1 printed inch will correspond to about 288 pixels on the screen. Example: an image with a width of 576 pixels and a height of 288 pixels will have a printed size of about 2 inches to 1 inch.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php _e( 'Thank You', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>personal_notes" rows="5" class="large-text"><?php echo stripslashes( wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'personal_notes' ) ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Add some personal notes, or season greetings or whatever (e.g. Thank You for Your Order!, Merry Christmas!, etc.).', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong>
								<?php _e('Leave blank to not print any personal notes.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php _e( 'Returns Policy, Conditions, etc', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>policies_conditions" rows="5" class="large-text"><?php echo stripslashes( wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'policies_conditions' ) ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Here you can add some more policies, conditions etc. For example add a returns policy in case the client would like to send back some goods. In some countries (e.g. in the European Union) this is required so please add any required info in accordance with the statutory regulations.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong> 
								<?php _e('Leave blank to not print any policies or conditions.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>footer_imprint"><?php _e( 'Footer', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<textarea name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>footer_imprint" rows="5" class="large-text"><?php echo stripslashes( wp_kses_stripslashes( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'footer_imprint' ) ) ); ?></textarea>
							<span class="description">
								<?php _e( 'Add some further footer imprint, copyright notes etc. to get the printed sheets a bit more branded to your needs.', 'woocommerce-delivery-notes' ); ?>
								<strong><?php _e( 'Note:', 'woocommerce-delivery-notes' ); ?></strong> 
								<?php _e('Leave blank to not print a footer.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
				</tbody>
			</table>

			<h3><?php _e( 'Options', 'woocommerce-delivery-notes' ); ?></h3>
			<table class="form-table">
				<tbody>	
					<tr>
						<th>
							<label><?php _e( 'Print Page Endpoint', 'woocommerce-delivery-notes' ); ?></label>
						</th>
						<td>
							<p>
								<input type="text" name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>print_order_page_endpoint" value="<?php echo get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint' ); ?>" />
							</p>
							<span class="description">
								<?php _e( 'Endpoint for the print order button.', 'woocommerce-delivery-notes' ); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th>
							<?php _e( 'Frontend Print Buttons', 'woocommerce-delivery-notes' ); ?>
						</th>
						<td>
							<fieldset>
								<label>
									<input name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>print_button_on_view_order_page" type="hidden" value="" />
									<input name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>print_button_on_view_order_page" type="checkbox" value="1" <?php checked( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_view_order_page' ), 1 ); ?> />
									<?php _e( 'Show print button on the "View Order" page', 'woocommerce-delivery-notes' ); ?>
								</label>
							</fieldset>
							<fieldset>
								<label>
									<input name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>print_button_on_my_account_page" type="hidden" value="" />
									<input name="<?php echo WooCommerce_Delivery_Notes::$plugin_prefix; ?>print_button_on_my_account_page" type="checkbox" value="1" <?php checked( get_option( WooCommerce_Delivery_Notes::$plugin_prefix . 'print_button_on_my_account_page' ), 1 ); ?> />
									<?php _e( 'Show print buttons on the "My Account" page', 'woocommerce-delivery-notes' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h3><?php _e( 'Order Numbering', 'woocommerce-delivery-notes' ); ?></h3>
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
		 * Save all settings
		 */
		public function save_settings_page() {
			if ( isset( $_POST[ $this->hidden_submit ] ) && $_POST[ $this->hidden_submit ] == 'submitted' ) {
				
				// Save settings
				foreach ( $_POST as $key => $value ) {
					if ( $key != $this->hidden_submit && strpos( $key, WooCommerce_Delivery_Notes::$plugin_prefix ) !== false ) {
						// set a default values
						if ( empty( $value ) ) {
							if ( $key == WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint' ) {
								$value = 'print-order';
							}
						}
						
						// sanitize values
						if ( $key == WooCommerce_Delivery_Notes::$plugin_prefix . 'print_order_page_endpoint' ) {
							$value = sanitize_title( $value );
						}
						
						// update the value
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
				
				// Flush permalink structs
				flush_rewrite_rules();
			}
		}
	
	}
	
}

?>