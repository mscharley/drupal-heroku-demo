<?php

/**
 * @file
 * Contains \Drupal\monolog\Annotation\MonologHandler.
 */

namespace Drupal\monolog\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a monolog handler annotation object.
 *
 * Plugin Namespace: Plugin\MonologHandler
 *
 * For a working example, see
 * \Drupal\image\Plugin\MonologHandler\ResizeImageEffect
 *
 * @see hook_monolog_handler_info_alter()
 * @see \Drupal\image\MonologHandlerInterface
 * @see \Drupal\image\MonologHandlerManager
 * @see plugin_api
 *
 * @Annotation
 */
class MonologHandler extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the handler.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;

  /**
   * A brief description of the handler.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation (optional)
   */
  public $description = '';

}
