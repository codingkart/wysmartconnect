<?php
/**
 * Header top.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if(flatsome_has_top_bar()['large_or_mobile']){ ?>
<div id="top-bar" class="header-top <?php header_inner_class('top'); ?>">
    <div class="flex-row container">
      <div class="flex-col hide-for-medium flex-left">
          <ul class="nav nav-left medium-nav-center nav-small <?php flatsome_nav_classes('top'); ?>">
              <?php flatsome_header_elements('topbar_elements_left'); ?>
          </ul>
      </div>

      <div class="flex-col hide-for-medium flex-center ">
          <ul class="nav nav-center nav-small <?php flatsome_nav_classes('top'); ?>">
              <?php flatsome_header_elements('topbar_elements_center'); ?>
          </ul>

      </div>

      <div class="flex-col hide-for-medium flex-right shopByColorContainer">
         <ul class="nav top-bar-nav nav-right nav-small <?php flatsome_nav_classes('top'); ?>">
              <?php flatsome_header_elements('topbar_elements_right'); ?>
          </ul>
          <a class="shopByColor" href="javascript:void(0);">
                <img src="/wp-content/themes/flatsome-child/modules/global-module/images/ShopByColor_icon.jpg" alt="">
                <span>Shop by Color</span><i class="sci-arrow-drop-down"></i>
            </a>
      </div>

      <?php if(get_theme_mod('header_mobile_elements_top')) { ?>
      <div class="flex-col show-for-medium flex-grow shopByColorMobile">
          <ul class="nav nav-center nav-small mobile-nav <?php flatsome_nav_classes('top'); ?>">
              <?php flatsome_header_elements('header_mobile_elements_top'); ?>
          </ul>
          <a class="shopByColor" href="javascript:void(0);">
<img src="/wp-content/themes/flatsome-child/modules/global-module/images/ShopByColor_icon.jpg" alt="">
          </a>
      </div>
      <?php } ?>

    </div>
</div>
<?php } ?>
