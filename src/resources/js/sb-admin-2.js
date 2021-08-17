(function () {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  window.$("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
    window.$("body").toggleClass("sidebar-toggled");
    window.$(".sidebar").toggleClass("toggled");
    if (window.$(".sidebar").hasClass("toggled")) {
      window.$('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  window.$(window).resize(function () {
    if (window.$(window).width() < 768) {
      window.$('.sidebar .collapse').collapse('hide');
    };

    // Toggle the side navigation when window is resized below 480px
    if (window.$(window).width() < 480 && !window.$(".sidebar").hasClass("toggled")) {
      window.$("body").addClass("sidebar-toggled");
      window.$(".sidebar").addClass("toggled");
      window.$('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  window.$('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if (window.$(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  window.$(document).on('scroll', function () {
    var scrollDistance = window.$(this).scrollTop();
    if (scrollDistance > 100) {
      window.$('.scroll-to-top').fadeIn();
    } else {
      window.$('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  window.$(document).on('click', 'a.scroll-to-top', function (e) {
    var anchor = window.$(this);
    window.$('html, body').stop().animate({
      scrollTop: (window.$(anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict
