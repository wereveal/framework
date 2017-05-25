# Instructions for scripts in this directory

- install.php
  - should be used when initializing a new project.
  - It combines the work that the other scripts do into one simple command.
  - It uses several files in the /src/config/install_files dir to setup the app.
    - install_config.php.txt should be copied to /src/config/install_config.php or whatever name you choose but it must be in the src/config directory.
    - Modify new file to match your needs. If you use a name other than install_config.php, be sure to specify the file when you run install.php.
    - default_data.php has most of the primary values entered into the database and normally won't need changed.
    - default_x_create.php files have sql used to create the tables needed and will be automatically selected based on value given in install_config.php.
    - twig_config_values.php has the values needed to create the twig_config.php file used by Twig templates. Normally won't need to be changed.
- makePasswords script allows one to regenerate the default passwords. Change the actual password before doing it, copy the password generated and enter it into the database record.
- makeDirs creates the default directories for an app with appropriate files.
- makeDb recreates the default database tables for the app. Normally just re-running install.php is sufficient.
