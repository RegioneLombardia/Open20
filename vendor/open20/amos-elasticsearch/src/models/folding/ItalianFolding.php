<?php

namespace open20\elasticsearch\models\folding;

use open20\elasticsearch\base\ElasticConst;
use open20\elasticsearch\Module;

class ItalianFolding extends Folding {

    private $accentFilter = "ìòàùéèioea";
    private $articles = [
        "c", "l", "all", "dall", "dell",
        "nell", "gl", "un", "m", "t", "s", "v", "d"
    ];

    public function folding($phrase) {
        $module = Module::instance();
        $phrase = str_replace('"', '', $phrase);
        $parts = preg_split('/ +/', $phrase);
        $parts = $this->parseArticles($parts);
        $folded = "";
        foreach ($parts as $key => $part) {
            $char = mb_substr($part, -1);
            $with_end = "";
            if ($char == ElasticConst::WILDCARD_MORE) {
                $with_end = $char;
                $char = mb_substr($part, -2, 1);
            }
            if (strpos($this->accentFilter, $char)) {
                $s = mb_substr($part, 0, empty($with_end) ? -1 : -2);
                $parts[$key] = $s . "?" . $with_end;
            } else {
                if ($module->useFinalSpecial && empty($with_end)) {
                    $parts[$key] = $part . ElasticConst::WILDCARD_MORE;
                }
            }
        }
        $folded = implode(" ", $parts);

        return $folded;
    }

    /**
     * 
     * @param array $parts
     */
    protected function parseArticles($parts) {
        $purifyArray = [];

        foreach ($parts as $key => $part) {
            $remove = false;
            foreach ($this->articles as $article) {
                $val = str_replace($article, '', $part);
                if (strlen($val) <= 1) {
                    $remove = true;
                    break;
                }
            }
            if (!$remove) {
                $purifyArray[$key] = $part;
            }
        }
        return $purifyArray;
    }

}
