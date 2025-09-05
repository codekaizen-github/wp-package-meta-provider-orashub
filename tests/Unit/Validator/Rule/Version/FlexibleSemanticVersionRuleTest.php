<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\Version;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version\FlexibleSemanticVersionRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

class FlexibleSemanticVersionRuleTest extends TestCase
{
    public function testMajorVersionOnly(): void
    {
        $this->assertTrue(Validator::create(new FlexibleSemanticVersionRule())->isValid('6'));
    }
    public function testMajorMinorVersionOnly(): void
    {
        $this->assertTrue(Validator::create(new FlexibleSemanticVersionRule())->isValid('6.3'));
    }
    public function testFullSemverVersion(): void
    {
        $this->assertTrue(Validator::create(new FlexibleSemanticVersionRule())->isValid('6.3.4'));
    }
    public function testNumericWord(): void
    {
        $this->assertFalse(Validator::create(new FlexibleSemanticVersionRule())->isValid('five'));
    }
    public function testAdditionalSpacesWord(): void
    {
        $this->assertFalse(Validator::create(new FlexibleSemanticVersionRule())->isValid('6. 5'));
    }
}
