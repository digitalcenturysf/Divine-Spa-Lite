<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.12
 */
if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>
<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>
<form id="yith-wcwl-form" action="<?php echo esc_url( YITH_WCWL()->get_wishlist_url( 'view' . ( $wishlist_meta['is_default'] != 1 ? '/' . $wishlist_meta['wishlist_token'] : '' ) ) ) ?>" method="post" class="woocommerce">
    <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>
    <!-- TITLE -->
    <?php
   // do_action( 'yith_wcwl_before_wishlist_title' );

     do_action( 'yith_wcwl_before_wishlist' ); ?>
    <!-- WISHLIST TABLE -->
    <table class="shop_table cart wishlist_table table-bordered" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo ( is_user_logged_in() ) ? esc_attr( $wishlist_meta['ID'] ) : '' ?>" data-token="<?php echo ( ! empty( $wishlist_meta['wishlist_token'] ) && is_user_logged_in() ) ? esc_attr( $wishlist_meta['wishlist_token'] ) : '' ?>">
	    <?php $column_count = 2; ?>
       <tbody>
        <tr>
	        <?php if( $show_cb ) : ?>
		        <td>
			        <input type="checkbox" value="" name="" id="bulk_add_to_cart"/>
		        </td>
	        <?php
		        $column_count ++;
            endif;
	        ?>

            <td><?php esc_html_e('Image','divine-spa-lite'); ?></td>
            <td>
                <span class="nobr"><?php echo apply_filters( 'yith_wcwl_wishlist_view_name_heading', __( 'Product Name', 'divine-spa-lite' ) ) ?></span>
            </td>
            <?php if( $show_price ) : ?>
                <td>
                    <span class="nobr">
                        <?php echo apply_filters( 'yith_wcwl_wishlist_view_price_heading', __( 'Unit Price', 'divine-spa-lite' ) ) ?>
                    </span>
                </td>
            <?php
	            $column_count ++;
            endif;
            ?>
            <?php if( $show_stock_status ) : ?>
                <td>
                    <span class="nobr">
                        <?php echo apply_filters( 'yith_wcwl_wishlist_view_stock_heading', __( 'Stock Status', 'divine-spa-lite' ) ) ?>
                    </span>
                </td>
            <?php
	            $column_count ++;
            endif;
            ?>
            <?php if( $show_last_column ) : ?>
                <td><?php esc_html_e('Add To Cart','divine-spa-lite'); ?></td>
            <?php
	            $column_count ++;
            endif;
            ?>
            <?php if( $is_user_owner ): ?>
                <td><?php esc_html_e('Remove','divine-spa-lite'); ?></td>
            <?php
                $column_count ++;
            endif;
            ?>
        </tr> 
 
        <?php
        if( count( $wishlist_items ) > 0 ) :
            foreach( $wishlist_items as $item ) :
                global $product;
	            if( function_exists( 'wc_get_product' ) ) {
		            $product = wc_get_product( $item['prod_id'] );
	            }
	            else{
		            $product = get_product( $item['prod_id'] );
	            }
                if( $product !== false && $product->exists() ) :
	                $availability = $product->get_availability();
	                $stock_status = $availability['class'];
	                ?>
                    <tr id="yith-wcwl-row-<?php echo $item['prod_id'] ?>" data-row-id="<?php echo $item['prod_id'] ?>">
	                    <?php if( $show_cb ) : ?>
		                    <td class="product-checkbox">
			                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( $product->product_type != 'simple' ) ? 'disabled="disabled"' : '' ?>/>
		                    </td>
	                    <?php endif ?>

                        <td class="product-thumbnail">
                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
                                <?php echo $product->get_image() ?>
                            </a>
                        </td>
                        <td class="product-name">
                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
                            <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>
                        </td>
                        <?php if( $show_price ) : ?>
                            <td class="product-price">
                                <?php
                                if( is_a( $product, 'WC_Product_Bundle' ) ){
	                                if( $product->min_price != $product->max_price ){
		                                echo sprintf( '%s - %s', wc_price( $product->min_price ), wc_price( $product->max_price ) );
	                                }
	                                else{
		                                echo wc_price( $product->min_price );
	                                }
                                }
                                elseif( $product->price != '0' ) {
	                                echo $product->get_price_html();
                                }
                                else {
                                    echo apply_filters( 'yith_free_text', __( 'Free!', 'divine-spa-lite' ) );
                                }
                                ?>
                            </td>
                        <?php endif ?>
                        <?php if( $show_stock_status ) : ?>
                            <td class="product-stock-status">
                                <?php
                                if( $stock_status == 'out-of-stock' ) {
                                    $stock_status = "Out";
                                    echo '<span class="wishlist-out-of-stock">' . __( 'Out of Stock', 'divine-spa-lite' ) . '</span>';
                                } else {
                                    $stock_status = "In";
                                    echo '<span class="wishlist-in-stock">' . __( 'In Stock', 'divine-spa-lite' ) . '</span>';
                                }
                                ?>
                            </td>
                        <?php endif ?>
	                    <?php if( $show_last_column ): ?>
                        <td class="product-add-to-cart">
	                        <!-- Date added -->
	                        <?php
	                        if( $show_dateadded && isset( $item['dateadded'] ) ):
								echo '<span class="dateadded">' . sprintf( __( 'Added on : %s', 'divine-spa-lite' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
	                        endif;
	                        ?>
	                        <!-- Add to cart button -->
                            <?php if( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'Out' ): ?>
                                <?php
								if( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
									woocommerce_template_loop_add_to_cart();
								}
								else{
									wc_get_template( 'loop/add-to-cart.php' );
								}
                                ?>
                            <?php endif ?>
	                        <!-- Change wishlist -->
							<?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist ): ?>
	                        <select class="change-wishlist selectBox">
		                        <option value=""><?php _e( 'Move', 'divine-spa-lite' ) ?></option>
		                        <?php
		                        foreach( $users_wishlists as $wl ):
			                        if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
				                        continue;
			                        }
		                        ?>
			                        <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
				                        <?php
				                        $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
				                        if( $wl['wishlist_privacy'] == 1 ){
					                        $wl_privacy = __( 'Shared', 'divine-spa-lite' );
				                        }
				                        elseif( $wl['wishlist_privacy'] == 2 ){
					                        $wl_privacy = __( 'Private', 'divine-spa-lite' );
				                        }
				                        else{
					                        $wl_privacy = __( 'Public', 'divine-spa-lite' );
				                        }
				                        echo sprintf( '%s - %s', $wl_title, $wl_privacy );
				                        ?>
			                        </option>
		                        <?php
		                        endforeach;
		                        ?>
	                        </select>
	                        <?php endif; ?>
	                        <!-- Remove from wishlist -->
	                        <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php _e( 'Remove this product', 'divine-spa-lite' ) ?>"><?php _e( 'Remove', 'divine-spa-lite' ) ?></a>
                            <?php endif; ?>
                        </td>
	                <?php endif; ?>
                        <?php if( $is_user_owner ): ?>
                        <td class="product-remove">
                            <div>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>"  title="<?php _e( 'Remove this product', 'divine-spa-lite' ) ?>">X</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php
                endif;
            endforeach;
        else: ?>
            <tr>
                <td colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php _e( 'No products were added to the wishlist', 'divine-spa-lite' ) ?></td>
            </tr>
        <?php
        endif;
        if( ! empty( $page_links ) ) : ?>
            <tr class="pagination-row">
                <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo $page_links ?></td>
            </tr>
        <?php endif ?>
        </tbody>

    </table>
    <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>
    <?php if( $wishlist_meta['is_default'] != 1 ): ?>
        <input type="hidden" value="<?php echo $wishlist_meta['wishlist_token'] ?>" name="wishlist_id" id="wishlist_id">
    <?php endif; ?>
    <?php do_action( 'yith_wcwl_after_wishlist' ); ?>
</form>
<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>
<?php if( $additional_info ): ?>
	<div id="ask_an_estimate_popup">
		<form action="<?php echo $ask_estimate_url ?>" method="post" class="wishlist-ask-an-estimate-popup">
			<?php if( ! empty( $additional_info_label ) ):?>
				<label for="additional_notes"><?php echo esc_html( $additional_info_label ) ?></label>
			<?php endif; ?>
			<textarea id="additional_notes" name="additional_notes"></textarea>
			<button class="btn button ask-an-estimate-button ask-an-estimate-button-popup" >
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
				<?php _e( 'Ask for an estimate', 'divine-spa-lite' ) ?>
			</button>
		</form>
	</div>
<?php endif; ?>