<?php
/**
 *  Autoloader for framework.
 *  Based on the auto_load_real.php file generated by Composer
 *  @file Autoloader.php
 *  @class Autoloader
 *  @requires Composer\Autoload\ClassLoader
 *      which is installed on level out from the web site root
 *      in the vendor directory.
 *  @author William Reveal  <wer@revealitconsulting.com>
 *  @version 1.0.0
 *  @par Change Log
 *      v1.0.0 - officially added to Framework
 *  @par Wer Framework version 4.0
 *  @date 2013-06-26 10:00:13
 *  @ingroup wer_framework core library
**/
namespace Wer\Framework\Library;

class Autoloader
{
    private static $loader;
    private $config_path   = $_SERVER['DOCUMENT_ROOT'] . '/../Wer/config';
    private $composer_path = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/composer';
    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require $this->composer_path . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('Autoloader', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('Autoloader', 'loadClassLoader'));

        if (file_exists($this->config_path . '/autoload_namespaces.php')) {
            $namespaces = require $this->config_path . '/autoload_namespaces.php';
            foreach ($namespaces as $namespace => $path) {
                $loader->add($namespace, $path);
            }
        }

        if (file_exists($this->config_path . '/autoload_classmap.php')) {
            $classMap = require $this->config_path . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        if (file_exists($this->composer_path . '/autoload_namespaces.php')) {
            $namespaces = require $this->composer_path . '/autoload_namespaces.php';
            foreach ($namespaces as $namespace => $path) {
                $loader->add($namespace, $path);
            }
        }

        if (file_exists($this->composer_path . '/autoload_classmap.php')) {
            $classMap = require $this->composer_path . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);
        return $loader;
    }
}
