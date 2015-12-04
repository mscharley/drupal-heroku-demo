<?php

/**
 * @file
 * Contains \Drupal\monolog\Form\MonologHandlerEditForm.
 */

namespace Drupal\monolog\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\monolog\MonologProfileInterface;

/**
 * Provides an edit form for image effects.
 */
class MonologHandlerEditForm extends MonologHandlerFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, MonologProfileInterface $monolog_profile = NULL, $monolog_handler = NULL) {
    $form = parent::buildForm($form, $form_state, $monolog_profile, $monolog_handler);

    $form['#title'] = $this->t('Edit %label handler', array('%label' => $this->handler->label()));
    $form['actions']['submit']['#value'] = $this->t('Update handler');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareHandler($monolog_handler_id) {
    return $this->profile->getHandler($monolog_handler_id);
  }

}
