<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\rules
 * @category   CategoryName
 */

namespace open20\amos\documenti\rules;

use open20\amos\core\rules\UserCreatorContentOnDomain;

class UserIsNotCommunityReaderDocumentiRule extends UserCreatorContentOnDomain
{
    public $name = 'userIsNotCommunityReaderDocumentiRule';

    public function execute($user, $item, $params)
    {
        // RULE PER CREAZIONE DOCUMENTI
        // Se è un documento di piattaforma CREATORE_DOCUMENTI può crearla di default,
        // altrimenti controlla con la rule UserCreatorContentOnDomain
        $cwhModule = \Yii::$app->getModule('cwh');
        if($cwhModule) {
            $scope = $cwhModule->getCwhScope();
            if (empty($scope)) {
                return true;
            } else {
                return parent::execute($user, $item, $params);
            }
        }
        return true;
    }

}