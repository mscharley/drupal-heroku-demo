<?php

/**
 * @file
 * Contains \Drupal\monolog\Entity\MonologProfile.
 */

namespace Drupal\monolog\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Plugin\DefaultLazyPluginCollection;
use Drupal\monolog\MonologHandlerInterface;
use Drupal\monolog\MonologProfileInterface;

/**
 * Defines a monolog profile configuration entity.
 *
 * @ConfigEntityType(
 *   id = "monolog_profile",
 *   label = @Translation("Monolog profile"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\monolog\Form\MonologProfileAddForm",
 *       "edit" = "Drupal\monolog\Form\MonologProfileEditForm",
 *       "delete" = "Drupal\monolog\Form\MonologProfileDeleteForm",
 *     },
 *     "list_builder" = "Drupal\monolog\MonologProfileListBuilder",
 *   },
 *   config_prefix = "profile",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "name",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/development/monolog/profile/{monolog_profile}",
 *     "delete-form" = "/admin/config/development/monolog/profile/{monolog_profile}/delete"
 *   }
 * )
 */
class MonologProfile extends ConfigEntityBase implements MonologProfileInterface {

  /**
   * The name of the profile.
   *
   * @var string
   */
  protected $name;

  /**
   * The profile label.
   *
   * @var string
   */
  protected $label;

  /**
   * Whether this profile is disabled or not.
   *
   * @var bool
   */
  protected $disabled;

  /**
   * The array of handlers for this profile.
   *
   * @var array
   */
  protected $handlers = array();

  /**
   * Holds the collection of handlers that are used by this profile.
   *
   * @var \Drupal\Core\Plugin\DefaultLazyPluginCollection
   */
  protected $handlersBag;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return array('handlers' => $this->getHandlers());
  }

  /**
   * {@inheritdoc}
   */
  public function getHandler($handler) {
    return $this->getHandlers()->get($handler);
  }

  /**
   * {@inheritdoc}
   */
  public function getHandlers() {
    if (!$this->handlersBag) {
      $this->handlersBag = new DefaultLazyPluginCollection($this->getMonologHandlerPluginManager(), $this->handlers);
      $this->handlersBag->sort();
    }
    return $this->handlersBag;
  }

  /**
   * {@inheritdoc}
   */
  public function addHandler(array $configuration) {
    $configuration['uuid'] = $this->uuidGenerator()->generate();
    $this->getHandlers()->addInstanceId($configuration['uuid'], $configuration);
    return $configuration['uuid'];
  }

  /**
   * {@inheritdoc}
   */
  public function deleteHandler(MonologHandlerInterface $handler) {
    $this->getHandlers()->removeInstanceId($handler->getUuid());
    $this->save();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->get('label');
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($label) {
    $this->set('label', $label);
    return $this;
  }

  /**
   * Returns the image effect plugin manager.
   *
   * @return \Drupal\Component\Plugin\PluginManagerInterface
   *   The image effect plugin manager.
   */
  protected function getMonologHandlerPluginManager() {
    return \Drupal::service('plugin.manager.monolog.handler');
  }

}
