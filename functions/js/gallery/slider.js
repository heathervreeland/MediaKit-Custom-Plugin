jQuery(document).ready(function($) { 

  $("#home-slider .gallery-index-wrapper").cycle({
    speed:  '2000', 
    timeout:  8000,
    pause:  1,
    pager:  '#slider-nav',
    pagerAnchorBuilder: function(idx, slide) { 
      return '#slider-nav li:eq(' + idx + ') a'; 
    } 
    /*
    */
  }); 

}); 

