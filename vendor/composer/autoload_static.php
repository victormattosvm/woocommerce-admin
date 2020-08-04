<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit872d8240bc8dd808a545b31db5a3961c
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
        'A' => 
        array (
            'Automattic\\WooCommerce\\Admin\\' => 29,
            'Automattic\\Jetpack\\Autoloader\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Automattic\\WooCommerce\\Admin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Automattic\\Jetpack\\Autoloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit872d8240bc8dd808a545b31db5a3961c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit872d8240bc8dd808a545b31db5a3961c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
