<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models;

use yii\helpers\ArrayHelper;

/**
 * Class SondaggiDomandePagine
 * This is the model class for table "sondaggi_domande_pagine".
 * @package open20\amos\sondaggi\models
 */
class SondaggiUsersInvitationMm extends \open20\amos\sondaggi\models\base\SondaggiUsersInvitationMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'id'
        ];
    }
}
