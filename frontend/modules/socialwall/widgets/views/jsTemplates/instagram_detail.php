<?php

use frontend\modules\socialwall\widgets\graphics\SocialWallItem;
use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;

// $xlinkBase = "";
// if(array_key_exists(BootstrapItaliaDesignAsset::className(), $this->assetBundles)) {
//     $bootstrapItaliaAsset = $this->assetBundles[BootstrapItaliaDesignAsset::className()];
//     $xlinkBase = $bootstrapItaliaAsset->baseUrl;
// }

//if(!array_key_exists(BootstrapItaliaDesignAsset::className(), $this->assetBundles)) {
//    $bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
//    $xlinkLogo = $bootstrapItaliaAsset->baseUrl  . "/sprite/material-sprite.svg#instagram";
//}
 
//\yii\helpers\VarDumper::dump(array_key_exists(BootstrapItaliaDesignAsset::className(), $this->assetBundles),4,true);
//\yii\helpers\VarDumper::dump($this,4,true);die;
?>

<div class="col-12 instagram-wall" id="${blockId}">
    <div class="it-grid-item-wrapper p-3">
        <div class="">
            <div class="select-social-post content-instagram text-right p-3">
                <div class="form-check">
                    <input type="checkbox" id="${blockId}-selected" name="${fieldName}[instagram][${id}][selected]" class="form-check-input instagram-content-select" data-post-id="${blockId}" ${checked ? "checked=\"checked\"" : ""}>
                    <label class="form-check-label" for="${blockId}-selected"></label>
                </div>
                <textarea class="d-none" name="${fieldName}[instagram][${id}][data]">
                    ${jsonContent}
                </textarea>
            </div>
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <div class="img-wrapper">
                        ${media_type === "VIDEO" ? "<video width=\"100%\" controls loop><source src=\""+media_url+"\" type=\"video/mp4\"/></video>" : "<img src=\""+media_url+"\">"}
                    </div>
                </div>
            </div>

            <div class="avatar-wrapper avatar-extra-text px-4 pt-3">
                    <div class="avatar size-lg d-none">
                        <img src="${(userinfo !== undefined && userinfo !== null && userinfo['profile_image_url'] !== undefined && userinfo['profile_image_url'] !== null) ? userinfo['profile_image_url'] : ''}" />
                    </div>
                    <div class="extra-text m-0">
                        <a href="https://www.instagram.com/${username}/" target="_blank">${username}</a>
                        <p class="public_date">${posting_date_formatted}</p>
                    </div>
            </div>

            <div class="insta-description truncate pb-1 px-4">
                <p class="m-0">${caption}</p>
            </div>

            <div class="text-right px-2 pb-2"></div>

        </div>
    </div>
</div>
