<?php

/**
 * @file
 * Handler include for NullHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\monolog\MonologHandlerInterface;
use Drupal\monolog\MonologHandlerBase;
use Monolog\Handler\NullHandler;

/**
 * Throws records away.
 *
 * @MonologHandler(
 *   id = "null",
 *   label = @Translation("Null Handler"),
 *   description = @Translation("Any record it can handle will be thrown away. This can be used to put on top of an existing handler stack to disable it temporarily."),
 *   group = @Translation("Wrappers / special handlers"),
 * )
 */
class NullMonologHandler extends MonologHandlerBase implements MonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new NullHandler($this->configuration['level']);
  }

}
