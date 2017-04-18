# Instructions for scripts in this directory

- install.php
  - should be used when initializing a new project.
  - It combines the work that the other scripts do into one simple command.
  - It uses several files in the /src/config/install dir to setup the app. Modify the default values in those files as desired.
    - install_config.php has most of the primary variable used in the install. Modify it to match your needs or copy it and modify the copied file. If you use a copy, be sure to specify the file when you run install.php.
    - default_data.php has most of the primary values entered into the database.
    - default_x_create.php files have sql used to create the tables needed.
    - twig_config_values.php has the values needed to create the twig_config.php file used by Twig templates.
- makePasswords script allows one to regenerate the default passwords. Change the actual password before doing it, copy the password generated and enter it into the database record.
- makeDirs creates the default directories for an app with appropriate files
- makeDb recreates the default database tables for the app.
