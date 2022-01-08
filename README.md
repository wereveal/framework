# README #

* The Framework upon which I build my apps.
* Version 5

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
          3. npm install
          4. sass update scss:css (After modifying the scss files as needed, recompile.)
          5. cd ../../
          6. git clone https://www.revealitconsulting.com/git/ritc/library src/apps/Ritc/Library
          7. copy src/config/install_files/install_config.php.txt to src/config/install_config.php
          8. Change settings in src/config/install_config.php to match new app(s)
          9. php src/bin/install.php
    5. Optionally install jquery-ui
       1. In the /public/assets dir  bash ./doJqueryUi

	   or

       1. cd public/assets/vendor/jquery-ui/
       2. npm install
       3. npm test
       4. if a different theme desired for jquery-ui, download it from jqueryui (http://jqueryui.com/themeroller/).

### Contribution guidelines ###

* Writing tests - yes
* Code review - yes
* Coding Standards
  * **PHP** - Try to follow PHP-FIG coding standards ([PSR-1][fig1] and [PSR-12][fig12]) with following exceptions allowed
      * PSR-1 4.2 avoids recommendations.
          * SHOULD use $under_score property names.
          * MAY use $o_ to start vars that are objects. Helps at a glance to know what it is.
          * MAY use $a_ to start vars that are arrays. Helps at a glance to know what it is.
      * PSR-12
          * 2.3 Lines Quite frankly, they are confusing. They say...
              - MUST NOT be a hard limit on line length
              - Soft Limit MUST be 120 characters
              - yet they also specifiy Lines SHOULD NOT be longer than 80 characters.
              - HUH??? So, what I do
                  - No hard limit on line length - exactly, MUST NOT be a hard limit.
                  - Soft Limit is 120 characters, I usually set my page guide to 120 but do what you want so MAY be 120 characters.
                  - I aim at 80 characters but really, who uses a terminal that narrow? So MAY be longer than 80.
          * 2.5 Keywords and True/False/Null
              - Opinion is they are contridicting their own rule in PSR-1, 4.1 with true, false, and null.
              - I use lower case now but don't really care unless I want to emphasize a value so true/false/null SHOULD be lower case unless you need to emphasize it is a CONSTANT.
              - They _are_ constants - see PSR-1 4.1
          * 5 Control Structures
              - I prefer to have elseif, else, while, and catch on their own line because it is easier to spot e.g.
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
  * **Javascript** MAY follow [Airbnb JavaScript Style Guide][airbnb] (but old js code for now may not)
  * **CSS/SASS** MAY follow [Airbnb CSS/Sass Style Guide][airbnbsass] (old sass may not for now)

### Who do I talk to? ###

* William E Reveal <bill@revealitconsulting.com>

[fig1]: https://www.php-fig.org/psr/psr-1/
[fig12]: https://www.php-fig.org/psr/psr-12/
[airbnb]: https://github.com/airbnb/javascript/
[airbnbsass]: https://github.com/airbnb/css