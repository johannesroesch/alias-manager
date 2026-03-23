<?php
declare(strict_types=1);

namespace WPAlias\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use WP_Alias_Admin;

/**
 * Tests für WP_Alias_Admin.
 *
 * Geprüft werden die Hook-Registrierung und grundlegende
 * Capabilities-Absicherung. Die vollständige Render-Logik
 * (Datenbankabfragen, Formularausgabe) gehört in Integrationstests.
 */
final class AliasAdminTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    // -------------------------------------------------------------------------
    // init() / Hook-Registrierung
    // -------------------------------------------------------------------------

    public function test_init_registers_admin_menu_hook(): void
    {
        Functions\expect('add_action')
            ->once()
            ->with('admin_menu', [\WP_Alias_Admin::class, 'register_menu']);

        WP_Alias_Admin::init();
    }

    // -------------------------------------------------------------------------
    // register_menu()
    // -------------------------------------------------------------------------

    public function test_register_menu_adds_options_page(): void
    {
        Functions\expect('__')
            ->zeroOrMoreTimes()
            ->andReturnFirstArg();

        Functions\expect('add_options_page')
            ->once()
            ->with(
                'WP Alias',
                'WP Alias',
                'manage_options',
                'wp-alias',
                [\WP_Alias_Admin::class, 'render_page']
            );

        WP_Alias_Admin::register_menu();
    }

    // -------------------------------------------------------------------------
    // render_page() – Capability-Check
    // -------------------------------------------------------------------------

    public function test_render_page_exits_silently_without_manage_options(): void
    {
        Functions\expect('current_user_can')
            ->once()
            ->with('manage_options')
            ->andReturn(false);

        // Wenn kein manage_options: keine WP-Ausgabe-Funktion darf aufgerufen werden
        Functions\expect('wp_nonce_field')->never();

        ob_start();
        WP_Alias_Admin::render_page();
        $output = ob_get_clean();

        $this->assertSame('', $output);
    }
}
