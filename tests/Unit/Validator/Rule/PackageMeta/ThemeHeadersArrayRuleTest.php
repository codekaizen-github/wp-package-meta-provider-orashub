<?php
/**
 * Theme Headers Array Rule Test
 *
 * Tests for validating theme header arrays according to WordPress standards.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta\ThemeHeadersArrayRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

/**
 * Test cases for the theme headers array validation rule.
 *
 * @since 1.0.0
 */
class ThemeHeadersArrayRuleTest extends TestCase {

	/**
	 * Tests validation of a complete theme header array with all possible fields.
	 *
	 * @return void
	 */
	public function testValidCaseKitchenSink(): void {
		$input   = [
			'Name'        => 'Test Theme',
			'ThemeURI'    => 'https://codekaizen.net',
			'Description' => 'This is a test theme',
			'Author'      => 'Andrew Dawes',
			'AuthorURI'   => 'https://codekaizen.net/team/andrew-dawes',
			'Version'     => '3.0.1',
			'Template'    => 'parent-theme',
			'Status'      => 'publish',
			'Tags'        => 'awesome,cool,test',
			'TextDomain'  => 'test-theme',
			'DomainPath'  => '/languages',
			'RequiresWP'  => '6.8.2',
			'RequiresPHP' => '8.2.1',
			'UpdateURI'   => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation of a theme header array with a non-full semver RequiresPHP value.
	 *
	 * @return void
	 */
	public function testValidCaseKitchenSinkWithNonFullSemverRequiresPHP(): void {
		$input   = [
			'Name'        => 'Test Theme',
			'ThemeURI'    => 'https://codekaizen.net',
			'Description' => 'This is a test theme',
			'Author'      => 'Andrew Dawes',
			'AuthorURI'   => 'https://codekaizen.net/team/andrew-dawes',
			'Version'     => '3.0.1',
			'Template'    => 'parent-theme',
			'Status'      => 'publish',
			'Tags'        => 'awesome,cool,test',
			'TextDomain'  => 'test-theme',
			'DomainPath'  => '/languages',
			'RequiresWP'  => '6.8.2',
			'RequiresPHP' => '8.2',
			'UpdateURI'   => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation of a minimal theme header array with only the required Name field.
	 *
	 * @return void
	 */
	public function testValidCaseNameOnly(): void {
		$input   = [
			'Name' => 'Test Theme',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation fails when ThemeURI is not a valid URI.
	 *
	 * @return void
	 */
	public function testInvalidCaseThemeURIIsNotAURI(): void {
		$input   = [
			'Name'        => 'Test Theme',
			'ThemeURI'    => 'not_a_uri',
			'Description' => 'This is a test theme',
			'Author'      => 'Andrew Dawes',
			'AuthorURI'   => 'https://codekaizen.net/team/andrew-dawes',
			'Version'     => '3.0.1',
			'Template'    => 'parent-theme',
			'Status'      => 'publish',
			'Tags'        => 'awesome,cool,test',
			'TextDomain'  => 'test-theme',
			'DomainPath'  => '/languages',
			'RequiresWP'  => '6.8.2',
			'RequiresPHP' => '8.2.1',
			'UpdateURI'   => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Tests validation fails when Version is not a valid version string.
	 *
	 * @return void
	 */
	public function testInvalidCaseVersionIsNotAVersion(): void {
		$input   = [
			'Name'        => 'Test Theme',
			'ThemeURI'    => 'https://codekaizen.net',
			'Description' => 'This is a test theme',
			'Author'      => 'Andrew Dawes',
			'AuthorURI'   => 'https://codekaizen.net/team/andrew-dawes',
			'Version'     => 'main',
			'Template'    => 'parent-theme',
			'Status'      => 'publish',
			'Tags'        => 'awesome,cool,test',
			'TextDomain'  => 'test-theme',
			'DomainPath'  => '/languages',
			'RequiresWP'  => '6.8.2',
			'RequiresPHP' => '8.2.1',
			'UpdateURI'   => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Tests validation fails when Name field is missing.
	 *
	 * @return void
	 */
	public function testInvalidCaseNameIsNull(): void {
		$input = [
			'ThemeURI'    => 'https://codekaizen.net',
			'Description' => 'This is a test theme',
			'Author'      => 'Andrew Dawes',
			'AuthorURI'   => 'https://codekaizen.net/team/andrew-dawes',
			'Version'     => '3.0.1',
			'Template'    => 'parent-theme',
			'Status'      => 'publish',
			'Tags'        => 'awesome,cool,test',
			'TextDomain'  => 'test-theme',
			'DomainPath'  => '/languages',
			'RequiresWP'  => '6.8.2',
			'RequiresPHP' => '8.2.1',
			'UpdateURI'   => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
		];
		// Testing without using check() to avoid exception handling.
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
}
