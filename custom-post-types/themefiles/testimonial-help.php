<?php
?>
<script>
jQuery(document).ready(function($){
  $('.inside.toggle').hide();
  $('.postbox .hndle, .postbox .handlediv').click(function() {
    $(this).siblings('.inside.toggle').toggle('slow');
    return false;
  });
});
</script>
<style>
  .postbox { margin:20px 40px 15px 0; }
  .postbox .hndle { padding:10px; margin:0; }
</style>


<h1>Solamar Testimonials Help</h1>

<div class="inside">
  <h2>Overview</h2>

  <p>The Solamar Testimonial plugin allows you to easily add and manage testimonials to your Wordpress website.</p>

  <p>This plugin lets you do three things:</p>
  <ol>
    <li><a href="#create">Create and manage testimonials</a></li>
    <li><a href="#page">Add testimonials to a Page or a Post with a shortcode</a></li>
    <li><a href="#sidebar">Add testimonials to the Sidebar as a widget</a></li>
  </ol>
</div>

<a name="create"></a>
&nbsp;
<div id="testimonial-manage" class="postbox">
  <div class="handlediv" title="Click to toggle"> <br> </div>
  <h3 class="hndle">Create and manage testimonials</h3>
  <div class="inside">

    <p>Testimonials are managed in much the same was as a standard Page or Post, in that you edit them in the same way.</p>

    <ul class="ul-disc">
      <li>The testimonial has the same features as a page or post.</li>
      <li>There is a listing of all testimonials with the additional feature of being able to see the categories that were assigned to each testimonial.</li>
      <li>You can Edit, Quick Edit, Trash, or View any given testimonial. </li>
      <li>You can add and manage categories that are specifically for testimonials</li>
    </ul>

    <h4>Adding testimonials</h4>
    <p>To add a testimonial, simply choose 'Add Testimonial' as you would a Page or a Post.</p>

    <h4>Editing testimonials</h4>

    <p>The Editing screen of the testimonial is pretty much like a standard Page or Post, however there is a new section at the bottom of the page, called "Testimonial Details". This is where you put all the special information.</p>

    <ul class="ul-disc">
      <li><strong>Testimonial Author:</strong> &mdash; This is the Author's name and appears in the 'credits' section below the testimonial.</li>
      <li><strong>Testimonial Headline:</strong> &mdash; This is the header of the testimonial and is used as an intro to the testimonial.</li>
      <li><strong>Author Title:</strong> &mdash; This is the Job Title of the Author. </li>
      <li><strong>Author Company:</strong> &mdash; This is the Author's company name.</li>
      <li><strong>Author URL:</strong> &mdash; This is the website of the Author.</li>
      <li><strong>Author Thumbnail Title:</strong> &mdash; This is a caption that will appear below the Author's thumbnail image.</li>
      <li><strong>Author Thumbnail Image:</strong> &mdash; This is the Author's thumbnail image.</li>
      <li><strong>DELETE the current Testimonial Thumbnail?</strong> &mdash; If you want to remove this thumbnail all together, just check this box to delete it.</li>
    </ul>
  </div>
</div>

<a name="page"></a>
&nbsp;
<div id="testimonial-page" class="postbox">
  <div class="handlediv" title="Click to toggle"> <br> </div>
  <h3 class="hndle">Add Testimonials to a Page or a Post with a shortcode</h3>
  <div class="inside">

    <p>You can insert shortcode into the content of a page or a post using the shortcode described below.  Please do not insert the examples below, but change them to suit your needs.</p>

    <p>The basic shortcode will insert ALL testimonials and looks like this:<p>

    <pre>
      [soltestimonial]
    </pre>

    <p>In all cases, the testimonials are sorted by their order in the testimonial list.</p>

    <p>There are a few variables that you can use to select just the testimonials you want:</p>

    <ul class="ul-disc">
      <li><strong>category</strong>
        <ul>
          <li>Description: This will pull only the testimonials with the given category.</li>
          <li>Options: Enter the slug of the category, not the name. The slug can be found in the category list and is usally the name with dashes.</li>
        </ul>
      </li>
      <li><strong>name</strong>
        <ul>
          <li>Description: This will pull the testimonial with the given Name of the testimonial. It's ideal for using when you just want to put one individual testimonial somewhere.</li>
          <li>Options: Be sure that it matches the name of the Testimonial as seen at the top of the editing screen, or in the list of testimonials.</li>
        </ul>
      </li>
      <li><strong>show_thumbnail</strong>
        <ul>
        <li>Description: This will allow you to display or hide all thumbnails.</li>
        <li>Options: On or Off.</li>
        </ul>
      </li>
    </ul>

    <p><strong>Variable priorities:</strong> You can combine these variables, however there is one limitation set, in that if you set a Category, it will override any Name you set.  Here are some code examples</p>

    <p>
    This will show all testimonials with the category, 'some-category'.<br /> 
    <pre>
      [soltestimonial category="some-category"]
    </pre>
    </p>


    <p>
    This will show all testimonials with the category 'some-category' and will NOT show the thumbnails.<br />
    <pre>
      [soltestimonial category="some-category" show_thumbnail="off"]
    </pre>
    </p>

    <p>
    This will show the testimonial name 'John Smith'.<br />
    <pre>
    [soltestimonial name="John Smith"]
    </pre>
    </p>

    <p>
    When the category option is set, the name option will be ignored.  In this case, it will simply show all testimonials from the category 'some-category', and the request for 'John Smith' will be ignored.
    <pre>
    [soltestimonial name="John Smith" category="some-category"]
    </pre>
    </p>
  </div>
</div>

<a name="sidebar"></a>
&nbsp;
<div id="testimonial-sidebar" class="postbox">
  <div class="handlediv" title="Click to toggle"> <br> </div>
  <h3 class="hndle">Add testimonials to the Sidebar as a widget</h3>
  <div class="inside">

    <p>This plugin comes with a pre-built, re-usable widget that you can place into any sidebar.  The widget is titled '<strong>TestimonialWidget</strong>' and can be found in the 'Available Widgets' section of the Widgets screen.</p>

    <p>Simply drag the widget over to the desired sidebar and update the settings as follows:</p>

    <ul>
    <li><h4>Testimonial Title</h4>
      <ul class="ul-disc">
        <li><strong>Description:</strong> This is the title that appears above the testimonial widget.</li>
        <li><strong>Options:</strong> Enter the desired text.</li>
      </ul>
    </li>
    <li><h4>Testimonial Category</h4>
      <ul class="ul-disc">
        <li><strong>Description:</strong> This is the category slug that you'd like to display.</li>
        <li><strong>Options:</strong> Enter the slug of the category you'd like to display.  Leaving it blank will cause ALL widgets to be displayed.</li>
      </ul>
    </li>
    <li><h4>Exclude from page</h4>
      <ul class="ul-disc">
        <li><strong>Description:</strong> This is the slug of your testimonial page, or some other page that you don't want the widget to show up on.  If you put the 'slug' of this page here, this widget will NOT be displayed on this page.</li>
        <li><strong>Options:</strong> Simply enter the page slug that you'd like the sidebar to be excluded from.</li>
        <li><strong>Notes:</strong> If you've got the permalinks set to the default setting, where pages look like this... http://somesite.com/?page_id=2, this won't work.  Set the permalinks to one of the other options that generates a more readable URL. It's better for SEO purposes as well.</li>
      </ul>
    </li>
    <li><h4>Show Thumbnail?</h4>
      <ul class="ul-disc">
        <li><strong>Description:</strong> This checkbox allows you to turn thumbnails on or off.</li>
        <li><strong>Options:</strong> Check the box to turn thumbnails on.</li>
      </ul>
    </li>
    <li><h4>Slideshow Speed</h4>
      <ul class="ul-disc">
        <li><strong>Description:</strong> This plugin comes enabled with a javascript slideshow feature for the widget.  You can manage the amount of time one has to read the testimonial, as well as the amount of time the transition from one slide to the next occurs.</li>
        <li><strong>Options:</strong>Enter a whole number, which will represent seconds.</li>
      </ul>
    </li>
    </ul>
  </div>
</div>
