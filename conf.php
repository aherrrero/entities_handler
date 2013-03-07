<?php

// DEFAULT SETTINGS
$_path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_SERVER['SCRIPT_FILENAME']);
$_path = explode('/', $_path);
array_pop($_path);
$_dirURL = '/'.implode('/', $_path).'/';

//DEFAULT PARAMETERS
$_defaults = array(
	'username'=>'echo123',
	'name'=>'Echo',
	'type'=>'call',
	'size'=>'16',
	'backups'=>false
);


/*
SKYPE API RETURN CODES

code	meaning				rule name

0		unknown				unknown
1		offline				offline
2		online				online
3		away				away
4		not available		na
5		do not disturb		dnd
6		invisible			invisible
7		skype me			skypeme
*/

// STATUS RULES
// array matching rule names to API return codes
$_rules = array(
	'offline',
	'offline',
	'online',
	'away',
	'offline',
	'dnd',
	'offline',
	'online'
);


// STATUS WEIGHTING
// the order statuses should be prioritised when checking backup accounts
$_prio = array(
	'skypeme',
	'online',
	'away',
	'dnd',
	'na',
	'invisible',
	'offline',
	'unknown'
);

?>