<?php

/**
 * @file
 * Includes the autoloader created by Composer.
 *
 * @see composer.json
 * @see index.php
 * @see core/install.php
 * @see core/rebuild.php
 * @see core/modules/statistics/statistics.php
 */

// Include our Composer libraries.
require __DIR__ . '/../vendor/autoload.php';

// Include Drupal core vendors.
return require __DIR__ . '/vendor/autoload.php';
