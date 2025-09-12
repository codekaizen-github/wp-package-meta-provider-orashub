<?php
/**
 * Theme Headers Array Rule Test
 *
 * Tests for validating theme header arrays according to WordPress standards.
 *
 * @package CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Validator\Rule\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderORASHubTests\Unit\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta\ThemeHeadersArrayRule;
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
			'name'                     => 'Test Theme',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https://codekaizen.net',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
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
			'name'                     => 'Test Theme',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https://codekaizen.net',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
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
			'name' => 'Test Theme',
		];
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation fails when ThemeURI is not a valid URI.
	 *
	 * @return void
	 */
	public function testInvalidCaseViewURLIsNotAURL(): void {
		$input   = [
			'name'                     => 'Test Theme',
			'version'                  => '3.0.1',
			'viewUrl'                  => 'not_a_url',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
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
			'name'                     => 'Test Theme',
			'version'                  => 'three',
			'viewUrl'                  => 'https://codekaizen.net',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
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
			'version'                  => '3.0.1',
			'viewUrl'                  => 'https://codekaizen.net',
			'downloadUrl'              => 'https://codekaizen.net',
			'tested'                   => '6.8.2',
			'stable'                   => '6.8.2',
			'tags'                     => [ 'tag1', 'tag2', 'tag3' ],
			'author'                   => 'Andrew Dawes',
			'authorUrl'                => 'https://codekaizen.net/team/andrew-dawes',
			'license'                  => 'GPL v2 or later',
			'licenseUrl'               => 'https://www.gnu.org/licenses/gpl-2.0.html',
			'description'              => 'This is a test theme',
			'shortDescription'         => 'Test',
			'requiresWordPressVersion' => '6.8.2',
			'requiresPHPVersion'       => '8.2.1',
			'textDomain'               => 'test-plugin',
			'domainPath'               => '/languages',
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		// Testing without using check() to avoid exception handling.
		$isValid = Validator::create( new ThemeHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
}
