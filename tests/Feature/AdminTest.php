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
            'winning_amount' => '₹5,000',
        ]);

        $response->assertRedirect('/admin/results');
        $this->assertDatabaseHas('draw_results', [
            'winning_number' => 'VL999999',
        ]);
    }

    /**
     * Test checking winning ticket returns custom winning amount.
     */
    public function test_user_can_check_winning_ticket_amount(): void
    {
        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL111222',
            'total_price' => 500,
            'status' => 'paid',
        ]);

        DrawResult::create([
            'draw_date' => '2026-06-06',
            'lottery_name' => 'Win Win',
            'draw_number' => 'W-100',
            'winning_number' => 'VL111222',
            'prize_category' => '1st Prize',
            'winning_amount' => '₹75,000',
        ]);

        $response = $this->postJson('/results/check', [
            'ticket' => 'VL111222',
            'mobile' => '9876543210',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'won' => true,
                'prize' => '₹75,000 (1st Prize)',
            ]);
    }

    /**
     * Test user can submit a prize claim with payment screenshot.
     */
    public function test_user_can_submit_prize_claim(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('screenshot.png');

        $response = $this->post('/results/claim', [
            'ticket' => 'VL111222',
            'mobile' => '9876543210',
            'screenshot' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Claim submitted successfully!',
            ]);

        $this->assertDatabaseHas('prize_claims', [
            'ticket_number' => 'VL111222',
            'mobile' => '9876543210',
            'registration_fee' => 3150.00,
        ]);
    }

    /**
     * Test admin dashboard displays correct revenue calculation including claims.
     */
    public function test_admin_dashboard_displays_correct_revenue_including_claims(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Create booking with paid status (₹649)
        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL102030,SL405060',
            'total_price' => 649,
            'status' => 'paid',
        ]);

        // Create prize claim with paid status (₹3,150)
        \App\Models\PrizeClaim::create([
            'ticket_number' => 'VL111222',
            'mobile' => '9876543210',
            'registration_fee' => 3150.00,
            'screenshot' => 'uploads/screenshots/test.png',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        
        // Total revenue should be 649 + 3150 = 3,799
        $response->assertSee('₹3,799');
        $response->assertSee('₹3,150');
    }

    /**
     * Test db:reset-data artisan command resets database tables but preserves admin.
     */
    public function test_reset_data_command_clears_tables_and_preserves_admin(): void
    {
        // Create an admin
        $admin = User::create([
            'name' => 'Custom Admin',
            'email' => 'customadmin@keralajackpot.com',
            'password' => bcrypt('custom123'),
            'is_admin' => true,
        ]);

        // Create booking
        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL102030',
            'total_price' => 500,
            'status' => 'paid',
        ]);

        // Create draw result
        DrawResult::create([
            'draw_date' => '2026-06-06',
            'lottery_name' => 'Win Win',
            'draw_number' => 'W-100',
            'winning_number' => 'VL111222',
            'prize_category' => '1st Prize',
            'winning_amount' => '₹75,000',
        ]);

        // Create claim
        \App\Models\PrizeClaim::create([
            'ticket_number' => 'VL111222',
            'mobile' => '9876543210',
            'registration_fee' => 3150.00,
            'screenshot' => 'uploads/screenshots/test.png',
            'status' => 'paid',
        ]);

        // Call command
        $this->artisan('db:reset-data --force')
            ->assertExitCode(0);

        // Verify tables are cleared
        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('draw_results', 0);
        $this->assertDatabaseCount('prize_claims', 0);

        // Verify admin is preserved
        $this->assertDatabaseHas('users', [
            'email' => 'customadmin@keralajackpot.com',
            'name' => 'Custom Admin',
        ]);
    }

    /**
     * Test admin can view and update website settings.
     */
    public function test_admin_can_view_and_update_website_settings(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Access edit page
        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Website Settings');

        // Update settings
        $response = $this->actingAs($admin)->post('/admin/settings', [
            'upi_id' => 'test-upi-id@okaxis',
            'registration_fee' => 3150.00,
            'bank_name' => 'State Bank of India',
            'bank_account_name' => 'Kerala State Lottery',
            'bank_account_no' => '53845623856',
            'bank_ifsc' => 'SBIN0030466',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertDatabaseHas('website_settings', [
            'upi_id' => 'test-upi-id@okaxis',
            'registration_fee' => 3150.00,
        ]);
    }

    /**
     * Test admin can create a draw result with 10th Prize and custom tax amount.
     */
    public function test_admin_can_create_draw_result_with_10th_prize_and_tax(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post('/admin/results', [
            'draw_date' => '2026-06-08',
            'lottery_name' => 'KERALA-10',
            'draw_number' => 'K-10',
            'winning_number' => 'VL000000',
            'prize_category' => '10th Prize',
            'winning_amount' => '₹500',
            'tax_amount' => '₹50',
        ]);

        $response->assertRedirect('/admin/results');
        $this->assertDatabaseHas('draw_results', [
            'winning_number' => 'VL000000',
            'prize_category' => '10th Prize',
            'tax_amount' => '₹50',
        ]);
    }

    /**
     * Test winner page renders successfully with query parameters.
     */
    public function test_winner_page_renders_successfully(): void
    {
        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL999999',
            'total_price' => 500,
            'status' => 'paid',
        ]);

        DrawResult::create([
            'draw_date' => '2026-06-06',
            'lottery_name' => 'Win Win',
            'draw_number' => 'W-100',
            'winning_number' => 'VL999999',
            'prize_category' => '1st Prize',
            'winning_amount' => '₹15,00,000',
        ]);

        $response = $this->get('/results/winner?ticket=VL999999&mobile=9876543210');

        $response->assertStatus(200);
        $response->assertSee('Congratulations');
        $response->assertSee('Winner Certificate');
        $response->assertSee('Account Details');
    }

    /**
     * Test dynamic certificate image generates successfully.
     */
    public function test_winner_certificate_image_generates_successfully(): void
    {
        Booking::create([
            'fullname' => 'John Doe',
            'mobile' => '9876543210',
            'state' => 'Kerala',
            'pincode' => '682001',
            'tickets' => 'VL999999',
            'total_price' => 500,
            'status' => 'paid',
        ]);

        DrawResult::create([
            'draw_date' => '2026-06-06',
            'lottery_name' => 'Win Win',
            'draw_number' => 'W-100',
            'winning_number' => 'VL999999',
            'prize_category' => '1st Prize',
            'winning_amount' => '₹15,00,000',
        ]);

        $response = $this->get('/results/winner/certificate-image?ticket=VL999999&mobile=9876543210');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}

