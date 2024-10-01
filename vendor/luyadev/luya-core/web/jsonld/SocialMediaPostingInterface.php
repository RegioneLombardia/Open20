<?php
namespace luya\web\jsonld;

/**
 * JsonLd - Social Media Posting Interface
 *
 *
 * @since 1.0.1
 */
interface SocialMediaPostingInterface extends ArticleInterface
{
    /**
     * @return CreativeWork
     */
    public function getSharedContent();

    /**
     * @param CreativeWork $sharedContent
     * @return static
     */
    public function setSharedContent(CreativeWork $sharedContent);
}
