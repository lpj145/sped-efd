<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd6473f60a04491e9f9803c24aa57fbcf
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Marcos\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Marcos\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd6473f60a04491e9f9803c24aa57fbcf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd6473f60a04491e9f9803c24aa57fbcf::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
