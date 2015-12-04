<?php

/**
 * @file
 * Handler include for HipChatHandler.
 */

namespace Drupal\monolog\Plugin\MonologHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\ConfigurableMonologHandlerInterface;
use Drupal\monolog\ConfigurableMonologHandlerBase;
use Monolog\Handler\HipChatHandler;

/**
 * Logs records to a HipChat chat room using its API.
 *
 * @MonologHandler(
 *   id = "hipchat",
 *   label = @Translation("HipChat Handler"),
 *   description = @Translation("Logs records to a HipChat chat room using its API."),
 *   group = @Translation("Alerts and emails"),
 * )
 */
class HipChatMonologHandler extends ConfigurableMonologHandlerBase implements ConfigurableMonologHandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function getHandlerInstance() {
    return new HipChatHandler($this->configuration['token'], $this->configuration['room'], $this->configuration['contact_name'], $this->configuration['notify'], $this->configuration['level'], $this->configuration['bubble']);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['token'] = array(
      '#title' => $this->t('HipChat API Token'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['token'],
      '#description' => $this->t('HipChat API Token.'),
      '#required' => TRUE,
    );

    $form['room'] = array(
      '#title' => $this->t('Room'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['room'],
      '#description' => $this->t('The room that should be alerted of the message (Id or Name).'),
      '#required' => TRUE,
    );

    $form['contact_name'] = array(
      '#title' => $this->t('Contact Name'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['contact_name'],
      '#description' => $this->t('Name used in the "from" field.'),
    );

    $form['notify'] = array(
      '#title' => $this->t('Trigger a notification in clients.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['notify'],
      '#description' => $this->t('Check this box to notify HipChat clients connected to the room.'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['token'] = $form_state->getValue('token');
    $this->configuration['room'] = $form_state->getValue('room');
    $this->configuration['contact_name'] = $form_state->getValue('contact_name');
    $this->configuration['notify'] = $form_state->getValue('notify');
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'token' => '',
      'room' => '',
      'contact_name' => \Drupal::config('system.site')->get('site_name'),
      'notify' => 0,
    ];
  }

}