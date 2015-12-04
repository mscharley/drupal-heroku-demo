<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\ChannelForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a form that configures monolog channel settings.
 */
class ChannelForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'monolog_channel_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $channel_info = monolog_channel_info_load_all();
    $channel_profiles = $this->config('monolog.settings')->get('channel_profiles');

    $form['description'] = array(
      '#markup' => $this->t('<p>A <strong>channel</strong> identifies which part of the application a record is related to.</p><p>Each channel is associated with a <a href="@href">profile</a> that defines which handlers are used to process the record, for example a <em>syslog handler</em> or <em>stream wrapper handler</em>.</p>', array('@href' => '')),
    );

    $form['channel_profiles'] = array(
      '#type' => 'table',
      '#caption' => $this->t('Logging Channels'),
      '#header' => [
        $this->t('Channel'),
        $this->t('Logging Profile'),
      ],
      '#empty' => $this->t('There are no available logging channels.'),
      '#attributes' => array('id' => 'monolog-channel-table'),
    );

    $profiles = \Drupal::entityManager()->getStorage('monolog_profile')->loadMultiple();
    $profile_options = [];
    foreach ($profiles as $profile) {
      $profile_options[$profile->id()] = SafeMarkup::checkPlain($profile->label());
    }

    foreach ($channel_info as $channel_name => $channel) {
      $form['channel_profiles'][$channel_name]['label'] = ['#markup' => SafeMarkup::checkPlain($channel['label'])];
      $form['channel_profiles'][$channel_name]['profile'] = array(
        '#type' => 'select',
        '#options' => $profile_options,
        '#default_value' => isset($channel_profiles[$channel_name]) ? $channel_profiles[$channel_name] : $channel['default profile'],
      );
    }

    $form['actions'] = array(
      '#type' => 'actions',
    );
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save channel settings'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $channel_profiles = array();
    $values = $form_state->getValues();
    foreach ($values['channel_profiles'] as $name => $channel) {
      $channel_profiles[$name] = $channel['profile'];
    }

    $this->config('monolog.settings')
      ->set('channel_profiles', $channel_profiles)
      ->save();
    drupal_set_message($this->t('The configuration options have been saved.'));
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['monolog.settings'];
  }

}
