<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\admin\widgets\icons;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\admin\models\UserProfile;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconValidatedUserProfiles
 * @package open20\amos\admin\widgets\icons
 */
class WidgetIconValidatedUserProfiles extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-darkGrey'
        ];

        $this->setLabel(AmosAdmin::tHtml('amosadmin', 'Validated users'));
        $this->setDescription(AmosAdmin::t('amosadmin', 'List of validated platform users'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('user');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('users');
        }

        $this->setUrl(['/'. AmosAdmin::getModuleName(). '/user-profile/validated-users']);
        $this->setCode('VALIDATED_USERS');
        $this->setModuleName(AmosAdmin::getModuleName());
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        $query = new Query();
        $query
            ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
            ->from(UserProfile::tableName())
            ->where([UserProfile::tableName().'.attivo' => UserProfile::STATUS_ACTIVE])
            ->andWhere([UserProfile::tableName().'.deleted_at' => null]);

        $this->setBulletCount(
            $this->makeBulletCounter(
                Yii::$app->getUser()->getId(), AmosAdmin::instance()->model('UserProfile'), $query
            )
        );
    }
}