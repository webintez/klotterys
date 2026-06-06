<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\DrawResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page can be rendered.
     */
    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Administrator Portal');
    }

    /**
     * Test valid admin can log in.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@keralajackpot.com',
            'password' => bcrypt('admin123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@keralajackpot.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test non-admin user is rejected from login.
     */
    public function test_non_admin_cannot_login_to_admin_portal(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/login');
        $this->assertFalse(auth()->check());
    }

    /**
     * Test guest cannot view dashboard.
     */
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin can view bookings list.
     */
    public function test_admin_can_view_bookings_list(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL102030,SL405060',
            'total_price' => 649,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($admin)->get('/admin/bookings');
        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    /**
     * Test admin can create a draw result.
     */
    public function test_admin_can_create_draw_result(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post('/admin/results', [
            'draw_date' => '2026-06-06',
            'lottery_name' => 'FIFTY-FIFTY',
            'draw_number' => 'FF-99',
            'winning_number' => 'VL999999',
            'prize_category' => '1st Prize',
        ]);

        $response->assertRedirect('/admin/results');
        $this->assertDatabaseHas('draw_results', [
            'winning_number' => 'VL999999',
        ]);
    }
}
