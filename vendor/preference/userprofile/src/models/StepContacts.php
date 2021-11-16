<?php

namespace preference\userprofile\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Model without table
 * 
 */
class StepContacts extends Model
{
    public $target_cittadini;
    public $target_impresa;
    public $target_enti_operatori;
    public $email_cittadini;
    public $email_impresa;
    public $email_enti_operatori;
    public $phone_cittadini;
    public $phone_impresa;
    public $phone_enti_operatori;

    public function rules()
    {
        return [
            [['target_cittadini', 'target_impresa', 'target_enti_operatori'], 'string'],
            [['email_cittadini', 'email_impresa', 'email_enti_operatori'], 'email'],
            [['phone_cittadini', 'phone_impresa', 'phone_enti_operatori'], 'safe'],
            [
                ['email_cittadini'], 'required',
                'when' => function ($model) {
                    // VarDumper::dump( $model->target_cittadini, $depth = 10, $highlight = true); die;
                    return $model->target_cittadini === 'on';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#target_cittadini-id').prop('checked') == 'on';
                }"
            ],

            [
                ['email_impresa'], 'required',
                'when' => function ($model) {
                    return $model->target_impresa === 'on';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#target_impresa-id').prop('checked') == 'on';
                }"
            ],

            [
                ['email_enti_operatori'], 'required',
                'when' => function ($model) {
                    return $model->target_enti_operatori === 'on';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#target_enti_operatori-id').prop('checked') == 'on';
                }"
            ],

        ];
    }

    public function attributeLabels()
    {
        return [
            'target_cittadini' => Yii::t('preferenceuser', 'Cittadini'),
            'target_impresa' => Yii::t('preferenceuser', 'Imprese'),
            'target_enti_operatori' => Yii::t('preferenceuser', 'Enti/operatori'),
            'email_cittadini' => Yii::t('preferenceuser', 'Email'),
            'phone_cittadini' => Yii::t('preferenceuser', 'Cellulare'),
            'email_impresa' => Yii::t('preferenceuser', 'Email'),
            'phone_impresa' => Yii::t('preferenceuser', 'Cellulare'),
            'email_enti_operatori' => Yii::t('preferenceuser', 'Email'),
            'phone_enti_operatori' => Yii::t('preferenceuser', 'Cellulare'),
        ];
    }
}
