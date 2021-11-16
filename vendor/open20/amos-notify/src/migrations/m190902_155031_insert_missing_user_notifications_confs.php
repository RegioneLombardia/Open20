<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\user\User;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use yii\db\ActiveQuery;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190902_155031_insert_missing_user_notifications_confs
 */
class m190902_155031_insert_missing_user_notifications_confs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ok = $this->addMissingUserNotificationsConfs();
        return $ok;
    }

    /**
     * This method add the notifications configurations for all users that these configurations are missing.
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function addMissingUserNotificationsConfs()
    {
        /** @var ActiveQuery $query */
        $query = User::find();
        $query->select(['id']);
        $allUserIds = $query->column();

        $queryNotify = new Query();
        $queryNotify->select(['user_id']);
        $queryNotify->from(NotificationConf::tableName());
        $queryNotify->where(['deleted_at' => null]);
        $allNotificationConfsUserIds = $queryNotify->column();

        $missingNotificationConfsUserIds = array_diff($allUserIds, $allNotificationConfsUserIds);

        $allOk = true;
        foreach ($missingNotificationConfsUserIds as $userId) {
            $ok = $this->setDefaultNotificationsConfs($userId);
            if (!$ok) {
                $allOk = false;
            }
        }

        return $allOk;
    }

    /**
     * This method set the user default notifications configurations.
     * @param int $userId
     * @return bool
     */
    public function setDefaultNotificationsConfs($userId)
    {
        $emailFrequency = NotificationsConfOpt::EMAIL_DAY;
        $smsFrequency = 0;
        $params = [
            'notifications_enabled' => 1,
            'notify_content_pubblication' => 1,
            'notify_comments' => 1,
        ];
        return $this->saveNotificationConf($userId, $emailFrequency, $smsFrequency, $params);
    }

    /**
     * The method save the notification configuration.
     * @param int $userId
     * @param int $emailFrequency
     * @param int $smsFrequency
     * @param array $params
     * @return bool
     */
    public function saveNotificationConf($userId, $emailFrequency = 0, $smsFrequency = 0, $params = [])
    {
        // Check the params type
        if (!is_numeric($userId) || !is_numeric($emailFrequency) || !is_numeric($smsFrequency)) {
            return false;
        }
        // Check the params presence
        if (!$emailFrequency && !$smsFrequency) {
            return false;
        }
        $query = new Query();
        $query->select(['user_id']);
        $query->from(NotificationConf::tableName());
        $query->where(['deleted_at' => null]);
        $query->andWhere(['user_id' => $userId]);
        $notificationConf = $query->one();

        $isNew = false;
        if ($notificationConf === false) {
            $isNew = true;
            $now = date('Y-m-d H:i:s');
            $notificationConf['user_id'] = $userId;
            $notificationConf['created_at'] = $now;
            $notificationConf['updated_at'] = $now;
            $notificationConf['created_by'] = 1;
            $notificationConf['updated_by'] = 1;
        }

        $notificationConf['email'] = $emailFrequency;

        if (isset($params['notifications_enabled'])) {
            $notificationConf['notifications_enabled'] = $params['notifications_enabled'];
        }
        if (isset($params['notify_content_pubblication'])) {
            $notificationConf['notify_content_pubblication'] = $params['notify_content_pubblication'];
        }
        if (isset($params['notify_comments'])) {
            $notificationConf['notify_comments'] = $params['notify_comments'];
        }

        if ($isNew) {
            try {
                $this->insert(NotificationConf::tableName(), $notificationConf);
                $ok = true;
            } catch (\Exception $exception) {
                $ok = false;
            }
        } else {
            try {
                $this->update(NotificationConf::tableName(), $notificationConf, [
                    'user_id' => $userId,
                    'deleted_at' => null
                ]);
                $ok = true;
            } catch (\Exception $exception) {
                $ok = false;
            }
        }

        return $ok;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190902_155031_insert_missing_user_notifications_confs cannot be reverted.\n";
        return false;
    }
}
