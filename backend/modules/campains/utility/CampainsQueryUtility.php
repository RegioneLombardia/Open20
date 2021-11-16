<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace backend\modules\campains\utility;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use preference\userprofile\models\PreferenceChannel;
use backend\modules\campains\models\search\UserCampainSearch;
use open20\amos\admin\models\UserProfile;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\VarDumper;

/**
 * Class EventsUtility
 * @package open20\amos\events\utility
 */
class CampainsQueryUtility
{

    /**
     * Undocumented function
     *
     * @return ActiveQuery
     */
    public static function getQueryBaseForRecipients()
    {
        /** @var ActiveQuery $query */
        $query = UserProfile::find()
            //->select(new Expression("user.*, user_profile.*, attr.*"))
            ->innerJoin('cwh_tag_owner_interest_mm as interessi', '`interessi`.classname = \''.addslashes(UserProfile::className()).'\' AND `interessi`.record_id = user_profile.id')
            ->innerJoin('preference_target_chosen_for_user_view as target_chosen', '`target_chosen`.user_profile_id = user_profile.id AND interessi.root_id = `target_chosen`.root_id')
            ->innerJoin('tag', 'tag.id = interessi.tag_id')
            ->innerJoin('user', 'user.id = user_profile.user_id')
            // Gli attributi sul targhet, la mail il telefono di contatto e se sono validati
            ->innerJoin('preference_user_target_attribute attr', 'attr.target_code COLLATE utf8_general_ci = target_chosen.target_code and attr.user_id = user_profile.user_id')
            // Se sono stati abilitati i canali di contatto sulla tematica - colleganemto con l'utente e il tag
            ->innerJoin('preference_topic_channel_mm canale_abilitato', 'canale_abilitato.topic_code COLLATE utf8_general_ci = tag.codice AND canale_abilitato.user_id = user_profile.user_id') 
            ->innerJoin('preference_channel canale', 'canale.id = canale_abilitato.preference_channel_id')
            ->where(['interessi.deleted_at' => null])
            ->andWhere(['canale_abilitato.deleted_at' => null])
            ->andWhere(['canale.active' => 1])
            ->andWhere(['tag.pc_active' => 1])
            ->andWhere(['user.status' => 10])
            ->andWhere(['user_profile.privacy' => 1])
            ;
    
        $query->groupBy('user_profile.id');

        return $query;
    }

    /**
     * Undocumented function
     *
     * @return ActiveQuery of PreferenceCampainChannelMm
     */
    public static function getQueryBaseForCampainsChannels()
    {
        /** @var ActiveQuery $query */
        $query = PreferenceCampainChannelMm::find()

            ->joinWith('preferenceCampain.preferenceCampainTagMms.tag', true, 'INNER JOIN')
            ->joinWith('preferenceChannel', true, 'INNER JOIN')
            ->innerJoin('cwh_tag_owner_interest_mm as interessi', '`interessi`.classname = \''.addslashes(UserProfile::className()).'\' AND `interessi`.tag_id = preference_campain_tag_mm.tag_id')
            ->innerJoin('user_profile', 'user_profile.id = interessi.record_id')
            ->innerJoin('user', 'user.id = user_profile.user_id')
            ->innerJoin('preference_target_chosen_for_user_view as target_chosen', '`target_chosen`.user_profile_id = user_profile.id AND target_chosen.root_id = `interessi`.root_id')
            
            ->andWhere(['interessi.deleted_at' => null])
            ->andWhere(['user_profile.deleted_at' => null])
            ->andWhere(['tag.deleted_at' => null])
            ->andWhere(['preference_campain_channel_mm.status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT])
            ->andWhere(['preference_channel.active' => 1])
            ->andWhere(['tag.pc_active' => 1])
            ->andWhere(['user.status' => 10])
            ->andWhere(['user_profile.privacy' => 1])

            // CANALE APP
            ->andWhere(['preference_channel.id' => PreferenceChannel::APP_ID])

            // ->orderBy('user_profile.id')

            ->groupBy('preference_campain_channel_mm.id')
            ;

            

        
        return $query;
    }

    /**
     * Undocumented function
     *
     * @param PreferenceCampainChannelMm $channelMm
     * @return ActiveQuery
     */    
    public static function getQueryRecipientsByChannelModel(PreferenceCampainChannelMm $channelMm)
    {
        $channelId = $channelMm->preference_channel_id;
        $tagIds = ArrayHelper::map($channelMm->preferenceCampain->preferenceCampainTagMms, 'id', 'tag_id');

        $query = self::getQueryBaseForRecipients();


        // Controllo che abbiano validato email e telefono per il contatto
        if ($channelId == 1) {
            $query->andWhere(['attr.validated_email_flag' => 1]);
        }
        if ($channelId == 2) {
            $query->andWhere(['attr.validated_phone_flag' => 1]);
        }

        // FILTRO il canale corrente
        $query->andWhere(['canale.id' => $channelId]);


        // FILTRO i tag scelti sulla campagna
        $query->andWhere(['tag.id' => $tagIds]);

        if (!empty($channelMm->preferenceCampain->search_params)) {
            $modelSearch = new UserCampainSearch();
            $query_params = unserialize($channelMm->preferenceCampain->search_params);
            if ($modelSearch->load($query_params)) {
                $query = $modelSearch->addFiltersToQuery($query);
            }
        }

        return $query;
    }

 
    public static function getQueryRecipientsByChannelAndTags(int $channelId, array $tags = [], $modelSearch = null)
    {
        $query = self::getQueryBaseForRecipients();

        // Controllo che abbiano validato email e telefono per il contatto
        if ($channelId == 1) {
            $query->andWhere(['attr.validated_email_flag' => 1]);
        }

        if ($channelId == 2) {
            $query->andWhere(['attr.validated_phone_flag' => 1]);
        }

        // FILTRO il canale corrente
        $query->andWhere(['canale.id' => $channelId]);

        // FILTRO i tag scelti sulla campagna
        $query->andWhere(['tag.id' => $tags]);

        if (!is_null($modelSearch)) {
            $query = $modelSearch->addFiltersToQuery($query);
        }

        return $query;
    }

}