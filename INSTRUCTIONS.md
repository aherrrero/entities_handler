# Usage Instructions #

This file aims to give you instructions on how to install and use the WPSkypeStatus project - either as a normal PHP class or as WordPress plugin.

## Contents ##

* [Standard PHP](#standard-php)
    * [Installation](#installaion)
    * [Usage](#usage)
    * [Changing Defaults](#changing-defaults)
    * [Debugging](#debugging)
* [WordPress Plugin](#wordpress-plugin)
    * [Installation](#installation-1)
    * [Usage](#usage-1)
    * [Changing Settings](#changing-settings)
    * [Debugging](#debugging-1)

## Standard PHP ##

In order to use WPSkypeStatus as a normal PHP class you don't have to change anything - it'll work straight out of the box. If you want, you can change the values in `conf.php` to make adding Skype links even easier by not having to use the array of parameters each time. If you're rarely going to be re-using the same account for links, though, changing `conf.php` won't make much difference for you.

### Installation ###

1. [Download](https://github.com/Ultrabenosaurus/WPSkypeStatus/zipball/master) and extract the project
2. Copy `skype.php`, `_admin/conf.php` and the `images/` folder to where you keep external libraries
2. Include `skype.php` and instantiate a new `WPSkypeStatus` object

**Example**

```php
include 'lib/skype.php';
$WPSS = new WPSkypeStatus();
```

### Usage ###

1. Install as [described above](#installaion)
2. Echo the non-static `skype()` function to display a link with default settings
3. Use the optional associative array to change the link that's displayed

**Example**

```php
$_args = array(
    'username' => 'echo123',
    'size' => '32'
);
echo $WPSS->skype($_args);
```

Any arguments you omit will take their values from the defaults in `conf.php`.

### Changing Defaults ###

1. Open `_admin/conf.php` in your favourite text editor
2. Alter the values you want to change
3. Save the file

Next time you use WPSkypeStatus (probably when you refresh the page you're working on) anywhere that uses a default value from `conf.php` will use the new values you just set. Simple.

If you want to change something that's not in `conf.php` - though I don't know why you would - you'll have to look inside `skype.php` and change it there. This is discouraged, however, as it complicates things if ever you try to upgrade WPSkypeStatus and means other people working on the project may not know where to go if they need to change it back.

If there's something that currently can't be changed but you think would benefit from being in `conf.php` then [open an issue](https://github.com/Ultrabenosaurus/WPSkypeStatus/issues) - after checking it hasn't already been requested - and I'll have a think about it.

### Debugging ###

1. Install as [described above](#installaion)
2. Call the non-static `set_debug($state, $return, $json)` function to toggle debugging
3. Use `$WPSS->skype()` as you would normally
4. Look at all the pretty debug info you get
5. Turn debug off again when you don't need it with `$WPSS->set_debug(false)`

**Example**

```php
$_settings = $WPSS->set_debug(true, true, false);
echo "<pre>" . print_r($_settings, true) . "</pre>";

$_args = array(
    'username' => 'echo123',
    'size' => '32'
);
echo $WPSS->skype($_args);

$WPSS->set_debug(false);
```

All parameters are boolean and optional:

* `$state` - turns debugging on, `true`, or off, `false`
* `$return` - whether to return, `true`, or echo, `false`, the settings in use
    * only affects the `set_debug()` call, no subsequent debugging
* `$json` - whether debugging should be JSON strings, `true`, or PHP arrays, `false`

If you want to retrieve the current settings, without turning debugging on, just use `$WPSS->get_current_settings()` to return a PHP array of the current settings.

## WordPress Plugin ##

Using WPSkypeStatus as a WordPress plugin requires absolutely no customisation either - the same files, functions and settings work just as they would when used via a normal PHP `include`. However, they also provide you with a shortcode so that you don't have to edit template files, as well as settings pages in the Tools section of `wp-admin` so you don't have to manually edit `conf.php` to change defaults.

### Installation ###

1. [Download](https://github.com/Ultrabenosaurus/WPSkypeStatus/zipball/master) the project
2. Extract to `<wp-root>/wp-content/plugins/WPSkypeStatus/`
3. Go to your Installed Plugins page in `wp-admin` and activate the plugin


When the plugin is loaded for the first time, it will create its settings table in your WordPress database and populate it with the values from `conf.php`. You can change these settings either in `conf.php` before activating the plugin or via the settings pages in `wp-admin` after activation.

### Usage ###

1. Install as [described above](#installation-1)
2. Use the `[skype]` shortcode anywhere WordPress allows shortcodes
3. Use the WordPress `do_shortcode()` function in your template files
4. Instantiate a WPSkypeStatus object and use the public `skype()` function as [described above](#usage)

**Shortcode Example**

```
[skype username="echo123" name="Geoffrey" type="video"]
```

### Changing Settings ###

There are two ways to set the defaults when using as a WordPress plugin:

* Go to Tools > Skype Options in `wp-admin` after activating the plugin
* Set your defaults in `conf.php` before activating the plugin

After initial activation, changing the values in `conf.php` will have no effect on the settings used unless you delete the `skype_settings` table from your WordPress database.

### Debugging ###

The shortcode does not support toggling the debugging state of WPSkypeStatus and I don't plan to implement that at any time - it's too dangerous if your content editors have easy access to this. As such you will have to enable/disable debugging via PHP in your template files as [described above](#debugging)

I have not tested debugging in WordPress as yet so I cannot confirm if it works as expected or not. I imagine if nothing is displayed it will be a result of the action hooks I used and I won't know how to get around that. I don't expect any problems, though, as the debugging will happen when the shortcode is parsed which will be in the page rather than the head.