<?php
namespace spk\yii2loganalyzer;

use yii\base\InvalidConfigException;
use yii\log\FileTarget;

class LogAnalyzerFileTarget extends FileTarget
{

    public function export()
    {
		$logPath = dirname($this->logFile);
		FileHelper::createDirectory($logPath, $this->dirMode, true);
	
        $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\r\n=====\r\n";
        if (($fp = @fopen($this->logFile, 'a')) === false) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFile}");
        }
        @flock($fp, LOCK_EX);
        if ($this->enableRotation) {
            // clear stat cache to ensure getting the real current file size and not a cached one
            // this may result in rotating twice when cached file size is used on subsequent calls
            clearstatcache();
        }
        if ($this->enableRotation && @filesize($this->logFile) > $this->maxFileSize * 1024) {
            $this->rotateFiles();
            @flock($fp, LOCK_UN);
            @fclose($fp);
            @file_put_contents($this->logFile, $text, FILE_APPEND | LOCK_EX);
        } else {
            @fwrite($fp, $text);
            @flock($fp, LOCK_UN);
            @fclose($fp);
        }
        if ($this->fileMode !== null) {
            @chmod($this->logFile, $this->fileMode);
        }
    }

}