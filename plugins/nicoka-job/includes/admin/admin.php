<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin Nicoka
 *
 * @package Nicoka Job
 * @since 1.0.0
 */
class Admin {


	/**
	 * Our Settings.
	 *
	 * @var array Settings.
	 */
	protected $settings = array();

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 */
	private static $_instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings_group = 'nicoka_job';
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		
	}

	/**
	 * Initializes the configuration for the plugin's setting fields.
	 *
	 * @access protected
	 */
	protected function init_settings() {
		// Prepare roles option.
		$roles         = get_editable_roles();
		$account_roles = array();

		foreach ( $roles as $key => $role ) {
			if ( 'administrator' === $key ) {
				continue;
			}
			$account_roles[ $key ] = $role['name'];
		}

		$this->settings = apply_filters(
			'nicoka_job_settings',
			array(
				'general'        => array(
					__( 'Général', 'nicoka-job' ),
					array(
						array(
							'name'       => 'nicoka_job_token_api',
							'std'        => '',
							'label'      => __( 'Token d\'acces', 'nicoka-job' ),
							'type'		=> 'password',
							// translators: Placeholder %s is URL to set up a Google Maps API key.
							'desc'       =>  __( 'Token d\'acces disponible dans l\'interface administrateur de Nicoka', 'nicoka-job' ),
							'attributes' => array(),
						),
						array(
							'name'       => 'nicoka_job_url_api',
							'std'        => '',
							'label'      => __( 'URL de l\'instance Nicoka', 'nicoka-job' ),
							// translators: Placeholder %s is URL to set up a Google Maps API key.
							'desc'       =>  __( 'URL de l\'instance Nicoka (https://omeva.nicoka.com/)', 'nicoka-job' ),
							'attributes' => array(),
						),
                        array(
                            'name'       => 'nicoka_form_url_confidential_politic',
                            'std'        => '',
                            'label'      => __( 'URL de la page politique de confidentialité', 'nicoka-job' ),
                            'type'		 => 'page',
                            // translators: Placeholder %s is URL to set up a Google Maps API key.
                            'desc'       =>  __( 'Nécessaire pour le formulaire de candidature', 'nicoka-job' ),
                            'attributes' => array(),
                        ),
                        array(
                            'name'       => 'nicoka_form_url_condition_utilisation',
                            'std'        => '',
                            'label'      => __( "URL de la page de condition d'utilisation", 'nicoka-job' ),
                            'type'		 => 'page',
                            // translators: Placeholder %s is URL to set up a Google Maps API key.
                            'desc'       =>  __( 'Nécessaire pour le formulaire de candidature', 'nicoka-job' ),
                            'attributes' => array(),
                        ),
                        array(
                            'name'       => 'nicoka_index_page_job',
                            'std'        => '',
                            'label'      => __( 'URL de la page des postes disponibles', 'nicoka-job' ),
                            'type'		 => 'page',
                            // translators: Placeholder %s is URL to set up a Google Maps API key.
                            'desc'       =>  __( 'Utiliser dans les pages de poste pour revenir à la liste.', 'nicoka-job' ),
                            'attributes' => array(),
                        ),
                        array(
                            'name'       => 'nicoka_published_job',
                            'std'        => '',
                            'label'      => __( 'Afficher les postes publiés', 'nicoka-job' ),
                            'type'		=> 'checkbox',
                            // translators: Placeholder %s is URL to set up a Google Maps API key.
                            'desc'       =>  __( 'Si coché, affiche uniquement les postes publiés dans l\'interface de Nicoka. Si non coché, affiche les postes non publié.', 'nicoka-job' ),
                            'attributes' => array(),
                        ),
					)
				),
				'recaptcha'        => array(
					__( 'Recaptcha', 'nicoka-job' ),
					array(
						array(
							'name'       => 'nicoka_job_captcha_site_key',
							'std'        => '',
							'label'      => __( 'Clef du site', 'nicoka-job' ),
							// translators: Placeholder %s is URL to set up a Google Maps API key.
							'desc'       =>  __( 'Clef du site disponible sur la console <a href="https://www.google.com/recaptcha/admin">Console captcha Google</a>', 'nicoka-job' ),
							'attributes' => array(),
						),
						
						array(
							'name'       => 'nicoka_job_captcha_secret_key',
							'std'        => '',
							'label'      => __( 'Clef secrète', 'nicoka-job' ),
							'type'		=> 'password',
							// translators: Placeholder %s is URL to set up a Google Maps API key.
							'desc'       => __('Clef secrète de vérification disponible sur la console <a href="https://www.google.com/recaptcha/admin">Console captcha Google</a>', 'nicoka-job' ),
							'attributes' => array(),
						)
					)
				)
			)
		);
	}
	/**
	 * Registers the plugin's settings with WordPress's Settings API.
	 */
	public function register_settings() {
		$this->init_settings();

		foreach ( $this->settings as $section ) {
			foreach ( $section[1] as $option ) {
				if ( isset( $option['std'] ) ) {
					add_option( $option['name'], $option['std'] );
				}
				register_setting( $this->settings_group, $option['name'] );
			}
		}
	}

	/**
	 * Shows the plugin's settings page.
	 */
	public function output() {
		$this->init_settings();
		?>
		<div class="wrap settings-wrap">
			<form class="nicoka-options" method="post" action="options.php">

				<?php settings_fields( $this->settings_group ); ?>

				<h2 class="nav-tab-wrapper">
					<?php
					foreach ( $this->settings as $key => $section ) {
						echo '<a href="#settings-' . esc_attr( sanitize_title( $key ) ) . '" class="nav-tab">' . esc_html( $section[0] ) . '</a>';
					}
					?>
				</h2>

				<?php
				if ( ! empty( $_GET['settings-updated'] ) ) {
					flush_rewrite_rules();
					echo '<div class="updated fade nicoka-updated"><p>' . esc_html__( 'Settings successfully saved', 'nicoka-job' ) . '</p></div>';
				}

				foreach ( $this->settings as $key => $section ) {
					$section_args = isset( $section[2] ) ? (array) $section[2] : array();
					echo '<div id="settings-' . esc_attr( sanitize_title( $key ) ) . '" class="settings_panel">';
					if ( ! empty( $section_args['before'] ) ) {
						echo '<p class="before-settings">' . wp_kses_post( $section_args['before'] ) . '</p>';
					}
					echo '<table class="form-table settings parent-settings">';

					foreach ( $section[1] as $option ) {
						$value = get_option( $option['name'] );
						$this->output_field( $option, $value );
					}

					echo '</table>';
					if ( ! empty( $section_args['after'] ) ) {
						echo '<p class="after-settings">' . wp_kses_post( $section_args['after'] ) . '</p>';
					}
					echo '</div>';

				}
				?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'nicoka-job' ); ?>" />
				</p>
			</form>
		</div>
		<script type="text/javascript">
			jQuery('.nav-tab-wrapper a').click(function() {
				if ( '#' !== jQuery(this).attr( 'href' ).substr( 0, 1 ) ) {
					return false;
				}
				jQuery('.settings_panel').hide();
				jQuery('.nav-tab-active').removeClass('nav-tab-active');
				jQuery( jQuery(this).attr('href') ).show();
				jQuery(this).addClass('nav-tab-active');
				window.location.hash = jQuery(this).attr('href');
				jQuery( 'form.nicoka-options' ).attr( 'action', 'options.php' + jQuery(this).attr( 'href' ) );
				window.scrollTo( 0, 0 );
				return false;
			});

			var goto_hash = window.location.hash;

			if ( '#' === goto_hash.substr( 0, 1 ) ) {
				jQuery( 'form.nicoka-options' ).attr( 'action', 'options.php' + jQuery(this).attr( 'href' ) );
			}

			if ( goto_hash ) {
				var the_tab = jQuery( 'a[href="' + goto_hash + '"]' );
				if ( the_tab.length > 0 ) {
					the_tab.click();
				} else {
					jQuery( '.nav-tab-wrapper a:first' ).click();
				}
			} else {
				jQuery( '.nav-tab-wrapper a:first' ).click();
			}


			jQuery( '.sub-settings-expander' ).on( 'change', function() {
				var $expandable = jQuery(this).parent().siblings( '.sub-settings-expandable' );
				var checked = jQuery(this).is( ':checked' );
				if ( checked ) {
					$expandable.addClass( 'expanded' );
				} else {
					$expandable.removeClass( 'expanded' );
				}
			} ).trigger( 'change' );

		</script>
		<?php
	}

	/**
	 * Adds pages to admin menu.
	 */
	public function admin_menu() {
		add_menu_page('Nicoka Jobs Configuration', 'Nicoka Jobs', 'manage_options', 'nicoka_job',  array($this, 'output') );
	}

	/**
	 * Checkbox input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_checkbox( $option, $attributes, $value, $ignored_placeholder ) {
		?>
		<label>
		<input type="hidden" name="<?php echo esc_attr( $option['name'] ); ?>" value="0" />
		<input
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			type="checkbox"
			value="1"
			<?php
			echo implode( ' ', $attributes ) . ' '; 
			checked( '1', $value );
			?>
		/> <?php echo wp_kses_post( $option['cb_label'] ); ?></label>
		<?php
		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Text area input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_textarea( $option, $attributes, $value, $placeholder ) {
		?>
		<textarea
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			class="large-text"
			cols="50"
			rows="3"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; 
			echo $placeholder; 
			?>
		>
			<?php echo esc_textarea( $value ); ?>
		</textarea>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Select input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_select( $option, $attributes, $value, $ignored_placeholder ) {
		?>
		<select
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			class="regular-text"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			<?php
			echo implode( ' ', $attributes ); 
			?>c
		>
		<?php
		foreach ( $option['options'] as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>';
		}
		?>
		</select>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Radio input field.
	 *
	 * @param array  $option
	 * @param array  $ignored_attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_radio( $option, $ignored_attributes, $value, $ignored_placeholder ) {
		?>
		<fieldset>
		<legend class="screen-reader-text">
		<span><?php echo esc_html( $option['label'] ); ?></span>
		</legend>
		<?php
		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}

		foreach ( $option['options'] as $key => $name ) {
			echo '<label><input name="' . esc_attr( $option['name'] ) . '" type="radio" value="' . esc_attr( $key ) . '" ' . checked( $value, $key, false ) . ' />' . esc_html( $name ) . '</label><br>';
		}
		?>
		</fieldset>
		<?php
	}

	/**
	 * Page input field.
	 *
	 * @param array  $option
	 * @param array  $ignored_attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_page( $option, $ignored_attributes, $value, $ignored_placeholder ) {
		$args = array(
			'name'             => $option['name'],
			'id'               => $option['name'],
			'sort_column'      => 'menu_order',
			'sort_order'       => 'ASC',
			'show_option_none' => __( '--no page--', 'nicoka-job' ),
			'echo'             => false,
			'selected'         => absint( $value ),
		);

		echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'nicoka-job' ) . "' id=", wp_dropdown_pages( $args ) ); 

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Hidden input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_hidden( $option, $attributes, $value, $ignored_placeholder ) {
		$human_value = $value;
		if ( $option['human_value'] ) {
			$human_value = $option['human_value'];
		}
		?>
		<input
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			type="hidden"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ); 
			?>
		/><strong><?php echo esc_html( $human_value ); ?></strong>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Password input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_password( $option, $attributes, $value, $placeholder ) {
		?>
		<input
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			class="regular-text"
			type="password"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; 
			echo $placeholder; 
			?>
		/>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Number input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_number( $option, $attributes, $value, $placeholder ) {
		echo isset( $option['before'] ) ? wp_kses_post( $option['before'] ) : '';
		?>
		<input
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			class="small-text"
			type="number"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; 
			echo $placeholder; 
			?>
		/>
		<?php
		echo isset( $option['after'] ) ? wp_kses_post( $option['after'] ) : '';
		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Text input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_text( $option, $attributes, $value, $placeholder ) {
		?>
		<input
			id="setting-<?php echo esc_attr( $option['name'] ); ?>"
			class="regular-text"
			type="text"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; 
			echo $placeholder; 
			?>
		/>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}

	/**
	 * Outputs the field row.
	 *
	 * @param array $option
	 * @param mixed $value
	 */
	protected function output_field( $option, $value ) {
		$placeholder    = ( ! empty( $option['placeholder'] ) ) ? 'placeholder="' . esc_attr( $option['placeholder'] ) . '"' : '';
		$class          = ! empty( $option['class'] ) ? $option['class'] : '';
		$option['type'] = ! empty( $option['type'] ) ? $option['type'] : 'text';
		$attributes     = array();
		if ( ! empty( $option['attributes'] ) && is_array( $option['attributes'] ) ) {
			foreach ( $option['attributes'] as $attribute_name => $attribute_value ) {
				$attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		echo '<tr valign="top" class="' . esc_attr( $class ) . '">';

		if ( ! empty( $option['label'] ) ) {
			echo '<th scope="row"><label for="setting-' . esc_attr( $option['name'] ) . '">' . esc_html( $option['label'] ) . '</a></th><td>';
		} else {
			echo '<td colspan="2">';
		}

		$method_name = 'input_' . $option['type'];
		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $option, $attributes, $value, $placeholder );
		} else {
			/**
			 * Allows for custom fields in admin setting panes.
			 *
			 *
			 * @param string $option     Field name.
			 * @param array  $attributes Array of attributes.
			 * @param mixed  $value      Field value.
			 * @param string $value      Placeholder text.
			 */
			do_action( 'nicoka_admin_field_' . $option['type'], $option, $attributes, $value, $placeholder );
		}
		echo '</td></tr>';
	}

	/**
	 * Multiple settings stored in one setting array that are shown when the `enable` setting is checked.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param array  $values
	 * @param string $placeholder
	 */
	protected function input_multi_enable_expand( $option, $attributes, $values, $placeholder ) {
		echo '<div class="setting-enable-expand">';
		$enable_option               = $option['enable_field'];
		$enable_option['name']       = $option['name'] . '[' . $enable_option['name'] . ']';
		$enable_option['type']       = 'checkbox';
		$enable_option['attributes'] = array( 'class="sub-settings-expander"' );
		$this->input_checkbox( $enable_option, $enable_option['attributes'], $values[ $option['enable_field']['name'] ], null );

		echo '<div class="sub-settings-expandable">';
		$this->input_multi( $option, $attributes, $values, $placeholder );
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Multiple settings stored in one setting array.
	 *
	 * @param array  $option
	 * @param array  $ignored_attributes
	 * @param array  $values
	 * @param string $ignored_placeholder
	 */
	protected function input_multi( $option, $ignored_attributes, $values, $ignored_placeholder ) {
		echo '<table class="form-table settings child-settings">';
		foreach ( $option['settings'] as $sub_option ) {
			$value              = isset( $values[ $sub_option['name'] ] ) ? $values[ $sub_option['name'] ] : $sub_option['std'];
			$sub_option['name'] = $option['name'] . '[' . $sub_option['name'] . ']';
			$this->output_field( $sub_option, $value );
		}
		echo '</table>';
	}

	/**
	 * Proxy for text input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_input( $option, $attributes, $value, $placeholder ) {
		$this->input_text( $option, $attributes, $value, $placeholder );
	}

}

Admin::instance();

?>