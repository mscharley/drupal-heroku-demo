<?php

/**
 * @file
 * Contains \Drupal\monolog\MonologHandlerManager.
 */

namespace Drupal\monolog;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages monolog handler plugins.
 *
 * @see hook_monolog_handler_info_alter()
 * @see \Drupal\monolog\Annotation\MonologHandler
 * @see \Drupal\monolog\MonologHandlerInterface
 * @see plugin_api
 */
class MonologHandlerManager extends DefaultPluginManager {

  /**
   * Constructs a new MonologHandlerManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/MonologHandler', $namespaces, $module_handler, 'Drupal\monolog\MonologHandlerInterface', 'Drupal\monolog\Annotation\MonologHandler');

    $this->alterInfo('monolog_handler_info');
    $this->setCacheBackend($cache_backend, 'monolog_handler_plugins');
  }

}
