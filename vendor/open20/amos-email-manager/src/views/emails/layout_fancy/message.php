<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

?>
<div marginwidth="0" marginheight="0">
    <div style="background-color:white;width:100%;margin:0;padding:30px 0 30px 0">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
            <tr>
                <td align="center" valign="top" style="padding-bottom:0px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center">
                        <tbody>
                        <tr>
                            <td style="margin-bottom: 10px;background-color: #297A38;height:15px;"></td>
                            <td style="margin-bottom: 10px;background-color: #297A38;height:15px;"></td>
                            <td style="margin-bottom: 10px;background-color: #297A38;height:15px;"></td>
                        </tr>
                        <tr>
                            <td style="height:10px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <!--                                TODO fix image-->
                                <!--                                <img width="120" src="http://www.regione.lombardia.it/RLIstituzionaleThemeStatic/themes/Portal8.0/tema/img/logo-rl.png" class="CToWUd">-->
                            </td>
                            <td style="width:20px;"></td>
                            <td align="right">
                                <!--                                <img width="250" src="http://www.gestinform.eu/wp-content/uploads/2016/03/logo-poi.png" class="CToWUd"></td>-->
                        </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" width="600"
                           style="background-color:white;color:#297A38;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle"
                           bgcolor="white">
                        <tbody>
                        <?php if ($heading) { ?>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="600"
                                           style="background-color:white;color:#ffffff;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle"
                                           bgcolor="white">
                                        <tbody>
                                        <tr>
                                            <td style="width:30px;"></td>
                                            <td>
                                                <h1 style="padding-top: 25px; color:green;margin:0;display:block;font-family:Arial;font-size:20px;font-weight:bold;text-align:center;line-height:150%"><?php echo $heading; ?></h1>
                                            </td>
                                            <td style="width:30px;"></td>
                                        </tr>
                                        <tr>
                                            <td id="m_-9137424053610890079down-heading"
                                                style="background-color:lightgrey;height:10px"></td>
                                            <td id="m_-9137424053610890079down-heading"
                                                style="background-color:lightgrey;height:10px"></td>
                                            <td style="background-color:lightgrey;height:10px"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="600">
                                    <tbody>
                                    <tr>
                                        <td valign="top"
                                            style="padding-top: 5px;background-color:white;border-radius:6px!important">
                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <div style="color:black; margin:10px;font-family:Arial;font-size:12px;line-height:150%;text-align:left">
                                                            <?php echo $contents; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td valign="top" style="padding-top: 5px;padding-bottom: 5px;">
                                                        <div style="color:green; margin:10px;font-family:Arial;font-size:12px;line-height:150%;text-align:left">
                                                            <a href="#"
                                                               style="font-weight: bold;font-size:20px;color:green;">Clicca
                                                                qui per accedere alla piattaforma e pubblicarlo</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin:10px;padding:10px;background-color:white" border="0" cellpadding="0"
                           cellspacing="0" width="600" align="center">
                        <tr>
                            <td>
                                <div style="color:black; background-color:lightgrey; padding:10px;margin:5px; font-family:Arial;font-size:12px;line-height:150%;text-align:left">
                                    <strong>Lo staff di open innovation regione lombardia<strong/>
                                        <p style="font-weight: normal">è un testo segnaposto utilizzato nel settore
                                            della tipografia e della stampa. Lorem Ipsum è considerato il testo
                                            segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo
                                            prese una cassetta di caratteri e li assemblò per preparare un testo
                                            campione. È sopravvissuto non solo a più di cinque secoli, ma anche al
                                            passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato.
                                            Fu r</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>