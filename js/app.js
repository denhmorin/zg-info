$(document).ready(function() {

    $('.fadeInRight').whenInViewport(function($paragraph) {
        $paragraph.addClass('inViewport');
    });

    $('.fadeInLeft').whenInViewport(function($paragraph) {
        $paragraph.addClass('inViewport');
    });

    $('.fadeInRightXL').whenInViewport(function($paragraph) {
        $paragraph.addClass('inViewport');
    });

    $('.fadeInLeftXL').whenInViewport(function($paragraph) {
        $paragraph.addClass('inViewport');
    });

    // var elements = Array.prototype.slice.call(document.getElementsByClassName('fadeInRight'));

    // elements.forEach(function(element) {
    //     new WhenInViewport(element, function(elementInViewport) {
    //         elementInViewport.classList.add('inViewport');
    //     });
    // });

    // Create template for download button
    $.fancybox.defaults.btnTpl.download = '<a download class="fancybox-button fancybox-download"></a>';

    // Choose what buttons to display by default
    $.fancybox.defaults.buttons = [
      'slideShow',
      'fullScreen',
      'thumbs',
      'download',
      'close'
    ];

    // Update download link source
    $( '[data-fancybox]' ).fancybox({
        beforeShow : function( instance, current ) {
            $('.fancybox-download').attr('href', current.src);
        }
    });

});