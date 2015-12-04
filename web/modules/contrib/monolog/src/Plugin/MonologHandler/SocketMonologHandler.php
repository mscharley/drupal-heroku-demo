<?php

/**
 * @file
 * Handler include for SocketHandler include.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\MonologHandlerBase;
use Monolog\Handler\SocketHandler;

/**
 * Logs records to sockets, use this for UNIX and TCP sockets.
 *
 * @MonologHandler(
 *   id = "socket",
 *   label = @Translation("Sockets Handler"),
 *   description = @Translation("Logs records to sockets, use this for UNIX and TCP sockets."),
 *   group = @Translation("Servers and networked logging"),
 * )
 */
class SocketMonologHandler extends MonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    $handler = new SocketHandler($this->configuration['connection_string'], $this->configuration['level'], $this->configuration['bubble']);
    $handler->setPersistent((bool) $this->configuration['persistent']);
    if ($this->configuration['connection_timeout']) {
      $handler->setConnectionTimeout($this->configuration['connection_timeout']);
    }
    if ($this->configuration['write_timeout']) {
      $handler->setTimeout($this->configuration['write_timeout']);
    }

    return $handler;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['connection_string'] = array(
      '#title' => $this->t('Socket connection string'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['connection_string'],
      '#description' => $this->t('The socket connection string, for example <code>unix:///var/log/httpd_app_log.socket</code>.'),
      '#required' => TRUE,
    );

    $form['persistent'] = array(
      '#title' => $this->t('Set socket connection to be persistent'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['persistent'],
    );

    $form['connection_timeout'] = array(
      '#title' => $this->t('Connection timeout'),
      '#type' => 'number',
      '#description' => t('The socket connection timeout in seconds.'),
      '#default_value' => $this->configuration['connection_timeout'],
      '#size' => 5,
    );

    $form['write_timeout'] = array(
      '#title' => $this->t('Write timeout'),
      '#type' => 'number',
      '#description' => $this->t('The socket write timeout in seconds.'),
      '#default_value' => $this->configuration['write_timeout'],
      '#size' => 5,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['connection_string'] = $form_state->getValue('connection_string');
    $this->configuration['persistent'] = $form_state->getValue('persistent');
    $this->configuration['connection_timeout'] = $form_state->getValue('connection_timeout');
    $this->configuration['write_timeout'] = $form_state->getValue('write_timeout');
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'connection_string' => '',
      'persistent' => 0,
      'connection_timeout' => 5,
      'write_timeout' => 60,
    ];
  }

}
