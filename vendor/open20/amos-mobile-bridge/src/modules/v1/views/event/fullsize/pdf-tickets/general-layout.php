<?php $dateBegin = new \DateTime($eventData->begin_date_hour);?>
<?php $dateEnd = new \DateTime($eventData->end_date_hour);?>
<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#e0e0e0" style="border-collapse: collapse;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;table-layout: fixed !important;margin: 0 auto !important;">
    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <td style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border:15px solid #297A38">

            <div style="max-width: 680px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                <!-- Email Body : BEGIN -->
                <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%" style="max-width: 680px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;">

                    <!-- Hero Image, Flush : BEGIN -->
                    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <td class="full-width-image" align="center" style="padding: 30px 0 10px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                            <img src="<?= \Yii::$app->params['logo']?>" width="200" alt="logo premio internazionale" border="0" style="width: 100%;max-width: 200px;height: auto;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;-ms-interpolation-mode: bicubic;">
                        </td>
                    </tr>
                    <!-- Hero Image, Flush : END -->

                    <!-- 1 Column Text : BEGIN -->
                    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <td style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;margin: 0 auto !important;">
                                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <td style="padding: 0 0 30px;text-align: center;font-family: sans-serif;font-size: 18px;mso-height-rule: exactly;line-height: 24px;color: #000000;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                       <?= $eventData->getFullLocationString() ?> | <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $dateBegin->format('d M Y') ?></strong> |
                                        ore <?=$dateBegin->format('H:m') ?>  - <?= $dateEnd->format('H:m') ?>
                                    </td>
                                </tr>
                                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <td style="padding: 0 0 20px;text-align: center;font-family: sans-serif;font-size: 15px;mso-height-rule: exactly;line-height: 14px;color: #000000;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                        <?php if(!empty($participantData['accreditationModel'])) : ?>
                                            <b>Lista accreditamento</b> <?= $participantData['accreditationModel']->title; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- 1 Column Text : BEGIN -->

                    <!-- Two Even Columns : BEGIN -->
                    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <td bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#CCCCCC; font-size: 14px;text-align: left;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;table-layout: fixed !important;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;margin: 0 auto !important;">
                                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                        <td style="padding-left:60px;width:48%;font-family: sans-serif;font-size: 15px;mso-height-rule: exactly;line-height: 20px;color: #000000;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                            <?php $invitation = $participantData['companion_of'];
                                                if($invitation->is_group){
                                                    $nome = 'Gruppo'?>
                                                <?php  }else {
                                                    $nome = "Nome e Cognome";
                                                } ?>
                                            <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $nome ?></strong>
                                            <br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $participantData['nome'] ?> <?= $participantData['cognome'] ?><br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <hr style="width:80%;border-color: #FFFFFF;text-align:left;">
                                            <?php if(!empty($participantData['codice_fiscale'])){ ?>
                                                <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">Cognome</strong>
                                                <br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $participantData['codice_fiscale'] ?><br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                <hr style="width:80%;border-color: #FFFFFF;text-align:left;">
                                            <?php } ?>
                                            <?php if (!empty($seatModel)) { ?>
                                            <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">Settore</strong>
                                            <br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $seatModel->sector ?><br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <hr style="width:80%;border-color: #FFFFFF;text-align:left;">
                                            <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">Palco/Fila</strong>
                                            <br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $seatModel->row ?><br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <hr style="width:80%;border-color: #FFFFFF;text-align:left;">
                                            <strong style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">Posto</strong>
                                            <br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"><?= $seatModel->seat ?><br style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <?php } ?>
                                        </td>

                                        <?php if (!empty($qrcode)) : ?>
                                            <td style="background:#FFFFFF; font-family: sans-serif;font-size: 15px;mso-height-rule: exactly;line-height: 20px;color: #000000;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                                <?= $qrcode; ?>
                                            </td>
                                        <?php endif; ?>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Two Even Columns : END -->


                    <!-- Background Image with Text : BEGIN -->
                    <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                        <td background="" bgcolor="#FFFFFF" style="width:100%;text-align: center;background-position: center center !important;background-size: cover !important;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                            <?php
                            $urlLogo = '/img/img_default.jpg';
                            if(!empty($eventData->eventLogo)){
                                $urlLogo = $eventData->eventLogo->getWebUrl();
                            }?>
                            <img src="<?= $eventData->eventLogo->getWebUrl()?>" width="100%" alt="giornata della ricerca" style="border: 0;height: auto;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;-ms-interpolation-mode: bicubic;" class="center-on-narrow">
                        </td>
                    </tr>
                    <?php if(!empty($participantData['note'])){ ?>
                        <tr><b>Note: </b> <?php echo  $participantData['note'] ?><br />
                        </tr>
                    <?php  }?>
                    <!-- Background Image with Text : END -->

                </table>
                <!-- Email Body : END -->


            </div>
        </td>
    </tr>

</table>