<?php
/**
 */

namespace creocoder\flysystem;

use Spatie\Dropbox\Client;
use Spatie\Dropbox\TokenProvider;
use Spatie\FlysystemDropbox\DropboxAdapter;
use yii\base\InvalidConfigException;

/**
 * DropboxFilesystem
 *
 */
class DropboxFilesystem extends Filesystem
{
    /**
     * @var string|array|TokenProvider
     */
    public $token;

    /**
     * @var string|null
     */
    public $prefix = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->token === null) {
            throw new InvalidConfigException('The "token" property must be set.');
        }

        parent::init();
    }

    /**
     * @return DropboxAdapter
     */
    protected function prepareAdapter()
    {
        return new DropboxAdapter(
            new Client($this->token),
            $this->prefix
        );
    }
}
