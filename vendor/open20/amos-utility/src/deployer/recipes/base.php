<?php
/**
 * Recipes for deploy platforms
 * @author: Damian Gomez
 */
namespace Deployer;

use Exception;
use yii\httpclient\Client;

require_once __DIR__ . '/../../../../../deployer/deployer/recipe/common.php';

/**
 * Uploaded files
 */
set(
    'shared_dirs',
    [
        'backend/web/static',
        'frontend/web/static',
        'common/uploads',
        'backend/models/translations',
        'backend/sondaggi',
    ]
);

/**
 * Main task
 */
task(
    'deploy:configure',
    function () {
        $executable = input()->getOption('php');

        if(empty($executable)) {
            $executable = ask("PHP Executable",'php');
        }

        set('php_executable', $executable);
    }
)->desc('Configure Env');

task(
    'deploy:unlock',
    function () {
        //TODO Unlock
    }
)->desc('Lock platform on db');

task(
    'deploy:cleanup',
    function () {
        //TODO Clean
    }
)->desc('Lock platform on db');

task(
    'deploy:backup',
    function () {
        //cd('{{update_path}}');

        //
        writeln("TODO implementare backup");
    }
)->desc('Back-up Current Codebase');


task(
    'deploy:prepare_env',
    function () {
        $updatePath = get('update_path');
        $updateExtractPath = $updatePath . '/update';

        //Workdir
        cd($updatePath);

        if (!file_exists($updatePath)) {
            if (!mkdir($updatePath)) {
                throw new \yii\base\Exception('Unable To Create Update Dir');
            }
        }

        if (!file_exists($updateExtractPath)) {
            if (!mkdir($updateExtractPath)) {
                throw new \yii\base\Exception('Unable To Create Update Extraction Dir');
            }
        }

        run("rm -Rf {$updateExtractPath}/*");
    }
)->desc('Start Deployment');

task(
    'deploy:prepare_update',
    function () {
        $moduleParams = \Yii::$app->getModule('utility')->params;

        if (!$moduleParams || !$moduleParams['master_domain'] || !$moduleParams['key']) {
            throw new \yii\base\Exception("Funzione Non Disponibile!");
        }

        //Override the base url for better UrlPath syntax
        $client = new Client([
                                 'baseUrl' => $moduleParams['master_domain'],
                                 'transport' => 'yii\httpclient\CurlTransport'
                             ]);

        $retiries = 0;

        do {
            //Loop slowly
            $retiries == 0 ?: sleep(30);

            $retiries++;

            $data = [
                'platformId' => $moduleParams['platform_id'],
                'key' => $moduleParams['key']
            ];

            $force = askChoice("Request for new update?", ['y' => 'Yes','n' => 'No'],'n');

            if ($force == 'y') {
                $data['force'] = 'true';
            }

            $response = $client->createRequest()
                ->setUrl(['/platforms/platforms/prepare-update'])
                ->setData($data)
                ->send();
        } while ($response->data['status'] != 'ready' && $retiries <= 10);

        if ($response->data['status'] != 'ready') {
            throw new \yii\base\Exception("Can't Update due too many retries");
        }
    }
)->desc('Start Deployment');

task(
    'deploy:download_update',
    function () {
        $moduleParams = \Yii::$app->getModule('utility')->params;

        if (!$moduleParams || !$moduleParams['master_domain'] || !$moduleParams['key']) {
            throw new \yii\base\Exception("Funzione Non Disponibile!");
        }

        //Override the base url for better UrlPath syntax
        $client = new Client([
                                 'baseUrl' => $moduleParams['master_domain'],
                                 'transport' => 'yii\httpclient\CurlTransport'
                             ]);


        $data = [
            'platformId' => $moduleParams['platform_id'],
            'key' => $moduleParams['key']
        ];

        $updatePath = get('update_path');
        $updateFile = $updatePath . '/' . get('update_file_name');

        if (file_exists($updateFile)) {
            $force = askChoice("Download te update?", ['y' => 'Yes','n' => 'No'],'n');

            if($force == 'y') {
                unlink($updateFile);
            } else {
                return true;
            }
        }

        $fh = fopen($updateFile, 'w');

        $client->createRequest()
            ->setUrl(['/platforms/platforms/download-update'])
            ->setData($data)
            ->setMethod('GET')
            ->setOutputFile($fh)
            ->send();
    }
)->desc('Download Deployment');

task(
    'deploy:unpack_and_check',
    function () {
        cd('{{update_path}}');

        $updateFile = get('update_file_name');
        $enviroment = input()->getOption('env');

        if(empty($executable)) {
            $enviroment = ask("Environment",$enviroment);
        }

        run("tar -xzvf {$updateFile} -C update");

        //Move to update Dir
        cd('{{update_path}}/update');

        run("{{php_executable}} init --env=\"{$enviroment}\" --overwrite=All");

        run('{{php_executable}} yii utility/check-integrity');
    }
)->desc('Unpack Update and Check Integrity');

task(
    'deploy:upgrade',
    function () {
        //Move to root path
        cd(\Yii::getAlias('@vendor/..'));

        run('rsync -avz --exclude "common/uploads" common/uploads/updates/update/* .');
        run('{{php_executable}} yii migrate up --interactive=0');
    }
)->desc('Upgrade Existing CodeBase');

task(
    'deploy:cleanup',
    function () {
        //TODO
    }
)->desc('Clean Caches and Temporary Files');

/**
 * Deployment procedures
 */
task(
    'deploy',
    [
        'deploy:configure',
        'deploy:backup',
        'deploy:prepare_env',
        'deploy:prepare_update',
        'deploy:download_update',
        'deploy:unpack_and_check',
        'deploy:upgrade',
        'deploy:unlock',
        'deploy:cleanup'
    ]
)->desc('Update From Master');

//On deploy completed
after('deploy', 'success');
