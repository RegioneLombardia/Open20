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
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\core\helpers\Html;
use open20\amos\tag\models\Tag;
use yii\base\Exception;
use yii\helpers\Url;
use backend\modules\campains\models\PreferenceCampainTagMm;
use Yii;
use yii\helpers\VarDumper;

/**
 * Class CampainsChannelUtility
 * @package open20\amos\events\utility
 */
class CampainsChannelUtility
{
    

    /**
     * method to save tag associated with the model PreferenceCampain
     *
     * @param array | $tag
     * @param int | $preference_campain_id
     * @return void
     */
    public static function saveTag($tags, $preference_campain_id){

        // create new tags
        foreach ($tags as $tag_id) {
            $preference_campain_tag_mm = new PreferenceCampainTagMm;
            $preference_campain_tag_mm->preference_campain_id = $preference_campain_id;
            $preference_campain_tag_mm->tag_id = $tag_id;

            $preference_campain_tag_mm->save();

        }
    }


    /**
     * method to remove all tags
     *
     * @param PreferenceCampain | $preference_campain
     * @return void
     */
    public static function removeAllPreferenceCampainTag($preference_campain){

        // extract all existing tags
        $tags = PreferenceCampainTagMm::find()->where([
            'preference_campain_id' => $preference_campain->id
        ])->all();

        // remove all existing tags
        foreach ($tags as $tag) {
            $tag->delete();
        }
    }


    /**
     * method to save channel associated with the model PreferenceCampain
     *
     * @param array | $channels
     * @param PreferenceCampain | $preference_campain
     * @return void
     */
    public static function saveChannelUnique($channels, $preference_campain){

        /**
         * controllo la presenza del canale della preferecen channel
         * nella lista checkbox dei canali
         * se non esiste allora procedo con la sua cancellazione
         */
        foreach ( $preference_campain->preferenceCampainChannelMms as $value ) {

            if( !in_array($value->preference_channel_id, $channels) ){
                $value->delete();
            }
        }

 

        /**
         * controllo la presenza del canale associatoal PreferenceCampain
         * se il canale esiste e non risulta cancellato allora salto la creazione del canale
         * se il canale esiste e risulta cancellato allora lo riporto indietro nello stato non cancellato
         */
        foreach ( $channels as $channel_id ) {

            // estrazione del canale associato alla PreferenceChannel
            $channel = PreferenceCampainChannelMm::find()->where([
                'preference_campain_id' => $preference_campain->id,
                'preference_channel_id' => $channel_id,
                // 'deleted_at' => null
            ])->one();

            // se il canale esiste ma è stato cancellato allora lo riporto indietro
            if( null != $channel && $channel->deleted_at != null){
                $channel->deleted_at = null;
                $channel->deleted_by = null;

                $channel->save();
            }

            // se il canale non esite nemmeno tra quegli cancelati allora procedo con la sua creazione
            if( null == $channel ){

                $channel = new PreferenceCampainChannelMm;
                $channel->preference_campain_id = $preference_campain->id;
                $channel->preference_channel_id = $channel_id;

                $channel->save();
            }
        }

    }


    /**
     * Metodo per il controllo dell'esistenza del canale Newsletter
     *
     * @param PreferenceCampain | $campain
     * @return boolean 
     */
    // public static function existNewsletterChannel($campain){

    //     foreach ($campain->getPreferenceCampainChannelMms()->all() as $campainCannel) {

    //         if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    
    /**
     * Metodo per il controllo dell'esistenza del canale Sms
     *
     * @param PreferenceCampain | $campain
     * @return boolean 
     */
    // public static function existSmsChannel($campain){

    //     foreach ($campain->getPreferenceCampainChannelMms()->all() as $campainCannel) {
            
    //         if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }


    /**
     * Undocumented function
     *
     * @param PreferenceCampain $campain
     * @return bool
     */
    // public static function campainHaveNewsletterChannel($campain)
    // {
    //     foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
            
    //        if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
    //            return true;
    //        }
    //     }

    //     return false;
    // }

    /**
     * Undocumented function
     *
     * @param PreferenceCampain $campain
     * @return bool
     */
    // public static function campainHaveSmsChannel($campain)
    // {
    //     foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
    //        if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
    //            return true;
    //        }
    //     }
        
    //     return false;
    // }

    /**
     * Return NewsLetterer model, if not exist return new model of PreferenceCampainChannelMm
     *
     * @param PreferenceCampain $campain
     * @return PreferenceCampainChannelMm
     */
    // public static function campainGetNewsletterModel($campain)
    // {
    //     foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
    //         if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
    //             return $campainCannel;
    //         }
    //      }
    //      return new PreferenceCampainChannelMm();
    // }

    /**
     * Return NewsLetterer model, if not exist return new model of PreferenceCampainChannelMm
     *
     * @param PreferenceCampain $campain
     * @return PreferenceCampainChannelMm
     */
    // public static function campainGetSmsModel($campain)
    // {
    //     foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
    //         if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
    //             return $campainCannel;
    //         }
    //      }
    //      return new PreferenceCampainChannelMm();
    // }

}


