<?php declare(strict_types=1);

$dependencies = [
    __SOURCE_DIR__ . "/Oraculum/Support/Primitives/PrimitiveObject.php",

    __SOURCE_DIR__ . "/Oraculum/Support/Contracts/Stringable.php",
    __SOURCE_DIR__ . "/Oraculum/Support/Contracts/FromArray.php",
    __SOURCE_DIR__ . "/Oraculum/Support/Contracts/Arrayable.php",

    __SOURCE_DIR__ . "/Oraculum/Support/Attributes/Override.php",

    __SOURCE_DIR__ . "/Oraculum/Support/Traits/GloballyAvailable.php",
    __SOURCE_DIR__ . "/Oraculum/Support/Traits/NonInstantiable.php",

    __SOURCE_DIR__ . "/Oraculum/Support/Path.php",

    __SOURCE_DIR__ . "/Oraculum/Json/Json.php",

    __SOURCE_DIR__ . "/Oraculum/FileSystem/Abstracts/File.php",
    __SOURCE_DIR__ . "/Oraculum/FileSystem/File.php",
    __SOURCE_DIR__ . "/Oraculum/FileSystem/LocalFile.php",
    __SOURCE_DIR__ . "/Oraculum/FileSystem/ReadonlyFile.php",
    __SOURCE_DIR__ . "/Oraculum/FileSystem/CacheFile.php",

    __SOURCE_DIR__ . "/Miscellaneous/Autoloader/Autoloader.php"
];

// Automatically require all dependencies.
// This helps us to avoid foreign dependencies in our code.
foreach ($dependencies as $dependency) {
    require_once $dependency;
}

// Creates a new instance of the autoloader based in a autoload.json file located
// in the root directory of the project.
$autoloder = new \Miscellaneous\Autoloader\Autoloader(
    \Oraculum\FileSystem\LocalFile::new('autoload.json')->getFilename()
);

// Mark all dependencies to be ignored by the autoloader.
foreach ($dependencies as $dependency) {
    $autoloder->ignore($dependency);
}

// Start autoloading dependencies.
$autoloder->autoload();

// Changes the global autoloader instance to the new one.
\Miscellaneous\Autoloader\Autoloader::setInstance($autoloder);