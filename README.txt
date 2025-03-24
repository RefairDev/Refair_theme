=== refair ===

Contributors: Thomas Vias, automattic
Tags: custom-background, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready

Requires at least: 4.5
Tested up to: 6.7.2
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: LICENSE

Custom theme for the refair-bm.fr website.

== Description ==

This website integrate graphic designs of REFAIR project. 
There are several templated pages, archive template managing of deposits post type, providers post type, materials ( Woocommerce products ).


== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload Theme and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.

== Frequently Asked Questions ==

= Does this theme support any plugins? =

refair includes support for Infinite Scroll in Jetpack.

== Changelog ==

= 1.0 - 24/03/2025 =
* Initial release

== Credits ==

* Based on Underscores https://underscores.me/, (C) 2012-2017 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* normalize.css https://necolas.github.io/normalize.css/, (C) 2012-2016 Nicolas Gallagher and Jonathan Neal, [MIT](https://opensource.org/licenses/MIT)

== Developement ==

* Prerequisite:
	Node.js: 20.17.0
	npm: 10.8.2

* Initialization
	1. exec: `npm install`
	2. Customize **gulpfile.js** from **gulpfile.sample.js** with correct paths
	3. plugin rebild command line:
		* for local server, exec command: 
			`gulp`
		* for archive, exec command:
	    	`gulp dist`
	4. Theme generated is either in working directory either in dist folder			