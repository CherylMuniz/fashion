=== Growmap Anti Spambot Plugin ===
Contributors: commentluv
Donate link:http://comluv.com/about/donate
Tags: comments, anti spam, spam, spambot, gasp
Requires at least: 2.9.2
Tested up to: 3.5
Stable tag: 1.2
	
Defeat automated spambots by adding a client side generated checkbox asking the comment author to confirm that they are not a spammer. 

== Description ==

This plugin will add a client side generated checkbox to your comment form asking users to confirm that they are not a spammer.
It is a lot less trouble to click a box than it is to enter a captcha and because the box is genereated via client side javascript that bots cannot see, it should stop 99% of all automated spam bots.

A check is made that the checkbox has been checked before the comment is submitted so there's no chance that a comment will be lost if it's being submitted by legitimate human user.

You can get support and see this plugin in action at [Growmap](http://www.growmap.com/growmap-anti-spambot-plugin/ "Growmap Internet Strategist")

Translations : 

French : [Frederic](http://www.fredserva.fr "French Translation")

== Installation ==

Wordpress : Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

WordpressMu : Same as above (do not place in mu-plugins)

== Frequently Asked Questions ==

= Does this plugin add any database tables? =

No. 

= Will this plugin work with Disqus/Intense Debate/js-kit? =

This will only work with the default Wordpress comments system.

= If I disable javascript will it still work? =

No. This plugin requires javascript to be enabled in the users browser for the comment to be accepted.

= The checkbox doesn't appear in my comment form =

The checkbox only appears for logged out users.
If you're logged out, it only shows if you have javascript enabled.
If you are logged out and have javascript enabled and it is still not showing then you need to have the comment form action active in your comments.php theme file
` do_action('comment_form',$post->ID); `

= I am allowing trackbacks but I am being trackback spammed! what do I do? =

You can download any of the number of trackback validation plugins which will check the trackback before allowing it or not.

= After having no spam I am now getting LOTS of spam, what do I do? =

Sometimes scripts can semi automate spam and they know what the checkbox name is so they can automatically tick it.
Change the `checkbox name` value in the settings page to something new (like change the number) so the autmoated systems don't know what the checkbox is called any more

== Screenshots ==

1. settings page

2. in use

3. error message

== ChangeLog ==

= 1.2 =
* allow blogger to change checkbox name in settings

= 0.1 =
* First version , commissioned by @phollows via @growmap

= 0.2 =
* tidied up options page and added field for checkbox label

= 0.3 =
* changed the hidden div with text type input to hidden input to prevent google toolbar from filling in the text field

= 0.4 =
* added client side alert if checkbox is not checked. @donnafontenot http://bit.ly/9Uqfxz

= 1.0 =
* release version

= 1.01 =
* use different method to identify submitted form for forms that have a submit button with no id or name set (@dragonblogger)

= 1.02 =
* ignore trackbacks and pingbacks (@basicblogtips)

= 1.03 =
* let blog owner to choose to allow trackbacks or not (@dragonblogger)

= 1.1 =
* add ability to specify maximum number of names or urls in content of comment. (@dragonblogger)
* choose where to send comment


== Upgrade Notice ==

= 1.2 =

* added - allow user to change gasp checkbox name 

== Configuration ==

* You only need to specify which error messages to show when the user forgets to use the checkbox or no checkbox is present.
