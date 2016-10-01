<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite72621cdbb7ab1eae4754470aabe0609
{
    public static $files = array (
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'splitbrain\\PHPArchive\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'splitbrain\\PHPArchive\\' => 
        array (
            0 => __DIR__ . '/..' . '/splitbrain/php-archive/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PEAR' => 
            array (
                0 => __DIR__ . '/..' . '/pear/pear_exception',
            ),
        ),
        'N' => 
        array (
            'Net_DNS2' => 
            array (
                0 => __DIR__ . '/..' . '/pear/net_dns2',
            ),
        ),
        'H' => 
        array (
            'HTTP_Request2' => 
            array (
                0 => __DIR__ . '/..' . '/pear/http_request2',
            ),
        ),
        'C' => 
        array (
            'Console' => 
            array (
                0 => __DIR__ . '/..' . '/pear/console_getopt',
            ),
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/..' . '/pear/pear-core-minimal/src',
    );

    public static $classMap = array (
        'Net_URL2' => __DIR__ . '/..' . '/pear/net_url2/Net/URL2.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite72621cdbb7ab1eae4754470aabe0609::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite72621cdbb7ab1eae4754470aabe0609::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite72621cdbb7ab1eae4754470aabe0609::$prefixesPsr0;
            $loader->fallbackDirsPsr0 = ComposerStaticInite72621cdbb7ab1eae4754470aabe0609::$fallbackDirsPsr0;
            $loader->classMap = ComposerStaticInite72621cdbb7ab1eae4754470aabe0609::$classMap;

        }, null, ClassLoader::class);
    }
}
