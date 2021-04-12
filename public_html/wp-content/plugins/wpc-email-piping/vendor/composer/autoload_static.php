<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita8202d550171551178d6b10aa9d809d3
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EmailReplyParser\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EmailReplyParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/willdurand/email-reply-parser/src/EmailReplyParser',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita8202d550171551178d6b10aa9d809d3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita8202d550171551178d6b10aa9d809d3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
