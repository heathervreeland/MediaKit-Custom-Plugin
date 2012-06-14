jQuery(document).ready(function($){

  /* find the tallest testimonial */
  /*
  var tallest = 0;

  $('#testimonial-slideshow .testimonial-slide').each(function() {

    if ($(this).outerHeight() > tallest) {
      tallest = $(this).outerHeight();
    }

    $('#testimonial-slideshow .testimonial-slide').height(tallest);
    $('#testimonial-slideshow').height(tallest);

  });

  // remove the border from below the last testimonial listing
  $('#solamar-testimonials .testimonial-wrapper').last().find('.testimonial-bottom').css('border-bottom','none');
  */

  $("#bkt-testimonials").jScrollPane({ });
  $(".jspVerticalBar").hide();
  $("#bkt-testimonials .testimonial-slide").last().css('padding','0');


}); // end ready()

