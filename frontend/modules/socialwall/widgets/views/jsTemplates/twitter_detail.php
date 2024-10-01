<?php

use open20\amos\core\module\BaseAmosModule;
use open20\design\assets\BootstrapItaliaDesignAsset;

?>

    <div class="col-12 twitter-wall" id="${blockId}">
        <div class="it-grid-item-wrapper p-2">
            <div class="p-3">
                <div class="select-social-post pos-rel pb-2 content-twitter">
                    <div class="form-check">
                        <input type="checkbox" id="${blockId}-selected" name="${fieldName}[twitter][${id}][selected]" class="form-check-input twitter-content-select" data-post-id="${blockId}" ${checked ? "checked=\"checked\"" : ""}>
                        <label class="form-check-label" for="${blockId}-selected"></label>
                    </div>
                    <textarea class="d-none" name="${fieldName}[twitter][${id}][data]">
                        ${jsonContent}
                    </textarea>
                </div>

                <div class="avatar-wrapper avatar-extra-text mb-1">
                    <div class="avatar size-lg">
                        <a href="https://twitter.com/${userinfo['username']}" target="_blank">
                        <img src="${userinfo['profile_image_url']}" />
                        </a>
                    </div>
                    <div class="extra-text">
                        <a href="https://twitter.com/${userinfo['username']}" target="_blank"><?= BaseAmosModule::t('app', "" )?> ${userinfo["name"]}</a>
                        <p class="public_date"><?= BaseAmosModule::t('app', "" )?> ${posting_date_formatted}</p>
                    </div>
                </div>

                <div class="twit_description pb-4">
                    <p class="">${text}</p>
                </div>
            </div>
        </div>
    </div> 

