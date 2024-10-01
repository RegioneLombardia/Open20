<?php

namespace luya\web\jsonld;

/**
 * JsonLd - Organization
 *
 * An organization such as a school, NGO, corporation, club, etc.
 *
 * @since 1.0.0
 */
class Organization extends BaseThing implements OrganizationInterface
{
    /**
     * @inheritdoc
     */
    public function typeDefintion()
    {
        return 'Organization';
    }
    
    use OrganizationTrait;
}
