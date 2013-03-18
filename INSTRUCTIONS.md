# Usage Instructions #

This file aims to give you instructions on how to install and use the WPSkypeStatus project - either as a normal PHP class or as WordPress plugin.

## Standard PHP ##

In order to use WPSkypeStatus as a normal PHP class you don't have to change anything - it'll work straight out of the box. If you want, you can change the values in `conf.php` to make adding Skype links even easier, but that isn't necessary at all.

### Installation ###

1. [Download the project](https://github.com/Ultrabenosaurus/WPSkypeStatus/zipball/master)
2. Include `skype.php` and instantiate a new `WPSkypeStatus` object

**Example**

```php
include 'skype.php';
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