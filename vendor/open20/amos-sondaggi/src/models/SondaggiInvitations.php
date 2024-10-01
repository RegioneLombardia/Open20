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

use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\ArrayHelper;

/**
 * Class SondaggiDomandePagine
 * This is the model class for table "sondaggi_domande_pagine".
 * @package open20\amos\sondaggi\models
 */
class SondaggiInvitations extends \open20\amos\sondaggi\models\base\SondaggiInvitations
{

    const TARGET_USERS = 1;
    const TARGET_ORGANIZATIONS = 2;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }

    public function getGroups() {
        return ProfiloGroups::find()->andWhere(['id' => $this->search_groups]);
    }

    /**
     * @return array
     */
    public function getTargetLabel()
    {
        return [
            self::TARGET_USERS => AmosSondaggi::t('amossondaggi', 'Utenti'),
            self::TARGET_ORGANIZATIONS => AmosSondaggi::t('amossondaggi', 'Organizzazioni'),
        ];
    }
}
