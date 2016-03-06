# README #

* The Framework upon which I build my apps.
* Version 3
* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)

### How do I get set up? ###

* Once cloned, immediately create a fresh app
    * rm -Rf .git
    * git init 
    * composer.phar install
* Bootstrap install either
    * cp vendor/twbs/bootstrap/dist files to public/assets
    * add CDN links to template to include bootstrap
* Database configuration
    * create database and assign user to it
    * cd app/config
    * php install.php -a AppName -n Namespace -h db_host -t db_type -d db_name -u db_user -p db_pass -f db_prefix

### Contribution guidelines ###

* Writing tests - yes
* Code review - yes
* Try to follow PSR coding standards with following exceptions allowed
    * conditionals can have each statement start on own line. e.g.
        if () {
        }
        else {
        }
    * other allowed exceptions

### Who do I talk to? ###

* William E Reveal <bill@revealitconsulting.com>
