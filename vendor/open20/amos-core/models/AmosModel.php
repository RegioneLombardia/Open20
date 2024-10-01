<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\models
 * @category   CategoryName
 */

namespace open20\amos\core\models;

use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\validators\StringHtmlValidator;
use Yii;

/**
 * Class AmosModel
 * This is the root model implementing attribute purification.
 * @package open20\amos\core\models
 */
class AmosModel extends \yii\base\Model
{

	/**
	 * @inheritdoc
	 */
	public function beforeValidate()
	{

			$enablePurifyDataParam = true;

			if (isset(Yii::$app->params['forms-purify-data']) && (Yii::$app->params['forms-purify-data'] == false)) {
					$enablePurifyDataParam = false;
			}

			if ($enablePurifyDataParam == true) {

					if (isset(Yii::$app->params['forms-purify-data-white-models'])) {
							$listClassModels = Yii::$app->params['forms-purify-data-white-models'];
							if (in_array($this->className(), $listClassModels)) {
									return parent::beforeValidate();
							}
					}

					$listAttributes = $this->attributes;
					foreach ($listAttributes as $key => $attribute) {
							if (is_string($this->$key)) {
									$this->$key = StringHelper::purifyString($this->$key);
							}
					}
			}

			return parent::beforeValidate();
	}

	/**
	 * @inheritdoc
	 */
    public function afterFind()
    {
        parent::afterFind();

        if (!empty(\Yii::$app->params['disableAfterFindPurify'])) {
            return;
        }

        $listAttributes = $this->attributes;
        foreach ($listAttributes as $attribute => $value) {
            $isStrict = true;
            $validators = $this->getActiveValidators($attribute);
            foreach ($validators as $validator) {
                if ($validator instanceof StringHtmlValidator) {
                    $isStrict = false;
                    break;
                }
            }
            if (is_string($this->$attribute)) {
                $this->$attribute = StringHelper::purifyString(Html::decode($value), $isStrict);
            }
        }
    }

}
