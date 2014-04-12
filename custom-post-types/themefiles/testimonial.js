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
  */

  $("#bkt-testimonials").jScrollPane({ });
  $(".jspVerticalBar").hide();
  $("#bkt-testimonials .testimonial-slide").last().css('padding','0');


}); // end ready()

