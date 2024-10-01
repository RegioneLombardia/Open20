<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Support\Changelog;

class ParseException extends \Exception
{
    public function __construct(string $message = '', $code = '')
    {
        parent::__construct("$message\n\n{$code}\n\n");
    }
}
