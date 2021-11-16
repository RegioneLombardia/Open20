<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

?>

<div class="last-comment-discussion-wrapper">
        <small><strong>Ultimo contributo</strong></small>

        <div class="comment-wrapper lightgrey-bg-c2 p-2 mt-2">
          <?php if (isset(($lastCommentDiscussion))) : ?>
            <div class="comment-author">
              <?=
                $this->render(
                  '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                  [
                    'nameSurname' => $nameSurnameLastComment,
                    'imageAvatar' => $imageAvatar,
                    'url' => $urlAvatar,
                    'additionalInfo' => '07/07/2020 alle 10:28',
                    'removeLink' => $removeLink,
                    'avatarWrapperSize' => $avatarWrapperSize,
                    'extraTextSize' => $extraTextSize

                  ]
                );
              ?>
            </div>
            <div class="last-comment small my-2 pl-4">
              <p class="ml-3 mb-0 title-four-line"><?= $lastCommentDiscussion ?></p>
            </div>
          <?php else : ?>
            <div class="last-comment small my-2">
              <p class="mb-0 text-muted">Puoi essere il primo a lasciare un contributo.</p>
            </div>
          <?php endif ?>
        </div>
        <div class="footer-last-comment lightgrey-bg-c2 d-flex justify-content-end mt-1 p-2">
          <a href="#" class="btn btn-primary btn-xs">Contribuisci</a>
        </div>
      </div>