<?php

/**
 * @file
 * Contains \Drupal\monolog\MonologProfileInterface.
 */

namespace Drupal\monolog;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\monolog\MonologHandlerInterface;

/**
 * Provides an interface defining a monolog profile config entity.
 */
interface MonologProfileInterface extends ConfigEntityInterface, EntityWithPluginCollectionInterface {

  /**
   * Returns a handler instance.
   *
   * @param string $handler
   *   The handler plugin id.
   *
   * @return \Drupal\monolog\MonologHandlerInterface
   *
   * @see \Drupal\Component\Plugin\PluginBag::get()
   */
  public function getHandler($handler);

  /**
   * Returns all handlers of this profile.
   *
   * @return \Drupal\Component\Plugin\PluginBag
   */
  public function getHandlers();

  /**
   * Adds a handlers to this profile.
   *
   * @param array $configuration
   *   The handler's configuration.
   */
  public function addHandler(array $configuration);

  /**
   * Deletes a handler from this profile.
   *
   * @param \Drupal\monolog\MonologHandlerInterface $hander
   *   The handler to delete.
   *
   * @return $this
   */
  public function deleteHandler(MonologHandlerInterface $handler);

  /**
   * Get the handler's label.
   *
   * @return $this
   */
  public function getLabel();

  /**
   * Set this profile's label.
   *
   * @return self
   */
  public function setLabel($label);

}
