<?php
/**
 * Profile code for the Heroku profile.
 *
 * This provides integrations with the Heroku environment.
 */

function heroku_form_alter(&$form, &$form_state, $form_id) {
  var_dump($form_id);
}

function heroku_form_install_settings_form_alter(&$form, &$form_state) {
  $database_variables = [
    // MySQL options. Recommended option as many contrib modules support MySQL much better than Postgres.
    'JAWSDB_URL', 'CLEARDB_DATABASE_URL',
    // Heroku Postgres.
    'DATABASE_URL',
  ];

  $scheme_mapping = [
    'postgres' => 'pgsql',
    'mysql2' => 'mysql',
  ];

  $dbconn = NULL;
  $detected_var = '';
  $configured = FALSE;
  foreach ($database_variables as $var) {
    $dbconn = getenv($var);
    if ($dbconn) {
      $detected_var = $var;
      break;
    }
  }

  if ($dbconn) {
    $db_url = parse_url($dbconn);
    $driver = empty($scheme_mapping[$db_url['scheme']]) ? $db_url['scheme'] : $scheme_mapping[$db_url['scheme']];
    $args = [
      '@var' => $detected_var,
      '@driver' => $driver,
    ];

    if (empty($form['driver']['#options'][$driver])) {
      drupal_set_message(t('Found a connection string in the environment variable @var that uses the @driver driver but either that driver is either not supported by Drupal or your Heroku instance. Have you configured the correct PHP extensions in your composer.json?', $args), 'error');
    }
    else {
      $form['driver']['#default_value'] = $driver;
      $configured = TRUE;
      $settings = &$form['settings'][$driver];

      $settings['database']['#default_value'] = substr($db_url['path'], 1);
      $settings['username']['#default_value'] = $db_url['user'];
      $settings['password']['#type'] = 'textfield';
      $settings['password']['#default_value'] = $db_url['pass'];
      $settings['advanced_options']['host']['#default_value'] = $db_url['host'];
      if (!empty($db_url['port'])) {
        $settings['advanced_options']['port']['#default_value'] = $db_url['port'];
      }

      drupal_set_message(t('Automatically configured your database connection settings using the environment variable @var.', $args));
    }
  }

  if (!$configured) {
    if (getenv('DYNO')) {
      drupal_set_message(t('Couldn\'t find any database settings. Defaulting to SQLite. This is fine for a testing environment but won\'t suffice for production.'), 'warning');
    }
    // If we don't have a valid connection string available then default to SQLite, which is good enough for local dev
    // or a free instance.
    $form['driver']['#default_value'] = 'sqlite';
  }
}
