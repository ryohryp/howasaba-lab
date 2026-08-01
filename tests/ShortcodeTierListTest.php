<?php

declare(strict_types=1);

namespace {
    if ( ! defined( 'ABSPATH' ) ) {
        define( 'ABSPATH', __DIR__ . '/' );
    }

    if ( ! function_exists( 'add_shortcode' ) ) {
        function add_shortcode( $tag, $callback ) {
            return true;
        }
    }

    if ( ! function_exists( 'absint' ) ) {
        function absint( $value ): int {
            return abs( (int) $value );
        }
    }

    require_once __DIR__ . '/../wp-content/themes/wos-survival/inc/shortcode-tier-list.php';
}

namespace Tests {

    use PHPUnit\Framework\TestCase;
    use ReflectionFunction;

    final class ShortcodeTierListTest extends TestCase {
        public function test_supabase_fetcher_accepts_wp_error_contract(): void {
            $function = new ReflectionFunction( 'wos_get_tier_list_from_supabase' );

            self::assertNull(
                $function->getReturnType(),
                'The Supabase fetcher must not declare array-only native return types because WP_Error is valid.'
            );
        }

        public function test_generation_attribute_is_sanitized_and_preferred(): void {
            self::assertSame(
                12,
                \wos_tier_list_get_generation(
                    array(
                        'generation' => '12abc',
                        'gen'        => '3',
                    )
                )
            );

            self::assertSame(
                3,
                \wos_tier_list_get_generation(
                    array(
                        'generation' => '',
                        'gen'        => '3',
                    )
                )
            );

            self::assertSame(
                '',
                \wos_tier_list_get_generation(
                    array(
                        'generation' => '0',
                        'gen'        => '-2',
                    )
                )
            );
        }

        public function test_tier_aliases_are_normalized(): void {
            $aliases = array(
                's+'     => 'S+',
                'S PLUS' => 'S+',
                'splus'  => 'S+',
                'S-PLUS' => 'S+',
                'Ｓ＋'    => 'S+',
                'a'      => 'A',
            );

            foreach ( $aliases as $input => $expected ) {
                self::assertSame( $expected, \wos_tier_list_normalize_tier( $input ) );
            }

            self::assertSame( '', \wos_tier_list_normalize_tier( 'SS' ) );
        }

        public function test_build_hero_normalizes_shared_card_shape(): void {
            $hero = \wos_tier_list_build_hero(
                array(
                    'id'    => '42',
                    'name'  => '  Molly  ',
                    'jp'    => ' モリー ',
                    'thumb' => 'https://example.com/molly.webp',
                    'gen'   => '-7',
                    'type'  => 'marksman',
                    'tier'  => 's plus',
                    'roles' => array( ' Bear Hunt ', '', null, 123, array( 'invalid' ) ),
                    'link'  => 'https://example.com/hero/molly/',
                )
            );

            self::assertNotNull( $hero );
            self::assertSame( 'Molly', $hero['name'] );
            self::assertSame( 'モリー', $hero['jp'] );
            self::assertSame( 7, $hero['gen'] );
            self::assertSame( 'Marksman', $hero['type'] );
            self::assertSame( 'S+', $hero['tier'] );
            self::assertSame( array( 'Bear Hunt', '123' ), $hero['roles'] );

            self::assertNull(
                \wos_tier_list_build_hero(
                    array(
                        'name' => 'Unknown',
                        'tier' => 'SS',
                    )
                )
            );
        }

        public function test_heroes_are_grouped_and_sorted_by_display_priority(): void {
            $heroes = array(
                array( 'tier' => 'S', 'type' => 'Marksman', 'gen' => 12, 'name' => 'Zed' ),
                array( 'tier' => 'S', 'type' => 'Infantry', 'gen' => 3, 'name' => 'Beta' ),
                array( 'tier' => 'S', 'type' => 'Infantry', 'gen' => 5, 'name' => 'Charlie' ),
                array( 'tier' => 'S', 'type' => 'Infantry', 'gen' => 5, 'name' => 'Alpha' ),
                array( 'tier' => 'A', 'type' => 'Lancer', 'gen' => 8, 'name' => 'Lancer Hero' ),
                array( 'tier' => 'SS', 'type' => 'Infantry', 'gen' => 99, 'name' => 'Ignored' ),
            );

            $groups = \wos_tier_list_group_heroes( $heroes );

            self::assertSame(
                array( 'Alpha', 'Charlie', 'Beta', 'Zed' ),
                array_column( $groups['S'], 'name' )
            );
            self::assertSame( array( 'Lancer Hero' ), array_column( $groups['A'], 'name' ) );
            self::assertSame( array(), $groups['S+'] );
        }
    }
}
