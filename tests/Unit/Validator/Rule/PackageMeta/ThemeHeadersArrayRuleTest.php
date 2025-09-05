<?php

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta\ThemeHeadersArrayRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

class ThemeHeadersArrayRuleTest extends TestCase
{
    public function testValidCaseKitchenSink(): void
    {
        $input = [
            'Name' => 'Test Theme',
            'ThemeURI' => 'https://codekaizen.net',
            'Description' => 'This is a test theme',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'Version' => '3.0.1',
            'Template' => 'parent-theme',
            'Status' => 'publish',
            'Tags' => 'awesome,cool,test',
            'TextDomain' => 'test-theme',
            'DomainPath' => '/languages',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2.1',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
        ];
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testValidCaseKitchenSinkWithNonFullSemverRequiresPHP(): void
    {
        $input = [
            'Name' => 'Test Theme',
            'ThemeURI' => 'https://codekaizen.net',
            'Description' => 'This is a test theme',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'Version' => '3.0.1',
            'Template' => 'parent-theme',
            'Status' => 'publish',
            'Tags' => 'awesome,cool,test',
            'TextDomain' => 'test-theme',
            'DomainPath' => '/languages',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
        ];
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testValidCaseNameOnly(): void
    {
        $input = [
            'Name' => 'Test Theme'
        ];
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertTrue($isValid);
    }
    public function testInvalidCaseThemeURIIsNotAURI(): void
    {
        $input = [
            'Name' => 'Test Theme',
            'ThemeURI' => 'not_a_uri',
            'Description' => 'This is a test theme',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'Version' => '3.0.1',
            'Template' => 'parent-theme',
            'Status' => 'publish',
            'Tags' => 'awesome,cool,test',
            'TextDomain' => 'test-theme',
            'DomainPath' => '/languages',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2.1',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
        ];
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
    public function testInvalidCaseVersionIsNotAVersion(): void
    {
        $input = [
            'Name' => 'Test Theme',
            'ThemeURI' => 'https://codekaizen.net',
            'Description' => 'This is a test theme',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'Version' => 'main',
            'Template' => 'parent-theme',
            'Status' => 'publish',
            'Tags' => 'awesome,cool,test',
            'TextDomain' => 'test-theme',
            'DomainPath' => '/languages',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2.1',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
        ];
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
    public function testInvalidCaseNameIsNull(): void
    {
        $input = [
            'ThemeURI' => 'https://codekaizen.net',
            'Description' => 'This is a test theme',
            'Author' => 'Andrew Dawes',
            'AuthorURI' => 'https://codekaizen.net/team/andrew-dawes',
            'Version' => '3.0.1',
            'Template' => 'parent-theme',
            'Status' => 'publish',
            'Tags' => 'awesome,cool,test',
            'TextDomain' => 'test-theme',
            'DomainPath' => '/languages',
            'RequiresWP' => '6.8.2',
            'RequiresPHP' => '8.2.1',
            'UpdateURI' => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
        ];
        // Validator::create(new ThemeHeadersArrayRule())->check($input);
        $isValid = Validator::create(new ThemeHeadersArrayRule())->isValid($input);
        $this->assertFalse($isValid);
    }
}
