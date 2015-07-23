=== Plugin Name ===
Contributors: eugenyh
Tags: widget, posts, plugin, recent, recent posts, video, latest, latest posts, shortcode, thumbnail, thumbnails, categories, content, featured image, Taxonomy, custom post type, custom
Requires at least: 3.5
Tested up to: 4.2.3
Stable tag: 0.6.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin that shows the latest posts with thumbnails in the widget and in other parts of the your blog or theme with shortcodes.

== Description ==

**Introducing new feature in 0.6.13 version: the embedded video instead of the Post Featured Image**

You can use the embedded video (first movie) instead of the Post Featured Image in Responsive Grid Layout. This feature is experimental and now the plugin support Youtube video only. If you have any ideas about this feature or it work not properly, please write me in special topic on [Support Forum](https://wordpress.org/support/topic/new-feature-in-0613-the-embedded-video-instead-of-the-post-featured-image).

See the [live demo of this feature](http://demo.lp-tricks.com/recent-posts/responsive-grid-light/).

In version 0.6.4 added new color scheme (both widgets and shortcode) - natural image colors, without any overlay :) If you choose this scheme - add in files lptw-recent-posts.css or style.css in your theme some styles, read more in the FAQ.

Advanced Recent Posts plugin shows the latest posts with thumbnails in two areas:

* widget in your sidebar
* shortcode in any place of your post or theme.

Customization of the plugin is wery simple an flexible:

* Widgets are configured into Dashboard -> Appearance -> Widgets
* Shortcodes are configured in plugin shortcode builder, you will see it in the admin menu of your WordPress

There are two predefined color schemes (for Basic and Grid layouts): dark and light, but you can set up your own scheme. Or use natural images.

Better to see once than read a hundred times - see the [live demo](http://demo.lp-tricks.com/) on my website :)

Like my plugin? Please, rate :) &#9733;&#9733;&#9733;&#9733;&#9733;

**Contributing**

You can make suggestions and submit your own modifications to this plugin on [Github](https://github.com/eholin/WP-Advanced-Recent-Posts).

For more information and support, please visit [my website](http://lp-tricks.com).

Have any ideas or suggestions? Please write me - i will try to make them in the new version of the plugin.

== Installation ==

1. Upload "advanced-recent-posts" folder to the "/wp-content/plugins/" directory
1. Activate the plugin through the Plugins menu into WordPress admin area
1. Use plugin shortcode builder to create a shortcode and use it a in your posts
1. Add Widgets on your Sidebar into Dashboard -> Appearance -> Widgets

== Frequently Asked Questions ==

= How to show images without any overlay =

In version 0.6.4 added new color scheme (both widgets and shortcode) - natural image colors, without any overlay :) If you choose this scheme - add in files lptw-recent-posts.css or style.css in your theme the following styles:

* .lptw_recent_posts_fluid_images_widget .title-no-overlay - style for header in the widget
* .lptw_recent_posts_fluid_images_widget .date-no-overlay - style for date in the widget
* .basic-layout .title-no-overlay - style for header in the Basic layout
* .basic-layout .date-no-overlay - style for date in the Basic layout
* .grid-element-no-overlay - style for grid element in the Grid layout, need to set the background color here
* .grid-layout .title-no-overlay - style for header in the Grid layout
* .grid-layout .date-no-overlay style for header in the Grid layout
* .grid-layout .content-no-overlay - style for text in the Grid layout
* .grid-layout.w3 .title-no-overlay - style for header of the Featured Post in the Grid layout
* .grid-layout.w3 .date-no-overlay - style for date of the Featured Post in the Grid layout

== Screenshots ==

1. This screenshot shows the settings widget: Recent posts - Fluid images
2. This is this widget on the website
3. This screenshot shows the settings widget: Recent posts - Thumbnails
4. This is this widget on the website
5. This is shortcode builder window with resulting shortcode
6. This is result of shortcode on the website

== Changelog ==

= 0.6.13 =
* Added embedded video support. Now embedded video can be displayed instead of Featured Image in Responsive Grid Layout
* Fixed bug with styles and scripts in Widgets Management
* Fixed issue with disappearing the link to another plugin in WordPress Menu

= 0.6.12 =
* Added the Post Excerpt settings: now you can show or hide an Excerpt of all Posts in the Responsive Grid, also you can set the Post Excerpt lenght and use or ignore &lt;!-- more --&gt; tag.
* Added new settings of Post height in the Responsive Grid:
  * Height of a Featured Post
  * The minimal height of all Posts
* Fixed issue with incorect height of elements of the Responsive Grid in Chrome and Safari
* Fixed issue with incorrect work of two or more shortcodes on the page

= 0.6.11 =
* Added text color setting for Basic Layout
* Added text and background color settings for Responsive Grid Layout

= 0.6.10 =
* Added sorting of the posts by the following parameters:
  * Title
  * Name (post slug)
  * Date created
  * Date modified
  * Random
  * Number of comments
* Fixed issue with incorrect arrange of elements in the Basic Layout

= 0.6.9 =
* Added the ability to filter the posts by tags (only in a shortcode, in widgets this ability will be available in the next version). You can include or exclude post by a specific tag or multiple tags. Now only work with posts.
* Fixed issue with fixed height of a element in mobile version of the Responsive Grid layout.
* Fixed issue with compatibility with Woocommerce (when columns has the same styles names)

= 0.6.8 = 
* Fixed issue with incorrect column size in Responsive Grid

= 0.6.7 =
* Responsive Grid now fully responsive! You can set the width and the number of columns and the page will display all the column if the width of the container allows. Or you can set the number of columns and and posts will be placed over the entire width of the container automatically, their width will change depending on the width of the container.
* In both variants of Responsive Grid on smartphones all posts displays in one column the entire width of the screen.
* Added support for multiple columns in all layouts. Now in the layout can be from 1 to 12 columns, all as in the Bootstrap :)

= 0.6.6 =
* Fixed issue with incorrect interaction with other posts, comments, plug-ins, etc., which displays the content on the same page, which is inserted a shortcode.

= 0.6.5 = 
* Changes in the widgets and shortcodes - now you can exclude the Posts without Featured Image from the Posts list. 
* Changes in the Fluid Images Layout - now if the Post have no Featured Image, the block with background displayed instead of the Featured Image. Background and text color you can choose in the Shortcode Builder. In widget this feature will be available soon.
* Changes in the Thumbnail Layout - now if the Post have no Featured Image, the block with Thumbnail displayed as a la Drop Cap Layout with the first letter of the Post title. Background and text color you can choose in the Shortcode Builder. In widget this feature will be available soon.
* Fixed some bugs in the Shortcode Builder

= 0.6.4 =
* Added filter by Post authors in the shortcode and widgets
* Added new color scheme (both widgets and shortcode) - natural image colors, without any overlay

= 0.6.3 =
* Added Custom Taxonomies Support
* Added Color Picker for the background and text color for the Drop Cap Layout
* Fixed bug with disabled columns in the Shortcode Builder

= 0.6.2 =
* Added Custom Post Types support

= 0.6.1 =
* Improved mobile version of the Responsive Grid Layout

= 0.6 =
* New layout (shortcode) - Responsive Grid

= 0.5 =
* Now you can select one or more categories of displayed posts
* Now you can rearrange the date and title
* Now you can display posts in reverse order
* Fixed some bugs

= 0.4 =
* Added different date and time formats - now date and time format is independent from WP date and time settings
* The custom months localization was removed

= 0.3 =
* Added shortcode builder in WordPress Backend
* Two layouts (basic and overlay) merged into one: layout with adaptive or fixed fixed width and fluid images
* Add one column and two columns support for all layouts

= 0.2 =
* Added one more widget  - widget with small thumbnails
* Added frontend for shortcode, only 4 styles:
  * Basic layout
  * Fluid images with dark/light overlay
  * Small thumbnails
  * Recent posts without thumbnails, with date as drop cap

= 0.1 =
* Initial release - only widget that shows the recent posts in the widget
* Only one style of recent posts in widget