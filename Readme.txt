To install:

1. Put the phpthumbs.php file into your plugins directory and activate it
2. Copy the CSS from phpthumbs.css into your css stylesheet (generally style.css)
3. Use as follows:

<div class="phpthumbs"><img class="phpthumbs" src="..." alt="alt text" /></div>

FAQ

Q: I changed .phpthumbs or .phpthumbs img, and the style has no effect!
A: .phpthumbs and .phpthumbs img are fallbacks. they only work when the plugin is deactivated or isn't installed. use .phpthumbs_active and .phpthumbs_active img to make style changes for when the plugin is active.

Q: Whenever I try to blog about this amazing plugin, it replaces my references to "phpthumb" with "phpthumb_active"! Make it stop!
A: aside from hidden bugs that I haven't found, that's probably the biggest problem with this plugin. It should be case-sensitive, however, so just say "phpThumb" instead of "phpthumb". Although you will have to let people know that to use the class, it must be all lower-case, as in <img class="phpthumb" ... >

Q: KITTENS?!!!
A: Yes.