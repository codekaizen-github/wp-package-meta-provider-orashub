<?php
/**
 * Plugin Headers Array Rule Test
 *
 * Tests for validating plugin header arrays according to WordPress standards.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\PackageMeta;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\PackageMeta\PluginHeadersArrayRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

/**
 * Test cases for the plugin headers array validation rule.
 *
 * @since 1.0.0
 */
class PluginHeadersArrayRuleTest extends TestCase {

	/**
	 * Tests validation of a complete plugin header array with all possible fields.
	 *
	 * @return void
	 */
	public function testValidCaseKitchenSink(): void {
		$input   = [
			'Name'            => 'Test Plugin',
			'PluginURI'       => 'https://codekaizen.net',
			'Version'         => '3.0.1',
			'Description'     => 'This is a test plugin',
			'Author'          => 'Andrew Dawes',
			'AuthorURI'       => 'https://codekaizen.net/team/andrew-dawes',
			'TextDomain'      => 'test-plugin',
			'DomainPath'      => '/languages',
			'Network'         => 'true',
			'RequiresWP'      => '6.8.2',
			'RequiresPHP'     => '8.2.1',
			'UpdateURI'       => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
			'RequiresPlugins' => 'akismet,hello-dolly',
		];
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation of a plugin header array with a non-full semver RequiresPHP value.
	 *
	 * @return void
	 */
	public function testValidCaseKitchenSinkWithNonFullSemverRequiresPHP(): void {
		$input   = [
			'Name'            => 'Test Plugin',
			'PluginURI'       => 'https://codekaizen.net',
			'Version'         => '3.0.1',
			'Description'     => 'This is a test plugin',
			'Author'          => 'Andrew Dawes',
			'AuthorURI'       => 'https://codekaizen.net/team/andrew-dawes',
			'TextDomain'      => 'test-plugin',
			'DomainPath'      => '/languages',
			'Network'         => 'true',
			'RequiresWP'      => '6.8.2',
			'RequiresPHP'     => '8.2',
			'UpdateURI'       => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
			'RequiresPlugins' => 'akismet,hello-dolly',
		];
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation of a minimal plugin header array with only the required Name field.
	 *
	 * @return void
	 */
	public function testValidCaseNameOnly(): void {
		$input   = [
			'Name' => 'Test Plugin',
		];
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation fails when PluginURI is not a valid URI.
	 *
	 * @return void
	 */
	public function testInvalidCasePluginURIIsNotAURI(): void {
		$input   = [
			'Name'            => 'Test Plugin',
			'PluginURI'       => 'not_a_uri',
			'Version'         => '3.0.1',
			'Description'     => 'This is a test plugin',
			'Author'          => 'Andrew Dawes',
			'AuthorURI'       => 'https://codekaizen.net/team/andrew-dawes',
			'TextDomain'      => 'test-plugin',
			'DomainPath'      => '/languages',
			'Network'         => 'true',
			'RequiresWP'      => '6.8.2',
			'RequiresPHP'     => '8.2',
			'UpdateURI'       => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
			'RequiresPlugins' => 'akismet,hello-dolly',
		];
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Tests validation fails when Version is not a valid version string.
	 *
	 * @return void
	 */
	public function testInvalidCaseVersionIsNotAVersion(): void {
		$input   = [
			'Name'            => 'Test Plugin',
			'PluginURI'       => 'https://codekaizen.net',
			'Version'         => 'one',
			'Description'     => 'This is a test plugin',
			'Author'          => 'Andrew Dawes',
			'AuthorURI'       => 'https://codekaizen.net/team/andrew-dawes',
			'TextDomain'      => 'test-plugin',
			'DomainPath'      => '/languages',
			'Network'         => 'true',
			'RequiresWP'      => '6.8.2',
			'RequiresPHP'     => '8.2',
			'UpdateURI'       => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
			'RequiresPlugins' => 'akismet,hello-dolly',
		];
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Tests validation fails when Name field is missing.
	 *
	 * @return void
	 */
	public function testInvalidCaseNameIsNull(): void {
		$input = [
			'PluginURI'       => 'https://codekaizen.net',
			'Version'         => 'one',
			'Description'     => 'This is a test plugin',
			'Author'          => 'Andrew Dawes',
			'AuthorURI'       => 'https://codekaizen.net/team/andrew-dawes',
			'TextDomain'      => 'test-plugin',
			'DomainPath'      => '/languages',
			'Network'         => 'true',
			'RequiresWP'      => '6.8.2',
			'RequiresPHP'     => '8.2',
			'UpdateURI'       => 'https://github.com/codekaizen-github/wp-package-meta-provider-local',
			'RequiresPlugins' => 'akismet,hello-dolly',
		];
		// Testing without using check() to avoid exception handling.
		$isValid = Validator::create( new PluginHeadersArrayRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
}
