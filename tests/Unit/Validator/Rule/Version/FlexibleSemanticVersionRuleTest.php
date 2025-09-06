<?php
/**
 * Flexible Semantic Version Rule Test
 *
 * Tests for validating flexible semantic version strings according to WordPress standards.
 *
 * @package CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\Version
 * @since 1.0.0
 */

namespace CodeKaizen\WPPackageMetaProviderLocalTests\Unit\Validator\Rule\Version;

use CodeKaizen\WPPackageMetaProviderLocal\Validator\Rule\Version\FlexibleSemanticVersionRule;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;

/**
 * Test cases for flexible semantic version validation rule.
 *
 * @since 1.0.0
 */
class FlexibleSemanticVersionRuleTest extends TestCase {

	/**
	 * Tests validation of version string with only major version number.
	 *
	 * @return void
	 */
	public function testMajorVersionOnly(): void {
		$this->assertTrue( Validator::create( new FlexibleSemanticVersionRule() )->isValid( '6' ) );
	}
	/**
	 * Tests validation of version string with major and minor version numbers.
	 *
	 * @return void
	 */
	public function testMajorMinorVersionOnly(): void {
		$this->assertTrue( Validator::create( new FlexibleSemanticVersionRule() )->isValid( '6.3' ) );
	}
	/**
	 * Tests validation of complete semantic version string with major, minor, and patch numbers.
	 *
	 * @return void
	 */
	public function testFullSemverVersion(): void {
		$this->assertTrue( Validator::create( new FlexibleSemanticVersionRule() )->isValid( '6.3.4' ) );
	}
	/**
	 * Tests validation fails for non-numeric version strings.
	 *
	 * @return void
	 */
	public function testNumericWord(): void {
		$this->assertFalse( Validator::create( new FlexibleSemanticVersionRule() )->isValid( 'five' ) );
	}
	/**
	 * Tests validation fails for version strings with extra spaces.
	 *
	 * @return void
	 */
	public function testAdditionalSpacesWord(): void {
		$this->assertFalse( Validator::create( new FlexibleSemanticVersionRule() )->isValid( '6. 5' ) );
	}
}
