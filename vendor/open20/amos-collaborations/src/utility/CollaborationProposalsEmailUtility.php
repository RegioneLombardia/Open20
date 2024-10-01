<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\utility
 * @category   CategoryName
 */

namespace open20\amos\collaborations\utility;

use open20\amos\core\utilities\Email;
use yii\base\BaseObject;
use yii\helpers\Html;

/**
 * Class CollaborationProposalsEmailUtility
 * @package open20\amos\collaborations\utility
 */
class CollaborationProposalsEmailUtility extends BaseObject
{
    /**
     * @param string $from
     * @param array $tos
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     * @param array $params
     * @return bool
     */
    public static function sendMail($from, $tos, $subject, $text, $files = [], $bcc = [], $params = [], $priority = 0, $use_queue = false, $cc = [])
    {
        $ok = true;
        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = \Yii::$app->getModule('email');
        if (isset($mailModule)) {
            if (is_null($from)) {
                if (isset(\Yii::$app->params['email-assistenza'])) {
                    // Use default platform email assistance
                    $from = \Yii::$app->params['email-assistenza'];
                } else {
                    $from = 'assistenza@open20.it';
                }
            }
            $ok = Email::sendMail($from, $tos, $subject, $text, $files, $bcc, [], 0, false, $cc);
        }
        return $ok;
    }

    /**
     * Get icon external link
     * @param $url
     * @return string
     */
    public static function getExternalLinkIcon($url)
    {
        $platformUrl = \Yii::$app->params['platform']['frontendUrl'];
        $iconName = '/img//icon_emails/icon-arrow-right-circle-outline.png';
        $icon = $platformUrl . $iconName;
        $text = \Yii::t('amoscollaborations', 'Scopri l\'iniziativa');

        return Html::a(
            $text . Html::img(
                $icon,
                [
                    'style' => [
                        'height' => '15px',
                        'margin-left' => '4px',
                        'border-top-width' => '0px',
                        'border-right-width' => '0px',
                        'border-bottom-width' => '0px',
                        'border-left-width' => '0px',
                        'border-top-style' => 'solid',
                        'border-right-style' => 'solid',
                        'border-bottom-style' => 'solid',
                        'border-left-style' => 'solid',
                        'vertical-align' => 'text-top'
                    ]
                ]
            ),
            $url,
            [
                'title' => $text,
                'target' => '_blank',
                'style' => [
                    'font-size' => '12px',
                    'color' => 'white',
                    'margin-left' => '8px',
                    'font-weight' => 'normal',
                    'text-transform' => 'none',
                    'float' => 'right',
                    'margin-top' => '4px'
                ]
            ]
        );
    }
}
