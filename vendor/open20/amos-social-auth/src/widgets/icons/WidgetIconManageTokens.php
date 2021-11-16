<?php
namespace open20\amos\socialauth\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\socialauth\Module;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconManageTokens extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {

        return false;
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(Module::tHtml('amosadmin', 'Gestisci Token'));
        $this->setDescription(Module::t('amosadmin', 'Gestione dei token di aiutenticazione Oauth2'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/socialauth/oauth2/manage']);
        $this->setCode('SOCIAL_TOKENS');
        $this->setModuleName(Module::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }
}
