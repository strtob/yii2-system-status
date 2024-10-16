<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb8b6030c21fd71d5a538b11699b8f7fd
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

        spl_autoload_register(array('ComposerAutoloaderInitb8b6030c21fd71d5a538b11699b8f7fd', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb8b6030c21fd71d5a538b11699b8f7fd', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb8b6030c21fd71d5a538b11699b8f7fd::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
