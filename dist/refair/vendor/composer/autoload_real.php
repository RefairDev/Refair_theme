<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb20d382b38c54c255e55712cf2bfba78
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitb20d382b38c54c255e55712cf2bfba78', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb20d382b38c54c255e55712cf2bfba78', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb20d382b38c54c255e55712cf2bfba78::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
