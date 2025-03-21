<?php

// Meta-Box
// How to use: $meta_value = get_post_meta( $post_id, $field_id, true );

class EnwooOptionsMetabox {

	private $screens = array( 'post', 'page' );
	private $fields	 = array(
		array(
			'label'	 => 'Hide Title',
			'id'	 => 'envo_extra_hide_title',
			'type'	 => 'checkbox',
		),
		array(
			'label'	 => 'Hide Sidebar',
			'id'	 => 'envo_extra_hide_sidebar',
			'type'	 => 'checkbox',
		),
		array(
			'label'	 => 'Transparent Header',
			'id'	 => 'envo_extra_transparent_header',
			'type'	 => 'checkbox',
		),
		array(
            'label' => 'Transparent Header Text Color',
            'id' => 'envo_extra_header_text_color',
            'type' => 'color',
            'default' => '#000',
        ),
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}
	
	public function admin_head() {
		global $typenow;
		if ( in_array( $typenow, $this->screens ) ) {
			?><script>
				jQuery.noConflict();
				(function($) {
					$(document).ready(function () {
						if (document.getElementById('envo_extra_transparent_header').checked) {
								 $("#envo_extra_header_text_color").closest("tr").show()
							} else {
								$("#envo_extra_header_text_color").closest("tr").hide()
							}
						$('#envo_extra_transparent_header').on('change', function (e) {
							if (e.currentTarget.checked) {
								 $("#envo_extra_header_text_color").closest("tr").show()
							} else {
								$("#envo_extra_header_text_color").closest("tr").hide()
							}
						})
					})
				})
				(jQuery);
			</script><?php
			?><?php
		}
	}

	public function add_meta_boxes() {
		foreach ( $this->screens as $s ) {
			add_meta_box(
			'EnwooOptions', esc_html__( 'Envo Options', 'envo-extra' ), array( $this, 'meta_box_callback' ), $s, 'side', 'high'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'EnwooOptions_data', 'EnwooOptions_nonce' );
		esc_html_e( 'Custom page options', 'envo-extra' );
		$this->field_generator( $post );
	}

	public function field_generator( $post ) {
		$output = '';
		$allowed_html = array(
			'input'      => array(
				'id'  => array(),
				'name' => array(),
				'type' => array(),
				'value' => array(),
				'checked' => array(),
				'onclick' => array(),
			),
			'label'      => array(
				'for'  => array(),
			),
			'strong' => array(),
			'tr' => array(),
			'td' => array(),
		);
		foreach ( $this->fields as $field ) {
			$label		 = '<label for="' . esc_attr($field[ 'id' ]) . '">' . esc_html($field[ 'label' ]) . '</label>';
			$meta_value	 = get_post_meta( $post->ID, $field[ 'id' ], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $field[ 'default' ] ) ) {
					$meta_value = $field[ 'default' ];
				}
			}
			switch ( $field[ 'type' ] ) {
				case 'checkbox':
					$input = sprintf(
					'<input %1$s id="%2$s" name="%3$s" type="checkbox" value="on">', $meta_value === 'on' ? 'checked' : '', esc_attr($field[ 'id' ]), esc_attr($field[ 'id' ])
					);
					break;
				default:
					$input = sprintf(
					'<input %1$s id="%2$s" name="%3$s" type="%4$s" value="%5$s">', $field[ 'type' ] !== 'color' ? 'style="width: 100%"' : '', esc_attr($field[ 'id' ]), esc_attr($field[ 'id' ]), esc_attr($field[ 'type' ]), esc_attr($meta_value)
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . wp_kses($output, $allowed_html) . '</tbody></table>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function format_rows( $label, $input ) {
		return '<tr><td><strong>' . $label . '</strong></td><td>' . $input . '</td><tr>';
	}

	public function save_fields( $post_id ) {
		if ( !isset( $_POST[ 'EnwooOptions_nonce' ] ) ) {
			return $post_id;
		}
		$nonce = ( isset( $_POST[ 'EnwooOptions_nonce' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'EnwooOptions_nonce' ] ) ) : '' );
		if ( !wp_verify_nonce( $nonce, 'EnwooOptions_data' ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		foreach ( $this->fields as $field ) {
			switch ( $field['type'] ) {
				case 'checkbox':
					update_post_meta( $post_id, $field['id'], isset( $_POST[ $field['id'] ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field['id'] ])) : '' );
					break;
				default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( wp_unslash($_POST[ $field['id'] ]) );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
			}
		}
	}

}

if ( class_exists( 'EnwooOptionsMetabox' ) ) {
	new EnwooOptionsMetabox;
};
