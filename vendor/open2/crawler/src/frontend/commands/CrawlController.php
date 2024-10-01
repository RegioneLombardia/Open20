<?php

namespace open2\crawler\frontend\commands;

use open2\crawler\frontend\classes\CrawlContainer;
use luya\helpers\FileHelper;
use yii\console\widgets\Table;

/**
 * Crawler console Command.
 *
 * ```sh
 * ./vendor/bin/luya crawler/crawl
 * ```
 *
 * Add verbositiy while crawling:
 *
 * ```sh
 * ./vendor/bin/luya crawler/crawl --verbose
 * ```
 *
 * @since 1.0.0
 */
class CrawlController extends \luya\console\Command
{
    public $verboseDebug = true;
    
    public function actionIndex($resume = false)
    {
        // sart time measuremnt
        $start = microtime(true);
       
        $container = new CrawlContainer([
            'baseUrl' => $this->module->baseUrl,
            'filterRegex' => $this->module->filterRegex,
            'verbose' => $this->verbose,
            'verboseDebug' => $this->verboseDebug,
            'doNotFollowExtensions' => $this->module->doNotFollowExtensions,
            'useH1' => $this->module->useH1,
            'resume' => $resume,
        ]);
        
        $timeElapsed = round((microtime(true) - $start) / 60, 2);
        
        $table = new Table();
        $table->setHeaders(['status', 'url', 'message']);
        $table->setScreenWidth(80);
        $table->setRows($container->getReport());
        $this->output($table->run());
        $this->outputInfo('memory usage: ' . FileHelper::humanReadableFilesize(memory_get_usage()));
        $this->outputInfo('memory peak usage: ' . FileHelper::humanReadableFilesize(memory_get_peak_usage()));
        
        return $this->outputSuccess('Crawler finished in ' . $timeElapsed . ' min.');
    }

     /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return ['verbose', 'interactive', 'verboseDebug'];
    }

}
