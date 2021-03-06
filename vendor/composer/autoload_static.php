<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit703dcd23232b560de5aafcd7def8522e
{
    public static $prefixesPsr0 = array (
        'o' => 
        array (
            'org\\bovigo\\vfs' => 
            array (
                0 => __DIR__ . '/..' . '/mikey179/vfsStream/src/main/php',
            ),
        ),
        'P' => 
        array (
            'Pug\\' => 
            array (
                0 => __DIR__ . '/..' . '/kylekatarnls/jade-php/src',
            ),
        ),
        'J' => 
        array (
            'Jade\\' => 
            array (
                0 => __DIR__ . '/..' . '/kylekatarnls/jade-php/src',
            ),
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/..' . '/ci-jade/ci-jade/src',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit703dcd23232b560de5aafcd7def8522e::$prefixesPsr0;
            $loader->fallbackDirsPsr0 = ComposerStaticInit703dcd23232b560de5aafcd7def8522e::$fallbackDirsPsr0;

        }, null, ClassLoader::class);
    }
}
