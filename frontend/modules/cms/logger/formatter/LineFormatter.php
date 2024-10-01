<?php
namespace app\modules\cms\logger\formatter;

use Monolog\Formatter\LineFormatter as BaseLineFormatter;
use Monolog\Utils;
use SoapFault;


class LineFormatter extends BaseLineFormatter {

    /**
     * 
     * @param Throwable $e
     * @param int $depth
     * @return string
     */
    protected function normalizeException($e) {
        $str = $this->formatException($e);

        if ($previous = $e->getPrevious()) {
            do {
                $str .= "\n[previous exception] " . $this->formatException($previous);
            } while ($previous = $previous->getPrevious());
        }

        return $str;
    }

    /**
     * 
     * @param Throwable $e
     * @return string
     */
    private function formatException($e) {
        $str = '[object] (' . Utils::getClass($e) . '(code: ' . $e->getCode();
        if ($e instanceof SoapFault) {
            if (isset($e->faultcode)) {
                $str .= ' faultcode: ' . $e->faultcode;
            }

            if (isset($e->faultactor)) {
                $str .= ' faultactor: ' . $e->faultactor;
            }

            if (isset($e->detail)) {
                if (is_string($e->detail)) {
                    $str .= ' detail: ' . $e->detail;
                } elseif (is_object($e->detail) || is_array($e->detail)) {
                    $str .= ' detail: ' . $this->toJson($e->detail, true);
                }
            }
        }
        $str .= '): ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine() . ')';

        if ($this->includeStacktraces) {
            $str .= "\n[stacktrace]\n" . $this->jTraceEx($e) . "\n";
        }

        return $str;
    }

    /**
     * 
     * @param type $e
     * @param type $seen
     * @return string
     */
    protected function jTraceEx($e, $seen = null) {
        $starter = $seen ? 'Caused by: ' : '';
        $result = array();
        if (!$seen)
            $seen = array();
        $trace = $e->getTrace();
        $prev = $e->getPrevious();
        $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
        $file = $e->getFile();
        $line = $e->getLine();
        while (true) {
            $current = "$file:$line";
            if (is_array($seen) && in_array($current, $seen)) {
                $result[] = sprintf(' ... %d more', count($trace) + 1);
                break;
            }
            $result[] = sprintf(' at %s%s%s(%s%s%s)',
                    count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                    count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                    count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                    $line === null ? $file : basename($file),
                    $line === null ? '' : ':',
                    $line === null ? '' : $line);
            if (is_array($seen))
                $seen[] = "$file:$line";
            if (!count($trace))
                break;
            $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
            $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
            array_shift($trace);
        }
        $result = join("\n", $result);
        if ($prev)
            $result .= "\n" . $this->jTraceEx($prev, $seen);

        return $result;
    }

}
