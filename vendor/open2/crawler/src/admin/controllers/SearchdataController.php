<?php

namespace open2\crawler\admin\controllers;

/**
 * Search Data Controller.
 *
 * @since 1.0.0
 */
class SearchdataController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string $modelClass The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = '\open2\crawler\models\Searchdata';
}
