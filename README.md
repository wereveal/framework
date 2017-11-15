# README #

* The Framework upon which I build my apps.
* Version 4

### How do I get set up? ###

* Once cloned, immediately create a fresh app. The framework is intended to give a fresh start, so new git repo, not a fork.
    1. rm -Rf .git
    2. git init
    3. git commit -a -m "Initial commit"
    4. Automagical or Manual install
       1. Automagical
          1. cp src/config/install_files/install_config.php.txt to src/config/install_config.php
          1. Change settings in src/config/install_config.php to match new app(s)
          1. bash ./install.sh
       2. manual
          1. composer(.phar) install
          2. cd public/assets
          3. yarn install --modules-folder vendor
          4. sass update scss:css (After modifying the scss files as needed, recompile.)
          5. cd ../../
          6. git clone https://www.revealitconsulting.com/git/ritc/library src/apps/Ritc/Library
          7. copy src/config/install_files/install_config.php.txt to src/config/install_config.php
          8. Change settings in src/config/install_config.php to match new app(s)
          9. php src/bin/install.php
    5. Optionally install jquery-ui
	   1. bash ./doJqueryUi
		  or
       1. cd public/assets/vendor/jquery-ui/
       2. npm install grunt --save-dev
       3. npm install
       4. grunt
       5. if a different theme desired for jquery-ui, download it from jqueryui (http://jqueryui.com/themeroller/).

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
