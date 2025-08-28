<?php

namespace CodeKaizen\WPPackageMetaProviderLocal\Client\ORASHub\Validation\Rule;

use Respect\Validation\Validator;
use Respect\Validation\Rules;
use Respect\Validation\Rules\Core\Simple;

class ORASHubPackageMetaRulePlugin extends Simple
{
    public function isValid(mixed $input): bool
    {
        if (!is_object($input)) {
            return false;
        }

        return Validator::create(new Rules\Attribute('shortSlug', new Rules\StringType(), true))
            ->create(new Rules\Attribute('fullSlug', new Rules\StringType(), true))
            ->create(new Rules\Attribute('version', new Rules\Version(), true))
            ->create(new Rules\Attribute('downloadURL', new Rules\Url(), true))
            ->create(new Rules\Attribute('name', new Rules\StringType(), false))
            ->create(new Rules\Attribute('viewURL', new Rules\Url(), false))
            ->create(new Rules\Attribute('tested', new Rules\Version(), false))
            ->create(new Rules\Attribute('stable', new Rules\Version(), false))
            ->create(new Rules\Attribute(
                'tags',
                new Rules\AllOf(
                    new Rules\ArrayType(),
                    new Rules\Each(new Rules\StringType)
                ),
                false
            ))
            ->create(new Rules\Attribute('author', new Rules\StringType(), false))
            ->create(new Rules\Attribute('authorURL', new Rules\Url(), false))
            ->create(new Rules\Attribute('license', new Rules\StringType(), false))
            ->create(new Rules\Attribute('licenseURL', new Rules\Url(), false))
            ->create(new Rules\Attribute('shortDescription', new Rules\StringType(), false))
            ->create(new Rules\Attribute('description', new Rules\StringType(), false))
            ->create(new Rules\Attribute('requiresWordPressVersion', new Rules\Version(), false))
            ->create(new Rules\Attribute('requiresPHPVersion', new Rules\Version(), false))
            ->create(new Rules\Attribute('textDomain', new Rules\StringType(), false))
            ->create(new Rules\Attribute('domainPath', new Rules\StringType(), false))
            ->create(new Rules\Attribute(
                'requiresPlugins',
                new Rules\AllOf(
                    new Rules\ArrayType(),
                    new Rules\Each(new Rules\StringType)
                ),
                false
            ))
            ->create(new Rules\Attribute('pluginFile', new Rules\StringType(), false))
            ->create(new Rules\Attribute('sections', new ObjectWithStringValues(), false))
            ->create(new Rules\Attribute('network', new Rules\BoolVal(), true))
            ->isValid($input);
    }
}
