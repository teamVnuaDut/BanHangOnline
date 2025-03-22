<?php

namespace WPDesk\FS\Plugin;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

class PluginLinks implements Hookable {

	private $plugin_file;

	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	public function hooks() {
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( $plugin_file === $this->plugin_file ) {
			$docs_link    = get_locale() === 'pl_PL' ? 'https://octol.io/fs-docs-pl' : 'https://octol.io/fs-docs';
			$support_link = get_locale() === 'pl_PL' ? 'https://octol.io/fs-support-pl' : 'https://octol.io/fs-support';

			$plugin_links = [
				'<a target="_blank" href="' . esc_url( $docs_link ) . '">' . __( 'Docs', 'flexible-shipping' ) . '</a>',
				'<a target="_blank" href="' . esc_url( $support_link ) . '">' . __( 'Support', 'flexible-shipping' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $plugin_links );
		}

		return $plugin_meta;
	}

}
