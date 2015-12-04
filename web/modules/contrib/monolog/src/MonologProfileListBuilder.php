<?php

/**
 * @file
 * Contains \Drupal\monolog\MonologProfileListBuilder.
 */

namespace Drupal\monolog;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines a class to build a listing of monolog profile entities.
 *
 * @see \Drupal\monolog\Entity\MonologProfile
 */
class MonologProfileListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [
      'label' => $this->t('Profile'),
      'machine_name' => $this->t('Machine name'),
      'handlers' => $this->t('Handlers'),
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $handlers = [];
    foreach ($entity->getHandlers()->sort() as $handler) {
      $handlers[] = $handler->label();
    }
    $row = [
      'label' => SafeMarkup::checkPlain($entity->label()),
      'machine_name' => $entity->id(),
      'handlers' => implode(', ', $handlers),
    ];

    if (empty($row['handlers'])) {
      $row['handlers'] = '-';
    }

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    $build['#prefix'] = $this->t('<p>A <strong>profile</strong> is a collection of handlers that process the record.</p><p>Common examples of handlers are a <em>syslog handler</em> that routes records to the syslog and a <em>stream wrapper handler</em> that writes records to files and other streams.</p>');

    $build['#empty'] = $this->t('There are no logging profiles. Add one by clicking the "Add profile" link above.');
    $build['#caption'] = $this->t('Logging Profiles');

    return $build;
  }

}
