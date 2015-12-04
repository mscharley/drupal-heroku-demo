<?php

/**
 * @file
 * Contains \Drupal\monolog\MonologHandlerInterface.
 */

namespace Drupal\monolog;

use Drupal\Component\Plugin\ConfigurablePluginInterface;

/**
 * Defines a monolog handler interface.
 *
 * @see \Drupal\monolog\Annotation\MonologHandler
 * @see \Drupal\monolog\MonologHandlerManager
 * @see plugin_api
 * @see https://github.com/Seldaek/monolog#handlers
 */
interface MonologHandlerInterface extends ConfigurablePluginInterface {

  /**
   * Returns the handler's label.
   *
   * @return string
   *   The handler's label.
   */
  public function label();

  /**
   * Returns the unique ID representing the handler.
   *
   * @return string
   *   The monolog handler UUID.
   */
  public function getUuid();

  /**
   * Returns the weight of the handler.
   *
   * @return int
   *   The weight of the handler.
   */
  public function getWeight();

  /**
   * Sets the weight for this handler.
   *
   * @param int $weight
   *   The weight for this handler.
   *
   * @return $this
   */
  public function setWeight($weight);

  /**
   * Returns the log level of this handler.
   *
   * @return int
   *   The log level of the handler.
   */
  public function getLevel();

  /**
   * Sets the log level for this handler.
   *
   * @param int $level
   *   The weight for handler.
   *
   * @return $this
   */
  public function setLevel($level);

  /**
   * Whether this handler allows bubbling up, or stops propagation.
   *
   * @return bool
   */
  public function allowsBubblingUp();

  /**
   * Sets the allowbubbling up flag for this handler.
   *
   * @param bool $bubble
   *   Whether this handler should allow bubbling up.
   *
   * @return $this
   */
  public function setAllowsBubblingUp($bubble);

  /**
   * Loads the handler class and returns an instance of it.
   *
   * @todo Move this to container.
   *
   * @return \Monolog\Handler\HandlerInterface
   */
  public function getHandlerInstance();

}
