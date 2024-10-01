<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Creative Work
 *
 * The most generic kind of creative work, including books, movies, photographs, software programs, etc.
 *
 *
 * @since 1.0.1
 */
class CreativeWork extends BaseThing implements CreativeWorkInterface
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'CreativeWork';
    }

    use CreativeWorkTrait;
}
