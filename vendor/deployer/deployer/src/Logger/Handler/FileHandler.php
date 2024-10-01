<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Logger\Handler;

class FileHandler implements HandlerInterface
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function log(string $message)
    {
        file_put_contents($this->filePath, $message, FILE_APPEND);
    }
}
