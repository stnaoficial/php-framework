<?php declare(strict_types=1);

$dependencies = [
    __DIR__ . '/src/Oraculum/Support/Primitives/PrimitiveObject.php',

    __DIR__ . '/src/Oraculum/Contracts/Media.php',
    __DIR__ . '/src/Oraculum/Contracts/Stringable.php',
    __DIR__ . '/src/Oraculum/Contracts/FromArray.php',
    __DIR__ . '/src/Oraculum/Contracts/Arrayable.php',

    __DIR__ . '/src/Oraculum/Support/Attributes/Override.php',
    __DIR__ . '/src/Oraculum/Support/Traits/GloballyAvailable.php',
    __DIR__ . '/src/Oraculum/Support/Traits/NonInstantiable.php',
    __DIR__ . '/src/Oraculum/Support/Path.php',

    __DIR__ . '/src/Oraculum/Json/Json.php',

    __DIR__ . '/src/Oraculum/FileSystem/Abstracts/File.php',
    __DIR__ . '/src/Oraculum/FileSystem/File.php',
    __DIR__ . '/src/Oraculum/FileSystem/ReadonlyFile.php',
    __DIR__ . '/src/Oraculum/FileSystem/CacheFile.php',

    __DIR__ . '/src/Oraculum/Autoloader/Autoloader.php'
];

// Automatically require all dependencies.
// This helps us to avoid foreign dependencies in our code.
foreach ($dependencies as $dependency) {
    require_once $dependency;
}

$autoloder = new \Oraculum\Autoloader\Autoloader(
    \Oraculum\Support\Path::basePath('autoload.json')
);

// Mark all dependencies to be ignored by the autoloader.
foreach ($dependencies as $dependency) {
    $autoloder->ignore($dependency);
}

$autoloder->autoload();