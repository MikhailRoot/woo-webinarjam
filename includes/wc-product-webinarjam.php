<?php
/**
 * Class which defines WC Product type.
 *
 * @package woo-webinarjam;
 */

defined( 'ABSPATH' ) || exit;

/**
 * Product WC_Product_Webinarjam class
 */
class WC_Product_Webinarjam extends WC_Product {

	/**
	 * WC_Product_Webinarjam constructor.
	 *
	 * @param int|WC_Product|object $product Product to init.
	 */
	public function __construct( $product ) {

		$this->product_type = 'webinarjam';
		$this->supports[]   = 'ajax_add_to_cart';
		parent::__construct( $product );

	}

	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @return string
	 */
	public function add_to_cart_url() {

		$url = $this->is_purchasable() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );

		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
	}

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text() {
		$text = $this->is_purchasable() ? __( 'Add to cart', 'woocommerce' ) : __( 'Read More', 'woocommerce' );

		return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
	}

	/**
	 * Get the title of the post.
	 *
	 * @return string
	 */
	public function get_title() {
	    // TODO update to most recent woocommerce.
		$title = $this->post->post_title;

		if ( $this->get_parent() > 0 ) {
			$title = get_the_title( $this->get_parent() ) . ' &rarr; ' . $title;
		}

		return apply_filters( 'woocommerce_product_title', $title, $this );
	}

}
