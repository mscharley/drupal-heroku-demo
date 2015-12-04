<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologHandlerDeleteForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\MonologProfileInterface;

/**
 * Form for deleting a handler from a monolog profile.
 */
class MonologHandlerDeleteForm extends ConfirmFormBase {

  /**
   * The monolog profile containing the handler to be deleted.
   *
   * @var \Drupal\monolog\MonologProfileInterface
   */
  protected $profile;

  /**
   * The monolog handler to be deleted.
   *
   * @var \Drupal\monolog\MonologHandlerInterface
   */
  protected $handler;

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the %handler handler from the %profile profile?', array('%profile' => $this->profile->label(), '%handler' => $this->handler->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return $this->profile->urlInfo('edit-form');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'monolog_handler_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, MonologProfileInterface $monolog_profile = NULL, $monolog_handler = NULL) {
    $this->profile = $monolog_profile;
    $this->handler = $this->profile->getHandler($monolog_handler);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->profile->deleteHandler($this->handler);
    drupal_set_message($this->t('The %label handler has been deleted.', array('%label' => $this->handler->label())));
    $form_state->setRedirectUrl($this->profile->urlInfo('edit-form'));
  }

}
