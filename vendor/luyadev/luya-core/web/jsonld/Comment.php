<?php

namespace luya\web\jsonld;

/**
 * JsonLd Comment.
 *
 * @since 1.0.3
 */
class Comment extends BaseThing implements CommentInterface
{
    use CommentTrait;
    
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Comment';
    }
}
