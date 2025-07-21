<?php
/**
 * Category layout with left sidebar.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<!-- <div class="colorFilterContainer">
    <div class="container">
		<div class="colorFilterBox">
		<div class="popupHeader">
			<h2>Shop by Color</h2>
			<div class="filterHeaderAction">
				<a href="javascript:void(0);" class="filterColorBtn">Filter Colors</a>
				<a href="javascript:void(0);" class="resetBtn">reset</a>
			</div>
		</div>
    <div class="popupBody">
        <div class="popupRow">
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box selectedColor"style="background-color:#000000;"></span>
                    <p>Black</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#800020;"></span>
                    <p>Burgundy</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box selectedColor"style="background-color:#16333E;"></span>
                    <p>Caribbean</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#3d291e;"></span>
                    <p>Chocolate</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#7d8ac2;"></span>
                    <p>Ciel Blue</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#052170;"></span>
                    <p>Galaxy Blue</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#AAB1B9;"></span>
                    <p>Grey</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#FF69B4;"></span>
                    <p>Hot Pink</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#164734;"></span>
                    <p>Hunter</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#D1BEA7;"></span>
                    <p>Khaki</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#21273e;"></span>
                    <p>Navy</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#60674c;"></span>
                    <p>Olive</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#383843;"></span>
                    <p>Pewter</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#FFC0CB;"></span>
                    <p>Pink</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#36246D;"></span>
                    <p>Purple</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#BC1111;"></span>
                    <p>Red</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#005097;"></span>
                    <p>Royal Blue</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#00937D;"></span>
                    <p>Surgical Green</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#008993;"></span>
                    <p>Teal</p>
                </a>
            </div>

            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#63b6ea;"></span>
                    <p>Turquoise</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#FFFFFF;"></span>
                    <p>White</p>
                </a>
            </div>
            <div class="popupCol">
                <a class="colorToggle popupCard" href="<?php echo site_url();?>/shop">
                    <span class="scp-sbc-box"style="background-color:#722F37;"></span>
                    <p>Wine</p>
                </a>
            </div>
        </div>
    </div>
		</div>
	</div>
</div> -->
<div class="row category-page-row">
		<div class="col large-3 hide-for-medium <?php flatsome_sidebar_classes(); ?>">
			<?php flatsome_sticky_column_open( 'category_sticky_sidebar' ); ?>
			<div id="shop-sidebar" class="sidebar-inner col-inner">
				<?php
				  if(is_active_sidebar('shop-sidebar')) {
				  		dynamic_sidebar('shop-sidebar');
				  	} else{ echo '<p>You need to assign Widgets to <strong>"Shop Sidebar"</strong> in <a href="'.get_site_url().'/wp-admin/widgets.php">Appearance > Widgets</a> to show anything here</p>';
				  }
				?>
			</div>
			<?php flatsome_sticky_column_close( 'category_sticky_sidebar' ); ?>
		</div>

		<div class="col large-9">
		<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 (FL removed)
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );

		?>

		<?php
		/**
		 * Hook: woocommerce_archive_description.
		 *
		 * @hooked woocommerce_taxonomy_archive_description - 10
		 * @hooked woocommerce_product_archive_description - 10
		 */
		do_action( 'woocommerce_archive_description' );
		?>

		<?php

		if ( woocommerce_product_loop() ) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked wc_print_notices - 10
			 * @hooked woocommerce_result_count - 20 (FL removed)
			 * @hooked woocommerce_catalog_ordering - 30 (FL removed)
			 */
			do_action( 'woocommerce_before_shop_loop' );

			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
		}
		?>

		<?php
			/**
			 * Hook: flatsome_products_after.
			 *
			 * @hooked flatsome_products_footer_content - 10
			 */
			do_action( 'flatsome_products_after' );
			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>
		</div>
</div>
