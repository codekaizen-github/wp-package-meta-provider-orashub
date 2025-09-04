<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta\PluginHeadersArrayRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

class PluginHeadersArrayRuleTest extends TestCase
{
    public function testValidCaseKitchenSink(): void
    {
        $input = [
            'Name' => 'Test Plugin',
            'PluginURI' => 'https://codekaizen.net',
            'Version' => '3.0.1',
            'Description' => 'This is a test plugin',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'TextDomain' => 'test-plugin',
            'DomainPath' => '/languages',
            'Network' => 'true',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2.1',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
            'RequiresPlugins' => 'akismet,hello-dolly'
        ];
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testValidCaseKitchenSinkWithNonFullSemverRequiresPHP(): void
    {
        $input = [
            'Name' => 'Test Plugin',
            'PluginURI' => 'https://codekaizen.net',
            'Version' => '3.0.1',
            'Description' => 'This is a test plugin',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'TextDomain' => 'test-plugin',
            'DomainPath' => '/languages',
            'Network' => 'true',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
            'RequiresPlugins' => 'akismet,hello-dolly'
        ];
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testValidCaseNameOnly(): void
    {
        $input = [
            'Name' => 'Test Plugin'
        ];
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testInvalidCasePluginURIIsNotAURI(): void
    {
        $input = [
            'Name' => 'Test Plugin',
            'PluginURI' => 'not_a_uri',
            'Version' => '3.0.1',
            'Description' => 'This is a test plugin',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'TextDomain' => 'test-plugin',
            'DomainPath' => '/languages',
            'Network' => 'true',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
            'RequiresPlugins' => 'akismet,hello-dolly'
        ];
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
    public function testInvalidCaseVersionIsNotAVersion(): void
    {
        $input = [
            'Name' => 'Test Plugin',
            'PluginURI' => 'https://codekaizen.net',
            'Version' => 'one',
            'Description' => 'This is a test plugin',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'TextDomain' => 'test-plugin',
            'DomainPath' => '/languages',
            'Network' => 'true',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
            'RequiresPlugins' => 'akismet,hello-dolly'
        ];
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
    public function testInvalidCaseNameIsNull(): void
    {
        $input = [
            'PluginURI' => 'https://codekaizen.net',
            'Version' => 'one',
            'Description' => 'This is a test plugin',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'TextDomain' => 'test-plugin',
            'DomainPath' => '/languages',
            'Network' => 'true',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
            'RequiresPlugins' => 'akismet,hello-dolly'
        ];
        // Validator::create(new PluginHeadersArrayRule())->check($input);
        $isValid = Validator::create(new PluginHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
}
