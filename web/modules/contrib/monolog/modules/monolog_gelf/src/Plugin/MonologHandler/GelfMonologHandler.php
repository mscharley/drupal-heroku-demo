<?php

/**
 * @file
 * Handler include for FirePHPHandler.
 */

namespace Drupal\monolog_gelf\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\ConfigurableMonologHandlerBase;
use Monolog\Handler\GelfHandler;
use Gelf\MessagePublisher;

/**
 * Logs records to a Graylog2 server.
 *
 * @MonologHandler(
 *   id = "gelf",
 *   label = @Translation("GELF Handler"),
 *   description = @Translation("Logs records to a Graylog2 server."),
 *   group = @Translation("Servers and networked logging"),
 * )
 */
class GelfMonologHandler extends ConfigurableMonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    $publisher = new MessagePublisher($this->configuration['hostname'], $this->configuration['port'], $this->configuration['chunk_size']);
    return new GelfHandler($publisher, $this->configuration['level'], $this->configuration['bubble']);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['hostname'] = array(
      '#title' => $this->t('Hostname'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['hostname'],
      '#description' => $this->t('The hostname of the Graylog2 server.'),
      '#required' => TRUE,
    );

    $form['port'] = array(
      '#title' => $this->t('Port'),
      '#type' => 'number',
      '#default_value' => $this->configuration['port'],
      '#description' => $this->t('The port that the server is listening on.'),
      '#required' => TRUE,
      '#size' => 6,
    );

    $form['chunk_size'] = array(
      '#title' => $this->t('Chunk size'),
      '#type' => 'number',
      '#default_value' => $this->configuration['chunk_size'],
      '#description' => $this->t('The size of chunked messages in bytes. This allows larger messages to be broken up into smaller pieces.'),
      '#required' => TRUE,
      '#size' => 6,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['hostname'] = $form_state->getValue('hostname');
    $this->configuration['port'] = $form_state->getValue('port');
    $this->configuration['chunk_size'] = $form_state->getValue('chunk_size');
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'hostname' => '',
      'port' => 12201,
      'chunk_size' => 1420,
    ];
  }

}
