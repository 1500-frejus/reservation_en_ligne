<?php

namespace App\Scopes;

trait HasOrganizationScope
{
    public static function bootHasOrganizationScope()
    {
        static::addGlobalScope(new OrganizationScope());
    }
}
