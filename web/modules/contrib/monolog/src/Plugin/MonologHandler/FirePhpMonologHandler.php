<?php

/**
 * @file
 * Handler include for FirePHPHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\monolog\MonologHandlerInterface;
use Drupal\monolog\MonologHandlerBase;
use Monolog\Handler\FirePHPHandler;

/**
 * Handler for FirePHP, providing inline console messages within FireBug.
 *
 * @MonologHandler(
 *   id = "firephp",
 *   label = @Translation("FirePHP Handler"),
 *   description = @Translation("Handler for FirePHP, providing inline console messages within FireBug."),
 *   group = @Translation("Development"),
 * )
 */
class FirePhpMonologHandler extends MonologHandlerBase implements MonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new FirePHPHandler($this->configuration['level'], $this->configuration['bubble']);
  }

}