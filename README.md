# drupal-heroku-demo

A barebones Drupal 8 installation that falls somewhere between the core `minimal` and `standard` installation profiles,
which can easily be deployed to Heroku.

## Installation

```sh
$ git clone git@github.com:mscharley/drupal-heroku-demo
$ cd drupal-heroku-demo
$ composer install
```

## Deploying

Install the [Heroku Toolbelt](https://toolbelt.heroku.com/).

```sh
$ heroku create
$ heroku addons:create heroku-postgresql:hobby-dev
$ git push heroku master
$ heroku open core/install.php
```

You can also use a SQLite database if you wish for testing purposes but be aware that this database won't last through
a Heroku dyno restart which notably happens whenever you push code to Heroku as well as roughly every 24 hours.

## Documentation

For more information about the installation profile, see the Drupal project:

- [Drupal@Heroku](https://www.drupal.org/sandbox/mscharley/2629336)

For more information about using PHP on Heroku, see these Dev Center articles:

- [PHP on Heroku](https://devcenter.heroku.com/categories/php)
