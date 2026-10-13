<?php

namespace Tests\Feature;

use App\Livewire\Admin\Chat;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Invoice;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\Settings;
use App\Livewire\Auth\Gate;
use Livewire\Livewire;
use Tests\TestCase;

/** The admin panel's demo flows: pages render in both locales, the auth gate
 *  opens the panel, and sign-out lands back on /login. */
class AdminPanelTest extends TestCase
{
    public function test_the_dashboard_renders_in_english(): void
    {
        Livewire::test(Dashboard::class)
            ->assertSee('Workspace health')
            ->assertSee('Activity stream')
            ->assertSee('Tickets by language')
            ->assertSee('Resource usage');
    }

    public function test_the_dashboard_renders_in_persian_with_local_digits(): void
    {
        app()->setLocale('fa');

        Livewire::test(Dashboard::class)
            ->assertSee('سلام، حسین')
            ->assertSee('جریان فعالیت')
            ->assertSee('۶۱۲٬۰۰۰٬۰۰۰');
    }

    public function test_every_admin_page_renders(): void
    {
        foreach (['analytics', 'users', 'kanban', 'calendar', 'chat', 'invoice', 'profile', 'settings'] as $page) {
            $class = 'App\\Livewire\\Admin\\'.ucfirst($page);

            Livewire::test($class)
                ->assertSee(__('admin.'.$page.'_title'));
        }
    }

    /** The four pages the later pass filled: real content, no stub empty-state. */
    public function test_the_chat_invoice_profile_and_settings_pages_render_their_content(): void
    {
        Livewire::test(Chat::class)
            ->assertSee(__('admin.chat_person_1'))
            ->assertSee(__('admin.chat_msg_2_in'))
            ->assertSeeHtml('nx-chat');

        Livewire::test(Invoice::class)
            ->assertSee(__('admin.invoice_number'))
            ->assertSee(__('admin.invoice_to_name'))
            ->assertSee(__('admin.invoice_status_paid'))
            ->assertSeeHtml('nx-invoice')
            ->call('createInvoice')
            ->assertDispatched('nx-toast');

        Livewire::test(Profile::class)
            ->assertSee(__('admin.user_name'))
            ->assertSee(__('admin.profile_tab_overview'))
            ->assertSeeHtml('nx-timeline-feed')
            ->assertSeeHtml('nx-tabs');

        Livewire::test(Settings::class)
            ->assertSee(__('admin.settings_field_workspace_name'))
            ->assertSeeHtml('nx-switch')
            ->assertSeeHtml('nx-checkbox')
            ->assertSeeHtml('nx-theme-switch')
            ->assertSeeHtml('nx-language')
            ->assertSeeHtml('nx-dialog');
    }

    /** The composer's send-action appends to the Livewire thread state. */
    public function test_a_sent_chat_message_lands_in_the_thread_state(): void
    {
        Livewire::test(Chat::class)
            ->call('sendMessage', 'سلام، این یک آزمایش است', 'sara')
            ->assertSet('threads.0.unread', 0)
            ->assertSet('threads.0.messages.2.text', 'سلام، این یک آزمایش است');
    }

    /** The danger dialog's confirm closes itself and toasts instead of deleting. */
    public function test_the_settings_danger_dialog_confirm_toasts_and_closes(): void
    {
        Livewire::test(Settings::class)
            ->set('confirmingDelete', true)
            ->call('deleteWorkspace')
            ->assertSet('confirmingDelete', false)
            ->assertDispatched('nx-toast');
    }

    /** The invoice page's amounts roll with the locale's digits. */
    public function test_the_invoice_page_rolls_persian_digits(): void
    {
        app()->setLocale('fa');

        Livewire::test(Invoice::class)
            ->assertSee('۵۷۰٬۰۷۰٬۰۰۰');
    }

    public function test_signing_in_opens_the_panel(): void
    {
        Livewire::test(Gate::class)
            ->set('form.email', 'hussein@nabu.studio')
            ->set('form.password', 'secret')
            ->call('submit')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(session('admin_auth', false));
    }

    public function test_forgot_password_stays_on_the_gate(): void
    {
        Livewire::test(Gate::class, ['mode' => 'forgot'])
            ->assertSet('mode', 'forgot')
            ->call('submit')
            ->assertNoRedirect();

        $this->assertFalse((bool) session('admin_auth', false));
    }

    public function test_signing_out_returns_to_login(): void
    {
        session(['admin_auth' => true]);

        Livewire::test(Dashboard::class)
            ->call('logout')
            ->assertRedirect(route('login'));

        $this->assertNull(session('admin_auth'));
    }

    public function test_login_page_offers_a_jump_into_the_panel_when_signed_in(): void
    {
        $this->withSession(['admin_auth' => true])
            ->get('/login')
            ->assertOk()
            ->assertSee(__('admin.auth_go_panel'));
    }
}
