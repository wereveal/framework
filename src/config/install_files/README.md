# Notes regarding the install files

- These are used when the /site/bin/install.php script is run.
- See the README.md file in the bin directory for more instructions.
- Modify these as needed **before** running the install.php script.
  - install_config.php has most of the primary variable used in the install.
  - default_data.php has most of the primary values entered into the database.
  - default_x_create.php files have sql used to create the tables needed.
  - twig_config_values.php has the values needed to create the twig_config.php file used by Twig templates.
- There may be older/unused versions of this in the RITC\Library but they should not be used.
