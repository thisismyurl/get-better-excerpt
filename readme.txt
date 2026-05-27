=== Get Better Excerpt ===
Contributors: christopherross
Plugin URI: http://thisismyurl.com/downloads/get-better-excerpt/
Tags: wordpress,theme,excerpt,words, plugin, post, posts, adopt-me
Donate link:  http://thisismyurl.com/
Requires at least: 3.2.0
Tested up to: 7.0
Stable tag: 2.6147

The Get Better Excerpt plugin works almost identical to the built in get_the_excerpt() and the_excerpt() functions except it returns whole words instead of cutting off the excerpt as the existing function does.

== Description ==


** this plugin is no longer being update. Please feel free to adopt me! **


The Get Better Excerpt plugin works almost identical to the built in get_the_excerpt() and the_excerpt() functions except it returns whole words instead of cutting off the excerpt as the existing function does.

This plugin is maintained by Christopher Ross, http://thisismyurl.com or you can find him on Twitter at http://twitter.com/thisismyurl/

== Installation ==

To install the plugin, please upload the folder to your plugins folder and active the plugin.

== Screenshots ==

== Updates ==
Updates to the plugin will be posted here, to [thisismyurl.com](http://thisismyurl.com/downloads/get-better-excerpt/)

== Frequently Asked Questions ==

= How do I display the results? =

Insert the following code into your WordPress theme files: 

= General results =
Without passing any parameters, the plugin will return ten results or fewer depending on how many posts you have.

 thisismyurl_get_better_excerpt();


= Altering the before and after values =
By default the plugin wraps your code in list item (&lt;li&gt;) tags but you can specify how to format the results using the following code:

 thisismyurl_get_better_excerpt('before=&lt;p&gt;&amp;after=&lt;/p&gt;');

= Adding a Link = 
If you'd like to link to the post (remember it's not live yet) you can do so by calling:

 thisismyurl_get_better_excerpt('link=true'); 


= How many words? = 
You can specify the number of words returned using the option:

 thisismyurl_get_better_excerpt('words=20'); 
 
 = Skip the excerpt? = 
If you would like to load the content directly, skipping the entered excerpt:

 thisismyurl_get_better_excerpt('skipexcerpt=true'); 

 = Include a trailing character? = 
By default the plugin includes a ... after the excerpt, you can remove it or change it by altering:

 thisismyurl_get_better_excerpt('trail= ...'); 

 = Return a whole sentence = 
If you would like to return whole sentences rather than words, you can control the number of sentences to return:

 thisismyurl_get_better_excerpt('sentence=1'); 



= Echo vs. Return =
Finally, if you'd like to copy the results into a variable you can return the results as follows:

 thisismyurl_get_better_excerpt('show=false'); 


== Donations ==
If you would like to donate to help support future development of this tool, please visit [thisismyurl.com](http://thisismyurl.com/downloads/get-better-excerpt/)


== Change Log ==

= 2.0.0 =

* uses wp_trim_words()

= 1.5.0 =

* Tested for WordPress 3.2
* Optimized code

=  1.0.2 =

* removed update functions

1.0.0 -
Official Release
Added Sentence options