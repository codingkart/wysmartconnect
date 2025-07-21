//comman ajax function for all modules

let loaderElement = '<div class="overlay" id="ck_ajax_loader"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>';

jQuery('body').prepend(loaderElement);

const ajaxHanlder = (formData, loaderId = false, successCallback, beforeSendCallback, method = 'POST') => {
  jQuery.ajax({
    type: method,
    url: globalModuleObj.ajaxurl,
    data: formData,
    beforeSend: function () {
      loaderId ? showLoader(loaderId) : "";
      beforeSendCallback()
    },
    error: function () {
      alert('Error: Something went wrong.');
    },
    success: function (response) {
      successCallback(response);
    },
    complete: function () {
      loaderId ? hideLoader(loaderId) : "";
    }
  });
}
// Set the function in the window object

window.ajaxHanlder = ajaxHanlder;
/**
 * Show Loader 
 * @param ID
 */

const showLoader = (loaderId) => {
  jQuery(`#${loaderId}`).show();
}
/**
 * hide Loader 
 * @param ID
 */

const hideLoader = (loaderId) => {
  jQuery(`#${loaderId}`).hide();
}

jQuery(document).ready(function () {
  jQuery(document).on('click', '.shopByColor', function () {
    jQuery.magnificPopup.open({
      items: {
        src: '#shopByColor', // can be a HTML string, jQuery object, or CSS selector
        type: 'inline'
      }
    });
  })
});

jQuery(document).ready(function ($) {
  // Function to show/hide color boxes
  function manageColorBoxes(boxesToShow = 12) {
    const top_colors = globalModuleObj.top_colors.length;
    const totalBoxes = $('.popupBody .popup-color-boxes').length/2;
    // if (!boxesToShow) {
    //   boxesToShow = (top_colors > 0) ? top_colors : 12;
    // }
    // const boxesToShow = 12;
    const visibleBoxes = $('.popup-color-boxes:visible').length;
    console.log("boxesToShow")
    console.log(boxesToShow)
    console.log(visibleBoxes)
    console.log(totalBoxes)
    // Show the first 4 hidden boxes
    $('.popup-color-boxes:hidden').slice(0, boxesToShow).show();
    // If all boxes are visible, hide the "Show More" button
    if (parseInt(boxesToShow) == parseInt(totalBoxes)) {
      $('.popup-color-load-more').hide();
    }
  }
  manageColorBoxes();
  // Initially show the first 4 color boxes
  // On click of "Show More" button, show 4 more color boxes
  $('.popup-color-load-more').click(function () {
    manageColorBoxes($('.popup-color-boxes').length);
  });
  $(document).on("click", ".shopByColorContainer", function () {
    //set timeout to wait for the popup
    setTimeout(function () {
      manageColorBoxes();
    }, 100);
  })
});