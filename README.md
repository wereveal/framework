# README #

* The Framework upon which I build my apps.
* Version 2

### How do I get set up? ###

* Once cloned, immediately create a fresh app. The framework is intended to give a fresh start, so new git repo, not a fork.
    1. rm -Rf .git
    2. git init
    3. composer.phar install
    4. bower install
*  clone Library from git if not installed via composer
    1. cd app/src/Ritc
    2. git clone ritclibrary Library
    3. clone any other Ritc app needed
* New App configuration
    1. create database and assign user to it. Tables will be made with install.php.
    2. Move to app/config/install and modify/copy install_config.php. If you copy install_config.php be sure to specify it on the command line when you run install.php
    3. php install.php (alternative config file name)

### Contribution guidelines ###

* Writing tests - yes
* Code review - yes
* Try to follow PHP-FIG coding standards (PSR-1 and PSR-2) with following exceptions allowed
    * PSR-1 4.2 avoids recommendations.
        * For my projects I use $under_score property names.
        * I have used $camelCase property names but like the readability of $under_score names.
        * I use $o_ to start vars that are objects. Helps at a glance to know what it is.
        * I use $a_ to start vars that are arrays. Helps at a glance to know what it is.
    * PSR-2
        * 2.3 Lines Quite frankly, they are confusing. They say...
            - MUST NOT be a hard limit on line length
            - Soft Limit MUST be 120 characters
            - yet they also specifiy Lines SHOULD NOT be longer than 80 characters.
            - HUH??? So, what I do
                - No hard limit on line length - I couldn't care.
                - Soft Limit is 120 characters, I usually set my page guide to 120.
                - I aim at 80 characters but really, who uses a terminal that narrow?
        * 2.5 Keywords and True/False/Null
            - Opinion is they are being lazy with true, false, and null.
            - I use lower case now but don't really care unless I want to emphasize a value.
            - They are constants - see PSR-1 4.1
        * 5. Control Structures
            - I prefer to have elseif, else, while, and catch on their own line e.g.

```
#!php

             if ($this) {
                 // do something
             }
             elseif ($that) {
                 // do that something
             }
             else {
                 // do the default thing
             }
```


### Who do I talk to? ###

* William E Reveal <bill@revealitconsulting.com>