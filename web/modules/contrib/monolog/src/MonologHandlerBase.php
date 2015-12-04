<?php

/**
 * @file
 * Contains \Drupal\monolog\MonologHandlerBase.
 */

namespace Drupal\monolog;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\monolog\Logger\MonologLogLevel;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for monolog handlers.
 *
 * @see \Drupal\monolog\Annotation\MonologHandler
 * @see \Drupal\monolog\MonologHandlerInterface
 * @see \Drupal\monolog\ConfigurableMonologHandlerInterface
 * @see \Drupal\monolog\MonologHandlerManager
 * @see plugin_api
 */
abstract class MonologHandlerBase extends PluginBase implements MonologHandlerInterface, ContainerFactoryPluginInterface {

  /**
   * The hanlder UUID.
   *
   * @var string
   */
  protected $uuid;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->configuration['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getUuid() {
    return $this->uuid;
  }

  /**
   * {@inheritdoc}
   */
  public function setWeight($weight) {
    $this->configuration['weight'] = $weight;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->configuration['weight'];
  }

  /**
   * {@inheritdoc}
   */
  public function getLevel() {
    return $this->configuration['level'];
  }

  /**
   * {@inheritdoc}
   */
  public function setLevel($level) {
    $this->configuration['level'] = $level;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setAllowsBubblingUp($bubble){
    $this->configuration['bubble'] = $bubble;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsBubblingUp() {
    return $this->configuration['bubble'];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return [
      'uuid' => $this->getUuid(),
      'id' => $this->getPluginId(),
      'data' => $this->configuration,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $configuration += [
      'data' => [],
      'uuid' => '',
    ];
    $this->configuration = $configuration['data'] + $this->defaultConfiguration();
    $this->uuid = $configuration['uuid'];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label' => (string) $this->pluginDefinition['label'],
      'bubble' => 1,
      'level' => MonologLogLevel::INFO,
      'weight' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

}
