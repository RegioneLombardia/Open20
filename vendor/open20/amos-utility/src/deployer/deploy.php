<?php
namespace Deployer;

/**
 * @var $deployer Deployer
 * @var $yiiRouter array
 */

//Ensure Procedures Can DIE
set_time_limit(3600);

//Additional libs
use Symfony\Component\Console\Input\InputOption;

//Additional arguments
option('command', null, InputOption::VALUE_OPTIONAL, 'Yii Console Command');
option('force', null, InputOption::VALUE_OPTIONAL, 'Force Procedure', false);
option('env', null, InputOption::VALUE_REQUIRED, 'Environment');
option('php', null, InputOption::VALUE_REQUIRED, 'PHP Executable', 'php');

// Initialize an Yii2 Console App and get access to all functions
require 'initYii.php';

// Deployer reciper for yii2 advaned template/amos basic template
require 'recipes/base.php';

// Configuration for SSH compatibility
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('update_path', \Yii::getAlias('@vendor/../common/uploads/updates'));
set('update_file_name', 'update.tar.gz');

# Default Params
Deployer::setDefault('default_timeout', 900);