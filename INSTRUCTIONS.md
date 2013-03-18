# Usage Instructions #

This file aims to give you instructions on how to install and use the WPSkypeStatus project - either as a normal PHP class or as WordPress plugin.

## Contents ##

* [Standard PHP](#standard-php)
    * [Installation](#installaion)
    * [Usage](#usage)
    * [Debugging](#debugging)
* [WordPress Plugin](#wordpress-plugin)
    * [Installaion](#installation-1)
    * [Usage](#usage-1)

## Standard PHP ##

In order to use WPSkypeStatus as a normal PHP class you don't have to change anything - it'll work straight out of the box. If you want, you can change the values in `conf.php` to make adding Skype links even easier by not having to use the array of parameters each time.

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

1. Install as described above
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

1. Install as described above
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

### Installation ###

### Usage ###