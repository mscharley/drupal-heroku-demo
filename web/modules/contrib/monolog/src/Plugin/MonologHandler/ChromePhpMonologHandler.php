<?php

/**
 * @file
 * Handler include for ChromePHPHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\monolog\MonologHandlerInterface;
use Drupal\monolog\MonologHandlerBase;
use Monolog\Handler\ChromePHPHandler;

/**
 * Handler for ChromePHP, providing inline console messages within Chrome.
 *
 * @MonologHandler(
 *   id = "chromephp",
 *   label = @Translation("ChromePHP Handler"),
 *   description = @Translation("Handler for ChromePHP, providing inline console messages within Chrome."),
 *   group = @Translation("Development"),
 * )
 */
class ChromePhpMonologHandler extends MonologHandlerBase implements MonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new ChromePHPHandler($this->configuration['level'], $this->configuration['bubble']);
  }

}