<?php

namespace spk\yii2loganalyzer;


use Yii;
use yii\base\ErrorException;
use yii\base\Widget;

class LogAnalyzerWidget extends Widget
{
    public $filters = array();

    public $log_file_path;

    private $last_status;

    public function init()
    {
        parent::init();

        if (!$this->log_file_path) {
            $this->log_file_path = Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'app.log';
        }

    }

    public function run()
    {
        /**
         * Load log file
         */
        try {
            $log = file_get_contents($this->log_file_path);
        } catch (ErrorException $ex) {
            return 'Ошибок нет';
        }

        if (empty($log)) {
            return 'Ошибок нет';
        }

        /**
         * Explode log on messages
         */

        $log = explode('=====', $log);

        array_pop($log);

        $log = array_reverse($log);

        $log = array_map('trim', $log);

        LogAnalyzerAssets::register($this->view);

        return $this->render('index', array(
            'log' => $log,
            'model' => $this,
        ));
    }

    public function getClearUrl($clear = true)
    {
        $url = '/';

        $url .= Yii::$app->controller->id;

        if ($clear) {
            $url .= '/clearlog';
        }

        return Yii::$app->urlManager->createUrl([$url,'path' => $this->log_file_path]);
    }

    public function filterLog($text)
    {
        foreach ($this->filters as $f) {
            if (preg_match('/' . $f . '/', $text)) {
                return false;
            }
        }

        return true;
    }

    public function showDate($text)
    {
        return date('H:i d.m.Y', strtotime(mb_substr($text, 0, 20, 'utf8')));
    }

    public function showError($text)
    {
        $text = mb_substr($text, 20, mb_strlen($text, 'utf8'), 'utf8');

        $text = explode('Stack trace:', $text);
        $text = $text[0];

        if ($this->last_status != "") {
            $text = str_replace($this->last_status . " ", "", $text);
        }

        return $text;
    }

    public function showStack($text)
    {
        $text = explode('Stack trace:', $text);
        return @$text[1];
    }

    public function showStatus($text)
    {
        if (preg_match('%\[error\]%', $text)) {
            $this->last_status = '[error]';
            return array('status' => 'error', 'class' => 'label-important');
        } elseif (preg_match('%\[warning\]%', $text)) {
            $this->last_status = '[warning]';
            return array('status' => 'warning', 'class' => 'label-warning');
        } elseif (preg_match('%\[info\]%', $text)) {
            $this->last_status = '[info]';
            return array('status' => 'info', 'class' => 'label-info');
        } else {
            return array('status' => 'undefined', 'class' => '');
        }
    }
}