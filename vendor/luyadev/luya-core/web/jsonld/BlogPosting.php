<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Blog Posting
 *
 * A blog post.
 *
 *
 * @since 1.0.1
 */
class BlogPosting extends BaseThing implements BlogPostingInterface
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'BlogPosting';
    }

    use BlogPostingTrait;
}
