<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\notificationmanager\AmosNotify;

/**
 * @var string $title
 */
if($section_title or $section_description) {
    $notifyModule = AmosNotify::instance();
?>

<!-- TITOLO SEZIONE : BEGIN -->
<tr>
    <td style="padding-top:15px;" width="100%">
    <table cellspacing="0" cellpadding="0" border="0" align="center" class="email-container" width="100%" style="width:100%">
        <?php if ($section_title) { ?>
            <tr>
	            <td bgcolor="<?= $notifyModule->mailThemeColor['bgPrimary'] ?>" style="font-family:sans-serif; color:#FFF; font-weight:bold; font-size:18px; padding:5px 10px; text-transform: uppercase; width:520px"><p style="margin:8px 0;"><?= ucfirst($section_title) ?></p></td>            </tr>
        <?php } // $section_title ?>
        <?php if ($section_description) { ?>
            <tr>
                <td style="font-family:sans-serif; font-size:14px; padding:5px 10px; width:520px"><p style="margin:8px 0;"><?= $section_description ?></p></td>
            </tr>
        <?php } // $section_description ?>
    </table>               
    </td>  
</tr>
<!-- TITOLO SEZIONE : END -->
<?php } // if
