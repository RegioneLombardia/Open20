<?php

namespace frontend\modules\metrics\models;

use app\modules\cms\models\NavItem;
use open20\amos\admin\models\UserProfile;
use open20\amos\news\models\News;
use frontend\modules\metrics\Module;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Non UTILIZZATO! NON UTILIZZARE!
 */
class OperatorContent extends Model
{

    public function init()
    {
        // MODEL NON USATO ! LO BLOCCO!
        die;
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @var string $startDate
     */
    public $startDate;
    public $uid;
    /**
     * @var string $endDate
     */
    public $endDate;

    public function rules()
    {
        return [
            [['startDate', 'endDate'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startDate' => Module::t('metrics', 'Da'),
            'endDate' => Module::t('metrics', 'A'),
        ];
    }

    /**
     * @param $params
     * @param $userProfile UserProfile
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function statsOperatorContents($params, $userProfile)
    {
        /** @var ActiveQuery $queryNews */
        $queryNews = News::find();
        $queryNews->andWhere(['created_by' => $userProfile->user_id]);
        $queryNews->andWhere(['status' => Operators::NEWS_PUBBLISHED_STATUS]);

        /** @var ActiveQuery $queryPages */
        $queryPages = NavItem::find();
        $queryPages->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id');
        $queryPages->innerJoin('admin_user', 'admin_user.id = cms_nav_item.create_user_id');
        $queryPages->innerJoin('user', 'user.email = admin_user.email');
        $queryPages->andWhere(['cms_nav.is_deleted' => 0]);
        $queryPages->andWhere(['cms_nav.is_offline' => 0]);
        $queryPages->andWhere(['user.status' => 10]);
        $queryPages->andWhere(['user.id' => $userProfile->user_id]);

        $queryImages = new Query();
        $queryImages->from('attach_file');
        $queryImages->andWhere(['created_by' => $userProfile->user_id]);
        $queryImages->andWhere(['attach_file.type' => Operators::IMAGES_EXTENSIONS]);

        $queryAttachemnts = new Query();
        $queryAttachemnts->from('attach_file');
        $queryAttachemnts->andWhere(['created_by' => $userProfile->user_id]);
        $queryAttachemnts->andWhere(['attach_file.type' => Operators::ATTACHMENTS_EXTENSIONS]);

        $toret['created_news'] = $queryNews->count();
        $toret['created_pages'] = $queryPages->count();
        $toret['created_images'] = $queryImages->count();
        $toret['created_attachments'] = $queryAttachemnts->count();

        return $toret;

/*
        if (!($this->load($params) && $this->validate())) {
            $toret['created_news'] = $queryNews->count();
            $toret['created_pages'] = $queryPages->count();

            return $toret;
        }

        if (!empty($this->startDate)) {
            $queryNews->andWhere(['>=', new Expression('DATE(created_at)'), new Expression('DATE(\'' . $this->startDate . '\')')]);
        }
        if (!empty($this->endDate)) {
            $queryNews->andWhere(['<=', new Expression('DATE(created_at)'), new Expression('DATE(\'' . $this->endDate . '\')')]);
        }

        $toret['created_news'] = $queryNews->count();

        if (!empty($this->startDate)) {
            $queryPages->andWhere(['>=', new Expression('DATE(from_unixtime(timestamp_create))'), new Expression('DATE(\'' . $this->startDate . '\')')]);
        }
        if (!empty($this->endDate)) {
            $queryPages->andWhere(['<=', new Expression('DATE(from_unixtime(timestamp_create))'), new Expression('DATE(\'' . $this->endDate . '\')')]);
        }
        $toret['created_pages'] = $queryPages->count();

        return $toret;
*/
    }


}