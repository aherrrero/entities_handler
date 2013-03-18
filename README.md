# WPSkypeStatus #

Initially to be a WordPress plugin, now being written to work as either a WP plugin or in any PHP project via a single `include` statement.

The images I use are square resolutions, named according to status and size as `(online|offline|away|dnd).(16|32|64).png` with 16px being the default size. [Originals can be found here](http://vector.us/browse/253348/skype_status_icons).

## About ##

You can manually layout and write your own Skype Call links, but doing that every time can be a hassle. The new Skype URI builder creates the link for you, including pulling the images from the Skype servers for you, but doesn't allow for any sort of differentiation between your Skype statuses - your users won't know if you're online, offline, away, etc.

Thus, I decided to make a simple PHP Class that will create structured Skype call links complete with icons and classes for styling - works as both a normal PHP `include` or as a WordPress plugin!

## To Do ##

In the order I'm going to work on them, the things I want to achieve before labelling this project as having reached version 1.0 - 

1. PHP usage instructions
2. WP usage instructions
3. GitHub Pages site

Things for after version 1.0 - 

1. image filename pattern customisation
2. import/export to conf.php in WP admin

## License ##

As usual with my work, this project is available under the BSD 3-Clause license. In short, you can do whatever you want with this code as long as:

* I am always recognised as the original author.
* I am not used to advertise any derivative works without prior permission.
* You include a copy of said license and attribution with any and all redistributions of this code, including derivative works.

For more details, read the included [LICENSE.md](https://github.com/Ultrabenosaurus/WPSkypeStatus/blob/master/LICENSE.md) file or read about it on [opensource.org](http://opensource.org/licenses/BSD-3-Clause).