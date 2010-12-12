=== Rate ===
Contributors: wonderboymusic
Tags: ratings, rate, comments, posts, pages, manage, reviews, metadata, comment karma
Requires at least: 3.0
Tested up to: 3.0
Stable Tag: 0.3

Ratings: clean, lightweight and easy

== Description ==

Most ratings plugins contain too much code: inline JavaScript, messy markup, weird CSS. Rate is simple, hardly intrusive, and completely overridable. 

A Post/Page/Custom Post Type's rating is the average of all comment ratings. A user can leave a rating when commenting, and change that rating inline after leaving a comment (if logged-in or Cookie'd).

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

= 0.3 =
* Added jQuery as a required script - Whoops!

= 0.2.1.1 = 
* Whoops, adds second argument to <code>rate_calculate($id = 0, $is_comment = false)</code> for internal purposes

= 0.2.1 =
* <code>the_rating()</code> now excludes ratings from comments that are awaiting moderation. <code>the_rating($id = 0)</code> will not take an argument of ID. Use it to show a rating anywhere.

= 0.2 = 
* User can leave a rating while commenting now, can still edit rating inline after comment is posted

= 0.1.4 =
* I broke the rate.css path, oops!

= 0.1.2 =
* Got rid of Divide by Zero warning that PHP was throwing
* Does not count ratings of Zero or non-ratings in the Average Rating displayed by the_rating()
* Added screenshots to the Plugin page at WordPress.org

= 0.1 =
* Initial release

== Upgrade Notice ==