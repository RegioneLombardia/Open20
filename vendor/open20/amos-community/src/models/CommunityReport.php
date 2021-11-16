<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\models
 * @category   CategoryName
 */

namespace open20\amos\community\models;

use yii\base\Model;

/**
 * Class CommunityReport
 * @package open20\amos\community\models
 */
class CommunityReport extends Model
{
    /**
     * @var int $id
     */
    public $id;
    
    /**
     * @var string $title
     */
    public $title;
    
    /**
     * @var mixed $reportValue
     */
    public $reportValue;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'reportValue'], 'safe'],
            [['title'], 'string'],
            [['id'], 'integer'],
        ];
    }
}
