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

use CodeKaizen\WPPackageMetaProviderORASHub\Validator\Rule\PackageMeta\ThemePackageMetaRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

/**
 * Test cases for the theme headers array validation rule.
 *
 * @since 1.0.0
 */
class ThemePackageMetaRuleTest extends TestCase {

	/**
	 * Tests validation of a complete theme header array with all possible fields.
	 *
	 * @return void
	 */
	public function testValidCaseKitchenSink(): void {
		$input   = [
			'name'                     => 'Test Theme',
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
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
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
	/**
	 * Tests validation of a minimal theme header array with only the required Name field.
	 *
	 * @return void
	 */
	public function testValidCaseBareMinimumFields(): void {
		$input   = [
			'name'      => 'Test Theme',
			'fullSlug'  => 'test-theme/style.css',
			'shortSlug' => 'test-theme',
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
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
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
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
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Tests validation fails when Name field is missing.
	 *
	 * @return void
	 */
	public function testInvalidCaseNameIsNull(): void {
		$input = [
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
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
			'icons'                    => [
				'1x'  => 'https://example.com/icon-128x128.png',
				'2x'  => 'https://example.com/icon-256x256.png',
				'svg' => 'https://example.com/icon.svg',
			],
			'banners'                  => [
				'1x' => 'https://example.com/banner-772x250.png',
				'2x' => 'https://example.com/banner-1544x500.png',
			],
			'bannersRtl'               => [
				'1x' => 'https://example.com/banner-rtl-772x250.png',
				'2x' => 'https://example.com/banner-rtl-1544x500.png',
			],
			'template'                 => 'parent-theme',
			'status'                   => 'publish',
		];
		// Testing without using check() to avoid exception handling.
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function testAllOptionalParamsAreEmptyValid(): void {
		$input   = [
			'name'                     => 'Test Theme',
			'fullSlug'                 => 'test-theme/style.css',
			'shortSlug'                => 'test-theme',
			'version'                  => null,
			'viewUrl'                  => null,
			'downloadUrl'              => null,
			'tested'                   => null,
			'stable'                   => null,
			'tags'                     => [],
			'author'                   => null,
			'authorUrl'                => null,
			'license'                  => null,
			'licenseUrl'               => null,
			'description'              => null,
			'shortDescription'         => null,
			'requiresWordPressVersion' => null,
			'requiresPHPVersion'       => null,
			'textDomain'               => null,
			'domainPath'               => null,
			'icons'                    => [],
			'banners'                  => [],
			'bannersRtl'               => [],
			'template'                 => null,
			'status'                   => null,
		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
		$this->assertTrue( $isValid );
	}
		/**
		 * Undocumented function
		 *
		 * @return void
		 */
	public function testOptionalArraysAreNullInvalid(): void {
		$input = [
			'name'       => 'Test Theme',
			'fullSlug'   => 'test-theme/style.css',
			'shortSlug'  => 'test-theme',
			'icons'      => null,
			'banners'    => null,
			'bannersRtl' => null,
			'tags'       => null,

		];
		$isValid = Validator::create( new ThemePackageMetaRule() )->isValid( $input );
		$this->assertFalse( $isValid );
	}
}
