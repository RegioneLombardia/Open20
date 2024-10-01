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
use open20\amos\sondaggi\AmosSondaggi;

/**
 * Class SondaggiDomandePagine
 * This is the model class for table "sondaggi_domande_pagine".
 * @package open20\amos\sondaggi\models
 */
class SondaggiInvitationMm extends \open20\amos\sondaggi\models\base\SondaggiInvitationMm
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

    public function getTo() {
        if (AmosSondaggi::instance()->compilationToOrganization) {
            return $this->hasOne(\open20\amos\organizzazioni\models\Profilo::className(), ['id' => 'to_id']);
        } else {
            return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'to_id']);
        }
    }

    public function getSessions() {
        if (AmosSondaggi::instance()->compilationToOrganization) {
            return $this->hasMany(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(), ['organization_id' => 'to_id', 'sondaggi_id' => 'sondaggi_id']);
        } else {
            return $this->hasMany(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(), ['user_id' => 'to_id', 'sondaggi_id' => 'sondaggi_id']);
        }
    }

    public function getLastSession() {
        if (AmosSondaggi::instance()->compilationToOrganization) {
            return $this->hasOne(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(), ['organization_id' => 'to_id', 'sondaggi_id' => 'sondaggi_id'])->orderBy(['end_date' => SORT_DESC])->limit([0, 1]);
        } else {
            return $this->hasOne(\open20\amos\sondaggi\models\SondaggiRisposteSessioni::className(), ['user_id' => 'to_id', 'sondaggi_id' => 'sondaggi_id'])->orderBy(['end_date' => SORT_DESC])->limit([0, 1]);
        }
    }
}
