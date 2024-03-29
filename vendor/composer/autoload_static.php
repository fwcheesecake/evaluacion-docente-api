<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb509f93147e5a9c6caa0c300ffe7fec1
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb509f93147e5a9c6caa0c300ffe7fec1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb509f93147e5a9c6caa0c300ffe7fec1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb509f93147e5a9c6caa0c300ffe7fec1::$classMap;

        }, null, ClassLoader::class);
    }
}
