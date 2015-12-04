<?php

/**
 * @file
 * Contains \Drupal\monolog\ConfigurableMonologHandlerInterface.
 */

namespace Drupal\monolog;

use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines the interface for configurable monolog handlers.
 *
 * @see \Drupal\monolog\Annotation\MonologHandler
 * @see \Drupal\monolog\MonologHandlerInterface
 * @see \Drupal\monolog\MonologHandlerManager
 * @see plugin_api
 */
interface ConfigurableMonologHandlerInterface extends MonologHandlerInterface, PluginFormInterface {
}
