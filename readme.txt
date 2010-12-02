=== Rate ===
Contributors: wonderboymusic
Tags: ratings, rate, comments, posts, pages, manage, reviews, metadata, comment karma
Requires at least: 3.0
Tested up to: 3.0
Stable Tag: 0.1.3

Ratings: clean, lightweight and easy

== Description ==
BETA RELEASE ... in case anything breaks :)

Most ratings plugins contain too much code: inline JavaScript, messy markup, weird CSS. Rate is simple, hardly intrusive, and completely overridable. 

A Post/Page/Custom Post Type's rating is the average of all comment ratings. The user must have left a comment to rate an item, but they don't have to be logged in - this is a compromise I made to reduce complexity (I may add standalone ratings in 0.2) and to make sure the use doesn't rate an item and leave the comment text blank.

Don't be afraid to play around and extend the code: drop a <code>rate.css</code> file in your theme directory and mine won't even load (by default, Rate stars are transparent with a white border, so you can use <code>background-color</code> to set your stars' colors)!

<code>
<?php 
// in this version, you need to insert these functions into your theme for ratings to appear
// you don't have to use the_rating(), the comment_rating() will work by itself, but the_rating() will not

// for a Post, Page, or Custom Post Type (average of all comment ratings)
the_rating();

// for a comment
the_comment_rating();
?>
</code>

Read More: http://scottctaylor.wordpress.com/2010/11/30/new-plugin-rate/

== Screenshots ==
1. The top rating is an average of all of the comment ratings

2. You can add the_rating() anywhere that has comments attached to it using the Theme editor

3. You can add ratings to comments in the Twenty Ten or any other Theme by adding the_comment_rating() to the custom comment callback located in functions.php using the Theme Editor

4. You can edit the CSS for Rate right in the Plugin Editor. Choose "Rate" from the dropdown, then select rate/css/rate.css to edit the styles right in the Editor

== Changelog ==

= 0.1.2 =
* Got rid of Divide by Zero warning that PHP was throwing
* Does not count ratings of Zero or non-ratings in the Average Rating displayed by the_rating()
* Added screenshots to the Plugin page at WordPress.org

= 0.1 =
* Initial release

== Upgrade Notice ==