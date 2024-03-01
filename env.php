<?php declare(strict_types=1);

/** 
 * The root directory constant.
 */
if (!defined("__ROOT_DIR__") && php_sapi_name() !== "cli") {
    if (!$rootDirectory = $_SERVER["DOCUMENT_ROOT"]) {
        exit("Unable to determine the root directory.");
    }

    define("__ROOT_DIR__", $rootDirectory);
}

/**
 * The working directory constant.
 */
if (!defined("__WORKING_DIR__")) {
    if (!$workingDirectory = getcwd()) {
        exit("Unable to determine the working directory.");
    }

    define("__WORKING_DIR__", $workingDirectory);
}

/**
 * The framework directory constant.
 */
if (!defined("__FRAMEWORK_DIR__")) {
    define("__FRAMEWORK_DIR__", __DIR__);
}

/**
 * The source directory constant.
 */
if (!defined("__SOURCE_DIR__")) {
    define("__SOURCE_DIR__", __FRAMEWORK_DIR__ . "/src");
}

/**
 * The storage directory constant.
 */
if (!defined("__STORAGE_DIR__")) {
    define("__STORAGE_DIR__", __FRAMEWORK_DIR__ . "/storage");
}

/**
 * The PHP default file extension constant.
 */
if (!defined("PHP_FILE_EXTENSION")) {
    define("PHP_FILE_EXTENSION", ".php");
}

/** 
 * The default directory separator constant.
 */
if (!defined("DIRECTORY_SEPARATOR")) {
    define("DIRECTORY_SEPARATOR", '/');
}

/**
 * The default namespace separator constant.
 */
if (!defined("NAMESPACE_SEPARATOR")) {
    define("NAMESPACE_SEPARATOR", '\\');
}