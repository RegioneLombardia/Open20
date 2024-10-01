<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\utility
 * @category   CategoryName
 */

namespace open2\amos\ticket\utility;

use open20\amos\admin\AmosAdmin;
use open2\amos\ticket\AmosTicket;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\models\TicketCategorieUsersMm;
use open2\amos\ticket\widgets\icons\WidgetIconTicketProcessing;
use open2\amos\ticket\widgets\icons\WidgetIconTicketWaiting;
use open2\amos\ticket\widgets\icons\WidgetIconTicketClosed;
use open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie;
use open2\amos\ticket\widgets\icons\WidgetIconTicketFaq;
use open2\amos\ticket\widgets\icons\WidgetIconTicketAll;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class TicketUtility
 * @package open2\amos\ticket\utility
 */
class TicketUtility extends BaseObject
{
    /**
     * @param TicketCategorie|null $excludeCategory
     * @param bool $onlyTicketEnabled
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTicketCategories($excludeCategory = null, $onlyTicketEnabled = false)
    {
        $excludeCategoryIdList = TicketUtility::getIdDiscendenti($excludeCategory);

        // @var ActiveQuery $query 
        $query = TicketCategorie::find();
        $query->andFilterWhere([
            'not in',
            'id', $excludeCategoryIdList
        ]);
        if ($onlyTicketEnabled) {
            $query->andWhere(['abilita_ticket' => true]);
        }

        $abilita_per_community = false;

        // If scope set, filter categories for cwh
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (!empty($scope) && isset($scope['community'])) {
                $abilita_per_community = true;
                $query->andFilterWhere([
                    'community_id' => $scope['community'],
                ]);
            }
        }

        $query->andFilterWhere([
            'abilita_per_community' => $abilita_per_community,
        ]);

        return $query;
    }

    /**
     * @param TicketCategorie|null $category
     * @return array
     */
    private static function getIdDiscendenti($category = null)
    {
        $arrayIdDiscendenti = [];
        if (!is_null($category) && $category->id) {
            $arrayIdDiscendenti[] = $category->id;
            foreach ($category->categorieFiglie as $catFiglia) {
                $arrayIdDiscendenti = array_merge($arrayIdDiscendenti, TicketUtility::getIdDiscendenti($catFiglia));
            }
        }
        return $arrayIdDiscendenti;
    }

    /**
     * @param int $ticket_categoria_id
     * @param bool $alsoAdmin
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getReferenti($ticket_categoria_id, $alsoAdmin = true/*, $print = false*/)
    {
        $ticketCatUserMmTable = TicketCategorieUsersMm::tableName();

        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');

        //cerco gli user_profile referenti di una categoria
        /** @var ActiveQuery $q */
        $q = $userProfileModel::find()
            ->innerJoin($ticketCatUserMmTable, $ticketCatUserMmTable . '.user_profile_id = ' . $userProfileModel::tableName() . '.id')
            ->andWhere([$ticketCatUserMmTable . '.deleted_at' => null])
            ->andWhere([$ticketCatUserMmTable . '.ticket_categoria_id' => $ticket_categoria_id]);
        $q->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $q->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);

        $referentiUserProfile = $q->all();
        $adminTicketUser = ($alsoAdmin) ? self::getAllAdminTicketUsers() : [];

        $referenti = ArrayHelper::merge($referentiUserProfile, $adminTicketUser);

        // TODO - togliere i duplicati di questo metodo. Mettere tutto in un'unica utility e sistemare anche le relazioni dei model.

        return $referenti;
    }
    /**
     * This method checks if the referee logged user must receive the mail for the ticket faq categories.
     * @param int $userId
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function checkIsToSendMailToReferees($userId)
    {
        /** @var \open20\amos\notificationmanager\AmosNotify $notifyModule */
        $notifyModule = (class_exists('open20\amos\notificationmanager\AmosNotify') ? \open20\amos\notificationmanager\AmosNotify::instance() : null);

        if (!is_null($notifyModule)) {
            /** @var \open20\amos\notificationmanager\models\NotificationConf $notificationConfModel */
            $notificationConfModel = $notifyModule->createModel('NotificationConf');
            $notificationConf = $notificationConfModel::findOne(['user_id' => $userId]);
            if (!is_null($notificationConf) && $notificationConf->hasProperty('notify_ticket_faq_referee')) {
                // Notify module and new notification conf field found, the use the new procedure logic that sand mail accordingly with the user preference.
                $sendMail = ($notificationConf->notify_ticket_faq_referee == 1);
            } else {
                // New notification conf field not found, then use old procedure logic that always send mail.
                $sendMail = true;
            }
        } else {
            // Notify module not found, then use old procedure logic that always send mail.
            $sendMail = true;
        }

        return $sendMail;
    }

    /**
     * @param int $ticket_categoria_id
     * @param bool $alsoAdmin
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEmailReferentiCategoria($ticket_categoria_id, $alsoAdmin = true, $withCheckRefereeSettings = false)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();

        /** @var UserProfile $userProfileModel */
        $userProfileModel = $adminModule->createModel('UserProfile');

        $emails = [];
        //cerco gli user_profile referenti di una categoria
        /** @var ActiveQuery $q */
        $q = $userProfileModel::find()
            ->innerJoin('ticket_categorie_users_mm', 'ticket_categorie_users_mm.user_profile_id = user_profile.id')
            ->andWhere(['ticket_categorie_users_mm.deleted_by' => null])
            ->andWhere(['ticket_categorie_users_mm.ticket_categoria_id' => $ticket_categoria_id]);
        $q->andWhere([$userProfileModel::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $q->andWhere(['!=', $userProfileModel::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        $referentiUserProfile = $q->all();

        //ritorno un array con le email degli user referenti
        if (!is_null($referentiUserProfile)) {
            foreach ($referentiUserProfile as $userRecord) {
                /** @var UserProfile $userRecord */
                $user = $userRecord->user;
                if (!$withCheckRefereeSettings || ($withCheckRefereeSettings && self::checkIsToSendMailToReferees($user->id))) {
                    $emails[] = $user->email;
                }
            }
        }
        if ($alsoAdmin) {
            $emails = ArrayHelper::merge($emails, self::getAllAdminTicketUsersEmail(true));
        }
        if (count($emails) > 1) {
            $emails = array_unique($emails);
        }
        return $emails;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllAdminTicketUsers()
    {
        $adminTicketUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('AMMINISTRATORE_TICKET');

        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        /** @var ActiveQuery $query */
        $query = $userProfile::find()
            ->andWhere(['user_id' => $adminTicketUserIds])
            ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
        $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);

        $adminTicketUser = $query->all();

        return $adminTicketUser;
    }

    /**
     * @param bool $withCheckRefereeSettings
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllAdminTicketUsersEmail($withCheckRefereeSettings = false)
    {
        $adminTicketUser = self::getAllAdminTicketUsers();
        $emails = [];
        if (!is_null($adminTicketUser)) {
            foreach ($adminTicketUser as $userRecord) {
                /** @var UserProfile $userRecord */
                $user = $userRecord->user;
                if (!$withCheckRefereeSettings || ($withCheckRefereeSettings && self::checkIsToSendMailToReferees($user->id))) {
                    $emails[] = $user->email;
                }
            }
        }
        return $emails;
    }

    /**
     * @return bool
     */
    public static function hasPartnership()
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        $organizationModuleName = $adminModule->getOrganizationModuleName();
        return (!is_null(Yii::$app->getModule($organizationModuleName)));
    }

    /**
     * @param User|null $user
     * @return \open20\amos\admin\interfaces\OrganizationsModuleInterface|\open20\amos\organizzazioni\models\Profilo|\openinnovation\organizations\models\Organizations|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getUserPrevalentPartnership($user = null)
    {
        if (self::hasPartnership()) {
            if (is_null($user)) {
                $user = Yii::$app->getUser();
            }
            $userProfile = UserProfile::find()->andWhere(['user_id' => $user->id])->one();
            $partnership = $userProfile->prevalentPartnership;
            return $partnership;
        } else {
            return null;
        }
    }

    /**
     * Make the value for organizations in new ticket select
     * @param Profilo $organization
     * @return string
     */
    public static function makeOrganizationIndexForTicketSelect($organization)
    {
        return Ticket::PARTNERSHIP_TYPE_ORGANIZATION . '-' . $organization->id;
    }

    /**
     * Make the value for headquarters in new ticket select
     * @param ProfiloSedi $headquarter
     * @return string
     */
    public static function makeHeadquarterIndexForTicketSelect($headquarter)
    {
        return Ticket::PARTNERSHIP_TYPE_HEADQUARTER . '-' . $headquarter->id;
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getOrganizationsAndHeadquartersByUserId($userId)
    {
        $organizationsAndHeadquarters = [];
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if (!is_null($adminModule)) {
            $organizationsModuleName = $adminModule->getOrganizationModuleName();
            $organizationsModule = Yii::$app->getModule($organizationsModuleName);
            if (!is_null($organizationsModule) && ($organizationsModule instanceof OrganizationsModuleInterface)) {
                $userOrganizations = $organizationsModule->getUserOrganizations($userId);
                foreach ($userOrganizations as $userOrganization) {
                    /** @var Profilo $userOrganization */
                    $organizationsAndHeadquarters[static::makeOrganizationIndexForTicketSelect($userOrganization)] = $userOrganization->getNameField();
                }
                $userHeadquarters = $organizationsModule->getUserHeadquarters($userId);
                foreach ($userHeadquarters as $userHeadquarter) {
                    /** @var ProfiloSedi $userHeadquarter */
                    $organizationsAndHeadquarters[static::makeHeadquarterIndexForTicketSelect($userHeadquarter)] = $userHeadquarter->getNameField() . ' (' . $userHeadquarter->profilo->getNameField() . ')';
                }
            }
        }
        return $organizationsAndHeadquarters;
    }

    /**
     * Return an array with the values used in boolean fields. If the param 'invertValues' is true the values are returned inverted.
     * @param bool $invertValues
     * @return array
     */
    public static function getBooleanFieldsValues($invertValues = false)
    {
        if ($invertValues) {
            return [
                Html::BOOLEAN_FIELDS_VALUE_YES => BaseAmosModule::t('amoscore', 'Yes'),
                Html::BOOLEAN_FIELDS_VALUE_NO => BaseAmosModule::t('amoscore', 'No')
            ];
        } else {
            return [
                Html::BOOLEAN_FIELDS_VALUE_NO => BaseAmosModule::t('amoscore', 'No'),
                Html::BOOLEAN_FIELDS_VALUE_YES => BaseAmosModule::t('amoscore', 'Yes')
            ];
        }
    }

    /**
     * Return an array with all the manage links.
     * @return array
     */
    public static function getManageLink()
    {

        $module = \Yii::$app->getModule('ticket');
        $disableTicketManagement = (!empty($module) ? $module->disableTicketManagement : false);

        if (!$disableTicketManagement) {

            if (Yii::$app->user->can(WidgetIconTicketAll::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#ticket_all_description'),
                    'label' => AmosTicket::t('amosticket', '#ticket_all_title'),
                    'url' => '/ticket/ticket/index'
                ];
            }

            if (\Yii::$app->user->can(WidgetIconTicketProcessing::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#ticket_processing_description'),
                    'label' => AmosTicket::t('amosticket', '#ticket_processing_title'),
                    'url' => '/ticket/ticket/ticket-processing'
                ];
            }

            if (\Yii::$app->user->can(WidgetIconTicketWaiting::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#ticket_waiting_description'),
                    'label' => AmosTicket::t('amosticket', '#ticket_waiting_title'),
                    'url' => '/ticket/ticket/ticket-waiting'
                ];
            }

            if (\Yii::$app->user->can(WidgetIconTicketClosed::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#ticket_closed_description'),
                    'label' => AmosTicket::t('amosticket', '#ticket_closed_title'),
                    'url' => '/ticket/ticket/ticket-closed'
                ];
            }

            if (\Yii::$app->user->can(WidgetIconTicketCategorie::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#ticket_category_description'),
                    'label' => AmosTicket::t('amosticket', '#ticket_category_title'),
                    'url' => '/ticket/ticket-categorie/index',
                ];
            }
            if (\Yii::$app->user->can(WidgetIconTicketFaq::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#all_faq_description'),
                    'label' => AmosTicket::t('amosticket', '#all_faq'),
                    'url' => '/ticket/assistenza/cerca-faq',
                ];
            }

            if (\Yii::$app->user->can(WidgetIconTicketFaq::class)) {
                $links[] = [
                    'title' => AmosTicket::t('amosticket', '#manage_faq_description'),
                    'label' => AmosTicket::t('amosticket', '#manage_faq'),
                    'url' => '/ticket/ticket-faq/index',
                ];
            }
        }

        return $links;
    }
}
