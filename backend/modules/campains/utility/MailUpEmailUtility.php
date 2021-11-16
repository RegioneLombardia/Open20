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

use backend\modules\campains\models\base\PreferenceCampainChannelMm as BasePreferenceCampainChannelMm;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\PreferenceMailupTemplate;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\Module;
use open20\amos\core\helpers\StringHelper;
use Exception;
use Yii;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Class MailUpEmailUtility
 * @package open20\amos\events\utility
 */
class MailUpEmailUtility
{
    /**
     * @return string
     */
    public static function getEmailHeader()
    {
        return '
        <head>
  <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta content="width=device-width" name="viewport" />
  <!--[if !mso]><!-->
  <meta content="IE=edge" http-equiv="X-UA-Compatible" />
  <!--<![endif]-->
  <title></title>
  <!--[if !mso]><!-->
  <!--<![endif]-->
  <style type="text/css">
    body {
      margin: 0;
      padding: 0;
    }

    table,
    td,
    tr {
      vertical-align: top;
      border-collapse: collapse;
    }

    * {
      line-height: inherit;
    }

    a[x-apple-data-detectors=true] {
      color: inherit !important;
      text-decoration: none !important;
    }
  </style>
  <style id="media-query" type="text/css">
    @media (max-width: 620px) {

      .block-grid,
      .col {
        min-width: 320px !important;
        max-width: 100% !important;
        display: block !important;
      }

      .block-grid {
        width: 100% !important;
      }

      .col {
        width: 100% !important;
      }

      .col_cont {
        margin: 0 auto;
      }

      img.fullwidth,
      img.fullwidthOnMobile {
        max-width: 100% !important;
      }

      .no-stack .col {
        min-width: 0 !important;
        display: table-cell !important;
      }

      .no-stack.two-up .col {
        width: 50% !important;
      }

      .no-stack .col.num2 {
        width: 16.6% !important;
      }

      .no-stack .col.num3 {
        width: 25% !important;
      }

      .no-stack .col.num4 {
        width: 33% !important;
      }

      .no-stack .col.num5 {
        width: 41.6% !important;
      }

      .no-stack .col.num6 {
        width: 50% !important;
      }

      .no-stack .col.num7 {
        width: 58.3% !important;
      }

      .no-stack .col.num8 {
        width: 66.6% !important;
      }

      .no-stack .col.num9 {
        width: 75% !important;
      }

      .no-stack .col.num10 {
        width: 83.3% !important;
      }

      .video-block {
        max-width: none !important;
      }

      .mobile_hide {
        min-height: 0px;
        max-height: 0px;
        max-width: 0px;
        display: none;
        overflow: hidden;
        font-size: 0px;
      }

      .desktop_hide {
        display: block !important;
        max-height: none !important;
      }
    }
  </style>
  <style id="icon-media-query" type="text/css">
    @media (max-width: 620px) {
      .icons-inner {
        text-align: center;
      }

      .icons-inner td {
        margin: 0 auto;
      }
    }
  </style>
</head>
        
        ';
    }


    /**
     * Undocumented function
     *
     * @param [type] $emailList
     * @param [type] $tagName
     * @return void
     */
    public static function filterEmailListForTag($emailList, $tagName)
    {
        $toret = [];
        $moduleNewsletter = \Yii::$app->getModule('newsletter');
        $mailServiceClassname = $moduleNewsletter->mail_service_driver;
        $mailService = new $mailServiceClassname();

        foreach ($emailList as $valMail) {
            $mail = $mailService->getEmail($valMail->idList, $valMail->idMessage);
            // VarDumper::dump($mail, 5,true);
            if ( self::emailHasTagname($mail->Tags, $tagName) ) {
                $toret[] = $valMail;
            }
        }
        return $toret;
    }

    /**
     * Undocumented function
     *
     * @param [type] $email
     * @param [type] $tagname
     * @return void
     */
    private static function emailHasTagname($tags, $tagname)
    {
        foreach ($tags as $tag) {
            if ($tag->Name == $tagname) {
                return true;
            }
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @param Object $emailTemplate
     * @param BasePreferenceCampainChannelMm $channel
     * @param string $newContent
     * @return string
     */
    public static function getDynamicContentMailup($emailTemplate, $newContent, $channel)
    {
        $htmlWithParams = '';

        if (!is_null($emailTemplate)) {

            $htmlWithParams = Module::t('campain', $emailTemplate->Content, [
                'contenuto' => $newContent,
                'title' => $channel->title,
                'subtitle' => $channel->subtitle,
                'date' => $channel->date_newsletter,
            ]);

            //$htmlWithParams = str_replace('{contenuto}', $newContent, $emailTemplate->Content);
        }

        return self::getEmailHeader() . $htmlWithParams;
    }


    /**
     *
     * @param PreferenceCampainChannelMm $newsletterChannel
     * @return void
     * @throws Exception
     */
    public static function generateNewsletterContent($newsletterChannel)
    {
        $myTemplate = PreferenceMailupTemplate::findOne(['mailup_template_id' => $newsletterChannel->template_id]);
        $backendUrl = Yii::$app->params['platform']['backendUrl'];
        $colorChoiches = json_decode($myTemplate->colors_choice, true);

        if (empty($myTemplate)) {
            throw new Exception('Internal template not found!');
        }

        $content = '';

        /** @var PreferenceCampainSection $section */
        foreach ($newsletterChannel->getPreferenceCampainSections()->orderBy(['order' => SORT_ASC])->all() as $section) {
            $textColor = isset($colorChoiches[$section->color]['txtcolor'])? $colorChoiches[$section->color]['txtcolor']: '#FF0000';
            $bgcolor = isset($colorChoiches[$section->color]['bgcolor'])? $colorChoiches[$section->color]['bgcolor']: '#FF000000';
            $sectionIcon = isset($colorChoiches[$section->color]['section_icon'])? $colorChoiches[$section->color]['section_icon']: '';
            // VarDumper::dump( $section->title, $depth = 10, $highlight = true);

            $sectionTransparent = false;
            // per essere renderizzata la sezione deve avere un colore non trasparente (#ffffff00)... e deve avere un titolo... 
            if (isset($bgcolor) 
                && (strlen($bgcolor) == 9) 
                && (StringHelper::endsWith($bgcolor, '00'))
                && empty($section->title)
            ) {
                $sectionTransparent = true;
            }

            if (!$sectionTransparent) {
                $content .= Module::t('campain', $myTemplate->fsSection, [
                    'bgcolor' => $bgcolor, 
                    'txtcolor' => $textColor,
                    'title' => $section->title,
                    'section_icon' =>  $sectionIcon,
                ]);
            }

            /** @var PreferenceCampainContainer $container */
            foreach ($section->getPreferenceCampainContainers()->orderBy(['order' => SORT_ASC])->all() as $container) {
                $socialSharingFlag = $container->social_sharing;
                //var_dump($socialSharingFlag? 'true': 'false');
                // getPreferenceCampainContainerType
                switch($container->preference_campain_container_type_id) {
                    // contenuto
                    case 1:
                    case 2:

                        $containerLink = (is_null($container->getContentLink()))? '': $container->getContentLink();
                        if (empty($container->contentImage)) {
                                $content .= Module::t('campain', $myTemplate->fsContentNoImage, [
                                    'title' => $container->content_title, 
                                    'description' => $container->content_text,
                                    'text_link' =>  empty($containerLink)? '': $myTemplate->default_link_text,
                                    'link' => $containerLink,
                                    // 'link_facebook' => self::getContentWithLink($container->content_facebook_link,  $myTemplate->fsFacebookButton),
                                    // 'link_twitter' => self::getContentWithLink($container->content_twitter_link,  $myTemplate->fsTwitterButton),
                                    // 'link_linkedin' => self::getContentWithLink($container->content_linkedin_link,  $myTemplate->fsLinkedinButton),
                                    'bgcolor' => $bgcolor,
                                    'txtcolor' => $textColor,
                                    'td_arrow_link' => self::getContentWithLink($containerLink, $myTemplate->fsLinkButton, $bgcolor, $textColor),
                                    'social_sharing' => ($socialSharingFlag && !empty($containerLink))? Module::t('campain', $myTemplate->socialSharing, ['link' => $containerLink] ): '', 
                                    ]);

                        } else {
                            // obbligatorio deve essere l'orientamento del contenuto
                            switch ($container->content_align) {
                                case 'right':
                                        $content .= Module::t('campain', $myTemplate->fsContentRightImage, [
                                            'title' => $container->content_title, 
                                            'description' => $container->content_text,
                                            'text_link' =>  empty($containerLink)? '': $myTemplate->default_link_text,
                                            'link' => $containerLink,
                                            // 'link_facebook' => self::getContentWithLink($container->content_facebook_link,  $myTemplate->fsFacebookButton),
                                            // 'link_twitter' => self::getContentWithLink($container->content_twitter_link,  $myTemplate->fsTwitterButton),
                                            // 'link_linkedin' => self::getContentWithLink($container->content_linkedin_link,  $myTemplate->fsLinkedinButton),
                                            'link_image' => $backendUrl . $container->contentImage->getWebUrl(),
                                            'bgcolor' => $bgcolor,
                                            'txtcolor' => $textColor,
                                            'td_arrow_link' => self::getContentWithLink($containerLink, $myTemplate->fsLinkButton, $bgcolor, $textColor),
                                            'social_sharing' => ($socialSharingFlag && !empty($containerLink))? Module::t('campain', $myTemplate->socialSharing, ['link' => $containerLink] ): '', 
                                            ]);
                                    break;

                                case 'center':
                                        $content .= Module::t('campain', $myTemplate->fsContentCenterImage, [
                                            'title' => $container->content_title, 
                                            'description' => $container->content_text,
                                            'text_link' =>  empty($containerLink)? '': $myTemplate->default_link_text,
                                            'link' => $containerLink,
                                            // 'link_facebook' => self::getContentWithLink($container->content_facebook_link,  $myTemplate->fsFacebookButton),
                                            // 'link_twitter' => self::getContentWithLink($container->content_twitter_link,  $myTemplate->fsTwitterButton),
                                            // 'link_linkedin' => self::getContentWithLink($container->content_linkedin_link,  $myTemplate->fsLinkedinButton),
                                            'link_image' => $backendUrl . $container->contentImage->getWebUrl(),
                                            'bgcolor' => $bgcolor,
                                            'txtcolor' => $textColor,
                                            'td_arrow_link' => self::getContentWithLink($containerLink, $myTemplate->fsLinkButton, $bgcolor, $textColor),
                                            'social_sharing' => ($socialSharingFlag && !empty($containerLink))? Module::t('campain', $myTemplate->socialSharing, ['link' => $containerLink] ): '', 
                                            ]);
                                    break;

                                case 'left':
                                default:
                                        $content .= Module::t('campain', $myTemplate->fsContentLeftImage, [
                                            'title' => $container->content_title, 
                                            'description' => $container->content_text,
                                            'text_link' =>  empty($containerLink)? '': $myTemplate->default_link_text,
                                            'link' => $containerLink,
                                            // 'link_facebook' => self::getContentWithLink($container->content_facebook_link,  $myTemplate->fsFacebookButton),
                                            // 'link_twitter' => self::getContentWithLink($container->content_twitter_link,  $myTemplate->fsTwitterButton),
                                            // 'link_linkedin' => self::getContentWithLink($container->content_linkedin_link,  $myTemplate->fsLinkedinButton),
                                            'link_image' => $backendUrl . $container->contentImage->getWebUrl(),
                                            'bgcolor' => $bgcolor,
                                            'txtcolor' => $textColor,
                                            'td_arrow_link' => self::getContentWithLink($containerLink, $myTemplate->fsLinkButton, $bgcolor, $textColor),
                                            'social_sharing' => ($socialSharingFlag && !empty($containerLink))? Module::t('campain', $myTemplate->socialSharing, ['link' => $containerLink] ): '', 
                                            ]);
                                    break;
                            }
                        }

                        break;
                    // separatore
                    case 3:

                        $content .= Module::t('campain', $myTemplate->fsSeparator, [
                            'bgcolor' => $bgcolor, 
                            'txtcolor' => $textColor,
                            'title' => $container->separator_title,
                        ]);
                        
                        break;
                    // contenuto esterno
                    // case 2:

                    //     $content .= Module::t('campain', $myTemplate->content_left_image, [
                    //         'title' => $container->content_title, 
                    //         'description' => $container->content_text,
                    //         'text_link' =>  $myTemplate->default_link_text,
                    //         'link' => $container->content_news_link,
                    //         'link_facebook' => (!empty($container->content_facebook_link))? Module::t('campain', self::getTdFacebook(), ['link' =>  $container->content_facebook_link]): '',
                    //         'link_twitter' => (!empty($container->content_twitter_link))?Module::t('campain', self::getTdTwitter(), ['link' => $container->content_twitter_link]): '',
                    //         'link_linkedin' => (!empty($container->content_linkedin_link))?Module::t('campain', self::getTdLinkedin(), ['link' => $container->content_linkedin_link]): '',
                    //         'link_image' => (!empty($container->contentImage))? Url::toRoute($container->contentImage->getWebUrl(),true): '',
                    //     ]);
                        
                    //     break;

                }
            }

            // VarDumper::dump( $content, $depth = 10, $highlight = true);
        }

        return $content;

    }

    private static function getContentWithLink($link, $content, $bgcolor = null, $txtcolor = null)
    {
        $params = ['link' => $link];

        if(!is_null($bgcolor)) {
            $params['bgcolor'] = $bgcolor;
        }

        if(!is_null($txtcolor)) {
            $params['txtcolor'] = $txtcolor;
        }

        return (!empty($link))? Module::t('campain', $content, $params): '';
    }

    private static function getTdLinkArrow() 
    {
        return '<td style="word-break: break-word; vertical-align: top; text-align: center; padding-top: 4px; padding-bottom: 4px; padding-left: 0px; padding-right: 4px;" align="center" valign="top"><a href="http://[track]/{link}" target="blank"><div class="arrow" style="background: {txtcolor}; height:30px; width:30px; border-radius:50%;">
        <img src="http://a4f6d9.emailsp.com/assets/1/ic_arrow_forward-26.png" style="margin-top: 3px; width: 24px; height: 24px;">
        </div></a></td>';
    }

    private static function getTdFacebook() 
    {
        return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">
            <a href="http://[track]/{link}" target="_blank"><img width="32" height="32" src="http://a4f6d9.emailsp.com/images/social/circle-color/facebook@2x.png" alt="Facebook" title="Facebook" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;">
            </a></td>';
        // return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
        //     <a href="http://[track]/{link}" target="_blank">
        //     <img src="http://a4f6d9.emailsp.com/assets/1/fb.png" alt="" style="width: auto; height: 25px;">
        //     </a>
        // </td>';
    }

    private static function getTdFacebookBlack() 
    {
        return '
        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">								  
		<a href="http://[track]/{link}" target="_blank"><img width="24" height="24" src="http://a4f6d9.emailsp.com/images/social/t-circle-dark-gray/facebook@2x.png" alt="Facebook" title="Facebook" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
        ';
    }

    private static function getTdTwitter() 
    {
        return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">
            <a href="http://[track]/{link}" target="_blank"><img width="32" height="32" src="http://a4f6d9.emailsp.com/images/social/circle-color/twitter@2x.png" alt="Twitter" title="Twitter" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;">
            </a></td>
        ';
        // return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
        // <a href="http://[track]/{link}" target="_blank">
        // <img src="http://a4f6d9.emailsp.com/assets/1/twitter.png" style="height: 25px;" alt="">
        // </a>
        // </td>';
    }

    private static function getTdTwitterBlack() 
    {
        return '
        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">
        <a href="http://[track]/{link}" target="_blank"><img width="24" height="24" src="http://a4f6d9.emailsp.com/images/social/t-circle-dark-gray/twitter@2x.png" alt="Twitter" title="Twitter" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
        ';
    }

    private static function getTdLinkedin() 
    {
        return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">
            <a href="http://[track]/https://www.linkedin.com/" target="_blank"><img width="32" height="32" src="http://a4f6d9.emailsp.com/images/social/circle-color/linkedin@2x.png" alt="LinkedIn" title="LinkedIn" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;">
            </a></td>';
        // return '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
        //     <a href="http://[track]/{link}" target="_blank">
        //         <img src="http://a4f6d9.emailsp.com/assets/1/linkedin.png" style="height: 25px;" alt="icona linkedin"> 
        //     </a>
        // </td>';
    }

    private static function getTdLinkedinBlack() 
    {
        return '
        <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top">
        <a href="http://[track]/{link}" target="_blank"><img width="24" height="24" src="http://a4f6d9.emailsp.com/images/social/t-circle-dark-gray/linkedin@2x.png" alt="LinkedIn" title="LinkedIn" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;"></a></td>
        ';
    }

    public static function getNumberOfSendingRecipients($channelMm)
    {
        $moduleNewsletter = \Yii::$app->getModule('newsletter');
        $mailServiceClassname = $moduleNewsletter->mail_service_driver;
        $mailService = new $mailServiceClassname();
        $history = $mailService->getEmailSendHistory($channelMm->mailup_list_id, $channelMm->mailup_message_id);

        if (!empty($history)) {
            foreach ($history->Items as $email) {
                if (isset($email->Recipients)) {
                    return $email->Recipients;
                }
            }
        }

        return 0;
    }

    /**
     
     $myTemplate = PreferenceMailupTemplate::findOne(['mailup_template_id' => 9]);

        $newContent = Module::t('campain', $myTemplate->section, [
            'bgcolor' => '#297a38', 
            'txtcolor' => '#ffffff',
            'title' => 'Ecco un testo di esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->separator, [
            'bgcolor' => '#297a38', 
            'title' => 'Ecco un testo di esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->content_center_image, [
            'title' => 'Il mio titolo', 
            'description' => '<p>Un esempio di testo lungo, per fare una descrizione</p><p>due righe</p>',
            'text_link' => 'LEGGI...',
            'link' => 'https://google.it',
            'link_facebook' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/fb.png" alt="" style="width: auto; height: 25px;"></a></td>',
            'link_twitter' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/twitter.png" style="height: 25px;" alt=""></a>
            </td>',
            'link_linkedin' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
            <a href="http://[track]/'. 'wwww.google.it' .'" target="_blank">
                <img src="http://a4f6d9.emailsp.com/assets/1/linkedin.png" style="height: 25px;" alt="icona linkedin"> 
            </a>
        </td>',
            'link_image' => 'https://dummyimage.com/410x226/d9d9d9/000cb8.jpg&text=esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->separator, [
            'bgcolor' => '#297a3800', 
            'title' => 'Ecco un testo di esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->section, [
            'bgcolor' => '#297a38', 
            'txtcolor' => '#ffffff',
            'title' => 'Altra sezione'
        ]);

        $newContent .= Module::t('campain', $myTemplate->content_right_image, [
            'title' => 'Il mio titolo', 
            'description' => '<p>Un esempio di testo lungo, per fare una descrizione</p><p>due righe</p>',
            'text_link' => 'Vuoi saperne di più...',
            'link' => 'https://google.it',
            'link_facebook' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/fb.png" alt="" style="width: auto; height: 25px;"></a></td>',
            'link_twitter' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/twitter.png" style="height: 25px;" alt=""></a>
            </td>',
            'link_linkedin' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
                <a href="http://[track]/'. 'wwww.google.it' .'" target="_blank">
                    <img src="http://a4f6d9.emailsp.com/assets/1/linkedin.png" style="height: 25px;" alt="icona linkedin"> 
                </a>
            </td>',
            'link_image' => 'https://dummyimage.com/410x226/d9d9d9/000cb8.jpg&text=esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->separator, [
            'bgcolor' => '#297a38', 
            'title' => 'Ecco un testo di esempio'
        ]);

        $newContent .= Module::t('campain', $myTemplate->content_left_image, [
            'title' => 'Il mio titolo', 
            'description' => '<p>Un esempio di testo lungo, per fare una descrizione</p><p>due righe</p>',
            'text_link' => 'Vuoi saperne di più...',
            'link' => 'https://google.it',
            'link_facebook' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/fb.png" alt="" style="width: auto; height: 25px;"></a></td>',
            'link_twitter' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top"><a href="http://[track]/'. 'www.google.it'.'" target="_blank">
            <img src="http://a4f6d9.emailsp.com/assets/1/twitter.png" style="height: 25px;" alt=""></a>
            </td>',
            'link_linkedin' => '<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 5px; padding-left: 5px;" valign="top">
                <a href="http://[track]/'. 'wwww.google.it' .'" target="_blank">
                    <img src="http://a4f6d9.emailsp.com/assets/1/linkedin.png" style="height: 25px;" alt="icona linkedin"> 
                </a>
            </td>',
            'link_image' => 'https://dummyimage.com/410x226/d9d9d9/000cb8.jpg&text=esempio'
        ]);

     */




}