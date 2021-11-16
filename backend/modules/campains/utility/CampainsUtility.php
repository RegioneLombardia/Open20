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

use DateTime;
use DateInterval;
use yii\helpers\Url;
use yii\base\Exception;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use Exception as GlobalException;
use open20\amos\tag\models\Tag;
use open20\amos\core\helpers\Html;
use backend\modules\campains\models\PreferenceCampain;
use preference\userprofile\models\PreferenceChannel;
use backend\modules\campains\models\PreferenceCampainTagMm;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\PreferenceCampainContainerType;

/**
 * Class CampainsUtility
 * @package open20\amos\events\utility
 */
class CampainsUtility
{
	
	private static $channelNewsletterId = PreferenceChannel::NEWSLETTER_ID;
	private static $channelSmsId = PreferenceChannel::SMS_ID;
	private static $channelAppId = PreferenceChannel::APP_ID;
	

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
		$tags = PreferenceCampainTagMm::find()->andWhere([
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
		 * controllo la presenza del canale PreferenceCampainChannelMm per la PreferenceCampain
		 * nella lista checkbox dei canali
		 * se non esiste il canale allora procedo con la sua cancellazione
		 */
		foreach ( $preference_campain->preferenceCampainChannelMms as $value ) {

			if( !in_array($value->preference_channel_id, $channels) ){
				$value->delete();
			}
		}


		/**
		 * controllo la presenza del canale associatoal PreferenceCampain
		 * se il canale esiste e non risulta cancellato allora salto la creazione del canale
		 */
		foreach ( $channels as $channel_id ) {

			// estrazione del canale associato alla PreferenceChannel
			$channel = PreferenceCampainChannelMm::find()->where([
				'preference_campain_id' => $preference_campain->id,
				'preference_channel_id' => $channel_id,
				'deleted_at' => null
			])->one();


			// se il canale non esiste allora procedo con la sua creazione
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
	public static function existNewsletterChannel($campain){

		foreach ($campain->getPreferenceCampainChannelMms()->all() as $campainCannel) {

			if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Metodo per l'estrazione del numero dell'ultimo ordinamento 
	 *
	 * @return int | $last_number_order | max(order)
	 */
	public static function lastNumberOrderNewsletterContainer(){


		// TODO 
	}

	
	/**
	 * Metodo per il controllo dell'esistenza del canale Sms
	 *
	 * @param PreferenceCampain | $campain
	 * @return boolean 
	 */
	public static function existSmsChannel($campain){

		foreach ($campain->getPreferenceCampainChannelMms()->all() as $campainCannel) {
			
			if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Metodo per il controllo dell'esistenza del canale Sms
	 *
	 * @param PreferenceCampain | $campain
	 * @return boolean 
	 */
	public static function existAppChannel($campain){

		foreach ($campain->getPreferenceCampainChannelMms()->all() as $campainCannel) {
			
			if ($campainCannel->preferenceChannel->id == self::$channelAppId) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Undocumented function
	 *
	 * @param PreferenceCampain $campain
	 * @return bool
	 */
	public static function campainHaveNewsletterChannel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
			
		   if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
			   return true;
		   }
		}

		return false;
	}

	/**
	 * Undocumented function
	 *
	 * @param PreferenceCampain $campain
	 * @return bool
	 */
	public static function campainHaveSmsChannel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
		   if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
			   return true;
		   }
		}
		
		return false;
	}

	/**
	 * Undocumented function
	 *
	 * @param PreferenceCampain $campain
	 * @return bool
	 */
	public static function campainHaveAppChannel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
		   if ($campainCannel->preferenceChannel->id == self::$channelAppId) {
			   return true;
		   }
		}
		
		return false;
	}

	/**
	 * Return NewsLetterer model, if not exist return new model of PreferenceCampainChannelMm
	 *
	 * @param PreferenceCampain $campain
	 * @return PreferenceCampainChannelMm
	 */
	public static function campainGetNewsletterModel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
			if ($campainCannel->preferenceChannel->id == self::$channelNewsletterId) {
				$campainCannel->setScenario(PreferenceCampainChannelMm::SCENARIO_NEWSLETTER);
				return $campainCannel;
			}
		}
		$newModel = new PreferenceCampainChannelMm();
		$newModel->setScenario(PreferenceCampainChannelMm::SCENARIO_SMS);
		return $newModel;
	}

		/**
	 * Return NewsLetterer model, if not exist return new model of PreferenceCampainChannelMm
	 *
	 * @param PreferenceCampain $campain
	 * @return PreferenceCampainChannelMm
	 */
	public static function campainGetAppModel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
			if ($campainCannel->preferenceChannel->id == self::$channelAppId) {
				$campainCannel->setScenario(PreferenceCampainChannelMm::SCENARIO_APP);
				return $campainCannel;
			}
		}
		$newModel = new PreferenceCampainChannelMm();
		$newModel->setScenario(PreferenceCampainChannelMm::SCENARIO_SMS);
		return $newModel;
	}


	/**
	 * Return NewsLetterer model, if not exist return new model of PreferenceCampainChannelMm
	 *
	 * @param PreferenceCampain $campain
	 * @return PreferenceCampainChannelMm
	 */
	public static function campainGetSmsModel($campain)
	{
		foreach ($campain->preferenceCampainChannelMms as $campainCannel) {
			if ($campainCannel->preferenceChannel->id == self::$channelSmsId) {
        
				$campainCannel->setScenario(PreferenceCampainChannelMm::SCENARIO_SMS);
				return $campainCannel;
			}
		}
		$newModel = new PreferenceCampainChannelMm();
		$newModel->setScenario(PreferenceCampainChannelMm::SCENARIO_SMS);
		return $newModel;
	}


	/**
	 * Metodo per la ricerca del PreferenceCampainContainerType
	 *
	 * @param string | $name
	 * @return model | PreferenceCampainContainerType
	 */
	public static function preferenceCampainContainerTypeByName($name){

		return PreferenceCampainContainerType::find()->where(['name' => $name])->one();
	}


	/**
	 * Metodo per l'estrazione delle PreferenceCampainContainer associate a PreferenceCampainSection
	 * ordinamento per colonna order SORT_ASC
	 *
	 * @param int | $preference_campain_section_id
	 * @return PreferenceCampainContainer
	 */
	public static function getAllPreferenceCampainContainersBySection($preference_campain_section_id){

		return PreferenceCampainContainer::find()->where([
			'preference_campain_section_id' => $preference_campain_section_id,
			'deleted_at' => null
		])->orderBy(['order' => SORT_ASC])->all();
	}


	/**
	 * Metodo per il ritorno dei PreferenceCampainContainers per tipo associato alla PreferenceCampainSection
	 *
	 * @param int | $preference_campain_section_id
	 * @param int | $preference_campain_container_type_id
	 * @return PreferenceCampainContainer
	 */
	public static function getPreferenceCampainContainersBySectionType($preference_campain_section_id, 
		$preference_campain_container_type_id){

		return PreferenceCampainContainer::find()->where([
			'preference_campain_section_id' => $preference_campain_section_id,
			'preference_campain_container_type_id' => $preference_campain_container_type_id,
			'deleted_at' => null
		])->all();

	}

	/**
	 * Undocumented function
	 * 		1	18-25
	 * 		2	26-35
	 * 		3	36-45
	 * 		4	46-55
	 * 	 	5	56-65
	 *  	6	>65
	 * 		7	<18
	 * @param string $birthDate
	 * @return mixed
	 */
	public static function getAgeGroupByBirthDate($birthDate)
	{

		if (empty($birthDate)) {
			return null;
		}

		try {
			$birthDateObj = new DateTime($birthDate);
			$dateNowObj = new DateTime();
			/** @var DateInterval $interval */
			$interval = $dateNowObj->diff($birthDateObj);
		
			if ($interval->y < 18) {
				return 7;
			} elseif(($interval->y >= 18) && ($interval->y <= 18)) {
				return 1;
			} elseif(($interval->y >= 26) && ($interval->y <= 35)) {
				return 2;
			} elseif(($interval->y >= 36) && ($interval->y <= 45)) {
				return 3;
			} elseif(($interval->y >= 46) && ($interval->y <= 55)) {
				return 4;
			} elseif(($interval->y >= 56) && ($interval->y <= 65)) {
				return 5;
			} elseif ($interval->y > 65) {
				return 6;
			}
		} catch (Exception $e) {
			return null;
		}

		return null;
	}


	/**
	 * Method for creating a copy of the PreferenceCampain
	 * 
	 * @param model | PreferenceCampain $preference_campain
	 * @param model | PreferenceCampainCopy $preference_campain_copy
	 *
	 * @return model | PreferenceCampain
	 */
	public static function copyPreferenceCampain($preference_campain, $preference_campain_copy){

		$copy_preference_campain = new PreferenceCampain;
		$copy_preference_campain->setAttributes($preference_campain->attributes);
		$copy_preference_campain->titolo = $preference_campain_copy->titolo;

		return $copy_preference_campain;
	}


	/**
	 * Method to create a copy of the PreferenceCampainTagMm model
	 *
	 * @param model | PreferenceCampainTagMm | $preference_campain_tag_mm
	 * @param model | PreferenceCampain $copy_preference_campain
	 * 
	 * @return model | PreferenceCampainTagMm
	 */
	public static function copyPreferenceCampainTagMm($preference_campain_tag_mm, $copy_preference_campain){

		$copy_preference_campain_tag_mm = new PreferenceCampainTagMm;

		foreach($preference_campain_tag_mm->attributes as  $key => $value) {

			$copy_preference_campain_tag_mm->{$key} = $value;
		}

		$copy_preference_campain_tag_mm->preference_campain_id = $copy_preference_campain->id;
		$copy_preference_campain_tag_mm->id = NULL;

		return $copy_preference_campain_tag_mm;
	}


	/**
	 * Method to create a copy of the PreferenceCampainChannelMm model
	 *
	 * @param model | PreferenceCampainChannelMm | $preference_campain_channel_mm
	 * @param model | PreferenceCampain $copy_preference_campain
	 * 
	 * @return model | PreferenceCampainChannelMm
	 */
	public static function copyPreferenceCampainChannelMms($preference_campain_channel_mm, $copy_preference_campain){

		$copy_preference_campain_channel_mm = new PreferenceCampainChannelMm;

		foreach($preference_campain_channel_mm->attributes as  $key => $value) {

			// salto gli attributi che iniziano per 'mailup'
			if( strpos($key, 'mailup') === false ){

				$copy_preference_campain_channel_mm->{$key} = $value;

			}else{
				
				$copy_preference_campain_channel_mm->{$key} = NULL;
			}
		}

		$copy_preference_campain_channel_mm->preference_campain_id = $copy_preference_campain->id;
		$copy_preference_campain_channel_mm->id = NULL;
		$copy_preference_campain_channel_mm->status = "PreferenceCampainChannelMmWorkflow/TOBEAPPROVED";

		return $copy_preference_campain_channel_mm;
	}



	/**
	 * Method to create a copy of the PreferenceCampainSection model
	 *
	 * @param model | PreferenceCampainSection $section
	 * @param model | PreferenceCampainChannelMm $copy_preference_campain_channel_mm
	 * 
	 * @return model PreferenceCampainSection 
	 */
	public static function copyPreferenceCampainSection($section, $copy_preference_campain_channel_mm){

		$copy_preference_campain_section = new PreferenceCampainSection;

		foreach($section->attributes as  $key => $value) {

			$copy_preference_campain_section->{$key} = $value;
		}

		$copy_preference_campain_section->preference_campain_channel_mm_id = $copy_preference_campain_channel_mm->id;
		$copy_preference_campain_section->id = NULL;

		return $copy_preference_campain_section;
	}


	/**
	 * Method for creating a PreferenceCampainContaier copy model
	 *
	 * @param model | PreferenceCampainContainer $container
	 * @param model | PreferenceCampainSection $copy_preference_campain_section
	 * 
	 * @return model PreferenceCampainContainer
	 */
	public static function copyPreferenceCampainContainer($container, $copy_preference_campain_section){

		$copy_preference_campain_container = new PreferenceCampainContainer;

		foreach ($container->attributes as $key => $value) {
			$copy_preference_campain_container->{$key} = $value;
		}
		$copy_preference_campain_container->preference_campain_section_id = $copy_preference_campain_section->id;
		$copy_preference_campain_container->id = NULL;

		return $copy_preference_campain_container;
	}

}


