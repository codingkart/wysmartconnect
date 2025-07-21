<style>
    .popupBody::-webkit-scrollbar-thumb {



        background-color: <?= $shortcode_header_bg_color; ?>;



    }

    .popupHeader h2 {

        color: <?= $shortcode_text_color; ?>;
        font-size: <?= $shortcode_text_size ?>px;

    }

    .popupCard p{
        color: <?= $shortcode_options_color; ?>;
    }

    .shopByColor .shop-by-color-title{
        color: <?= $WySMartConnect_shortcode_text_color; ?>;
        font-size: <?= $shop_page_shortcode_title_text_size ?>px;
    }

    .pro_tip_text {

        color: <?= $pro_tip_text_color; ?>;
        font-size: <?= $pro_tip_text_size ?>px;
        margin-top: 5px;
    }

    @media only screen and (max-width: 849px) {
        body ul.nav.nav-uppercase {
            width: 100%;
            justify-content: flex-start;
            justify-content: center;
        }
    }
</style>

<div id="shopByColor" class="white-popup mfp-hide">



    <div class="popupHeader" style="background-color: <?= $shortcode_header_bg_color; ?>;">



        <h2><?= $shortcode_text; ?></h2>
        <p class="pro_tip_text"><?= $pro_tip_text; ?></p>


    </div>



    <div class="popupBody">



        <div class="popupRow">



            <?php if (!empty($colors)) {



                foreach ($colors as $color) { ?>



                    <div class="popupCol popup-color-boxes">



                        <a class="colorToggle popupCard" href="<?php echo site_url(); ?>/shop?query_type_color=or&filter_color=<?= $color['slug']; ?>">



                            <span class="scp-sbc-box" style="background-color:<?= $color['color_code'] ? $color['color_code'] : "" ?>;border:solid 1px #0000003b;box-shadow: 0 1px 5px 0 <?= $color['color_code'] ? $color['color_code'] : "" ?>;"></span>



                            <p><?= $color['name']; ?></p>



                        </a>



                    </div>



                <?php }

                ?>



            <?php } else {

                //echo "Please add colors";

                echo "Colors are not available";
            } ?>







        </div>

        <!-- <a href="javascript:void(0);" style="color: <?php //$shortcode_header_bg_color; ?>;" class="popup-color-load-more">Show More</a> -->

    </div>



</div>