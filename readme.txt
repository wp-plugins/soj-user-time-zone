=== Plugin Name ===
Contributors: sojweb
Tags: tags, rss
Requires at least: 2.7.1
Tested up to: 2.7.1
Stable tag: 0.5

Set user specific time zones; this is a work in progress.

== Description ==

My intention was to create a plugin to allow authors to set their own time zones. It shouldn't take too much, but I keep having to put it off. It just needs to take into account daylight savings time, but this is a little tricky because you need to keep a record somehow of past DST times for posts published in the past.

I have a couple ideas for implementing this, but if anyone else has any brilliant ideas, please go for it. Another thing I intend to do is hook into `pre_option_gmt_offset` instead of the `get_the_time` filter, as it does now.

Let me know of any problems: jj56@indiana.edu

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the settings page to set the time zone and DST

== Frequently Asked Questions ==

Nothing here yet.

== Screenshots ==

Nothing here yet.