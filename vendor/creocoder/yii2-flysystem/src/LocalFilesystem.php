<?php
/**
 */

namespace creocoder\flysystem;

use League\Flysystem\Adapter\Local;
use Yii;
use yii\base\InvalidConfigException;

/**
 * LocalFilesystem
 *
 */
class LocalFilesystem extends Filesystem
{
    /**
     * @var string
     */
    public $path;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }

        $this->path = Yii::getAlias($this->path);

        parent::init();
    }

    /**
     * @return Local
     */
    protected function prepareAdapter()
    {
        return new Local($this->path);
    }
}
