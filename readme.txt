=== Rate ===
Contributors: wonderboymusic
Tags: ratings, rate, comments, posts, pages, manage, reviews, metadata, comment karma
Requires at least: 3.0
Tested up to: 3.0
Stable Tag: 0.1.1

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

== Changelog ==

= 0.1 =
* Initial release

== Upgrade Notice ==