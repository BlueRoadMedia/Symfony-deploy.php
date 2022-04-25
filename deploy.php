<?php
namespace Deployer;

require 'recipe/symfony.php';

// deploy.php file
//      .gitignore
// https://stackoverflow.com/questions/51221515/add-deployer-deploy-php-recipe-to-gitignore


// Config

// TODO Add example configuration about multiple hosts which share some common settings.

// Repository to deploy. This repository will be cloned to a hosting server
// server when creating a new release.
set('repository', 'git@github.com:[USER]/[REPO].git');

// This env config will be used for `bin/console dump-env` command ('deploy:dump-env' task).
// https://symfony.com/doc/current/configuration.html#selecting-the-active-environment
// https://github.com/deployphp/deployer/commit/c23688adcfb6050dcf847bc58bf6adfac3b68147
set('symfony_env', 'prod');


// Hosts
// https://deployer.org/docs/7.x/hosts
// https://lorisleiva.com/deploy-your-laravel-app-from-scratch/install-and-configure-deployer#hosts

// The hosts section defines a deployment location. You can call it by the
// hosting server url address (e.g. 'example.com', 'staging.example.com' etc.) or 
// any other way you see fit (e.g. 'production', 'staging' etc.)
host('[EXAMPLE.COM]')
    // In case the actual hosting server's ssh connection address is different
    // than what is host defined as, a 'hostname' setting must be provided.
    // The 'hostname' will be used for the actual ssh connection destination.
    // 1. Can be a hosting server url.
    // ->set('hostname', '[EXAMPLE.EXAMPLE.COM]')
    // 2. Or it can be a hosting server ip address.
    // ->set('hostname', '63.245.75.182')
    // Website user. Deployer uses this config for actual ssh connection to a
    // hosting server (e.g. website_user@example.com).
    ->set('remote_user', '[WEBSITE_USER]')
    // Ssh connection to a hosting server.
    // 1. It is a good practice to keep hosting server's ssh connection parameters
    // out of 'deploy.php'file, as they can change depending on where the deploy
    // is executed from. Only specify 'hostname' and 'remote_user' and for the
    // rest use the ssh 'config' file. The default location is '~/.ssh/config'.
    // ->set('config_file', '[PATH/TO/SSH/CONFIG/FILE]')
    // 2. Or provide the path to a private key for ssh connection to a hosting
    // server.
    // ->set('identity_file', '[PATH/TO/PRIVATE/KEY]')
    // The path to the location on a hosting server where a Symfony application
    // should be deployed.
    ->set('deploy_path', '[PATH/TO/APP/DIRECTORY]')
    // Deployer uses this config to assign writing permissions required by Symfony
    // applications, to certain directories (e.g. cache, log). Default value is 'acl'.
    // Other possible values are: 'chown', 'chgrp' and 'chmod'.
    // Uncomment if there is an error in making directories writable, in the
    // deployment process.
    // ->set('writable_mode', 'chmod')
;


// Tasks
// https://deployer.org/docs/7.x/tasks

// 'Update your deployment tools/workflow to run the dump-env command after each
// deploy to improve the application performance.'
// https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-production
// https://github.com/deployphp/deployer/pull/2507/commits/e9996c0d522ac36e7cc42fca5f11bbb62662d259
desc('Compile .env files');
task('deploy:dump-env', function () {
    run('cd {{release_or_current_path}} && {{bin/composer}} dump-env {{symfony_env}}');
});

after('deploy:vendors', 'deploy:dump-env');
after('deploy:failed', 'deploy:unlock');
