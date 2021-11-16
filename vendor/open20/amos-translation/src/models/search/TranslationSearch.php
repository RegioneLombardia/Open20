<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 04/01/2019
 * Time: 11:46
 */

namespace open20\amos\translation\models\search;


use open20\amos\translation\utility\TranslationUtility;
use yii\base\Model;

class TranslationSearch extends Model
{
    public $isTranslated;
    public $attributes;
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['isTranslated','attributes'], 'safe'],
        ];
    }

}