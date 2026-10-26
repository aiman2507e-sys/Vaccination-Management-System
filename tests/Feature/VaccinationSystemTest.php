<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Child;
use App\Models\Vaccine;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class VaccinationSystemTest
 * 
 * Verifies all modules and workflows described in the SRS:
 * 1. Public schedule & hospital directory
 * 2. Parent & Hospital registration & login
 * 3. Child profile creation & age-based milestone tracking
 * 4. Parent appointment request booking
 * 5. Admin request approval / rejection
 * 6. Hospital vaccine status update (vaccinated/missed)
 * 7. Admin vaccine availability toggle
 * 8. Report generation & CSV export
 */
class VaccinationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database for all feature tests
        $this->seed();
    }

    /**
     * Test public views
     */
    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('e-Vaccine');

        $response = $this->get('/schedule');
        $response->assertStatus(200);
        $response->assertSee('National Pediatric Vaccination Timeline');

        $response = $this->get('/hospitals');
        $response->assertStatus(200);
        $response->assertSee('Authorized Vaccination Hospitals');
    }

    /**
     * Test parent registration workflow
     */
    public function test_parent_can_register_and_access_dashboard(): void
    {
        $email = 'testparent_' . uniqid() . '@vaccine.test';
        $response = $this->post('/register/parent', [
            'name' => 'Test Parent',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+15551234567',
            'address' => '123 Test Avenue',
            'city' => 'Metropolis',
        ]);

        $response->assertRedirect(route('parent.dashboard'));
        $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'parent']);
    }

    public function test_parent_registration_rejects_invalid_email_and_phone(): void
    {
        $usersBefore = User::count();

        $response = $this->from('/register/parent')->post('/register/parent', [
            'name' => 'Invalid Parent',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => 'call me',
            'address' => '123 Test Avenue',
        ]);

        $response->assertRedirect('/register/parent');
        $response->assertSessionHasErrors(['email', 'phone']);
        $this->assertSame($usersBefore, User::count());
    }

    /**
     * Test hospital registration workflow
     */
    public function test_hospital_can_register_and_access_dashboard(): void
    {
        $email = 'testhospital_' . uniqid() . '@hospital.test';
        $response = $this->post('/register/hospital', [
            'hospital_name' => 'Metro Care Clinic',
            'contact_person' => 'Dr. Alexander',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+15559876543',
            'address' => '99 Healthcare Way',
            'city' => 'Metropolis',
            'location' => 'West Wing',
            'operating_hours' => 'Mon-Sat 09:00 AM - 05:00 PM',
        ]);

        $response->assertRedirect(route('hospital.dashboard'));
        $this->assertDatabaseHas('hospitals', ['name' => 'Metro Care Clinic', 'email' => $email]);
    }

    /**
     * Test child profile creation by parent
     */
    public function test_parent_can_create_child_profile(): void
    {
        $parent = User::where('role', 'parent')->first();
        $this->actingAs($parent);

        $childName = 'Baby Test ' . rand(100, 999);
        $response = $this->post(route('parent.children.store'), [
            'name' => $childName,
            'dob' => Carbon::now()->subMonths(2)->toDateString(),
            'gender' => 'Male',
            'blood_group' => 'A+',
            'birth_weight' => 3.4,
            'birth_place' => 'City Hospital',
            'allergies' => 'None',
            'medical_history' => 'Normal growth',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('children', ['name' => $childName, 'parent_id' => $parent->id]);
    }

    public function test_parent_booking_rejects_invalid_time_slot(): void
    {
        $parent = User::where('role', 'parent')->first();
        $child = $parent->children()->first();
        $vaccine = Vaccine::where('status', 'available')->first();
        $hospital = Hospital::where('status', 'active')->first();
        $existingCount = Appointment::count();

        $response = $this->actingAs($parent)->post(route('parent.book.hospital.store'), [
            'child_id' => $child->id,
            'vaccine_id' => $vaccine->id,
            'hospital_id' => $hospital->id,
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'appointment_time' => 'not a valid slot',
        ]);

        $response->assertSessionHasErrors('appointment_time');
        $this->assertSame($existingCount, Appointment::count());
    }

    public function test_parent_booking_rejects_time_slots_that_have_already_started_today(): void
    {
        $parent = User::where('role', 'parent')->first();
        $child = $parent->children()->first();
        $vaccine = Vaccine::where('status', 'available')->first();
        $hospital = Hospital::where('status', 'active')->first();
        $existingCount = Appointment::count();

        $this->travelTo(Carbon::now()->setTime(16, 0));
        $response = $this->actingAs($parent)->post(route('parent.book.hospital.store'), [
            'child_id' => $child->id,
            'vaccine_id' => $vaccine->id,
            'hospital_id' => $hospital->id,
            'appointment_date' => Carbon::today()->toDateString(),
            'appointment_time' => '03:30 PM - 04:30 PM',
        ]);

        $response->assertSessionHasErrors('appointment_time');
        $this->assertSame($existingCount, Appointment::count());
    }

    public function test_admin_filters_reject_unsupported_values(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.children.index', [
            'gender' => 'unsupported',
        ]));

        $response->assertSessionHasErrors('gender');
    }

    public function test_report_date_filters_allow_an_end_date_without_a_start_date(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.reports.index', [
            'end_date' => Carbon::today()->toDateString(),
        ]));

        $response->assertOk();
    }

    /**
     * Test end-to-end appointment request -> admin approval -> hospital status update
     */
    public function test_full_vaccination_appointment_workflow(): void
    {
        $parent = User::where('role', 'parent')->first();
        // Create a new child for this test
        $child = Child::create([
            'parent_id' => $parent->id,
            'name' => 'Workflow Baby',
            'dob' => Carbon::now()->subMonths(1),
            'gender' => 'Female',
            'blood_group' => 'B+',
        ]);
        $vaccine = Vaccine::where('status', 'available')->first();
        $hospital = Hospital::first();
        $admin = User::where('role', 'admin')->first();

        // 1. Parent submits appointment request
        $this->actingAs($parent);
        $bookingResponse = $this->post(route('parent.book.hospital.store'), [
            'child_id' => $child->id,
            'vaccine_id' => $vaccine->id,
            'hospital_id' => $hospital->id,
            'appointment_date' => Carbon::tomorrow()->toDateString(),
            'appointment_time' => '10:30 AM - 11:30 AM',
        ]);
        $bookingResponse->assertRedirect(route('parent.dashboard'));

        $appointment = Appointment::where('child_id', $child->id)
            ->where('vaccine_id', $vaccine->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($appointment);
        $this->assertEquals('pending', $appointment->status);

        // 2. Admin approves the parent request
        $this->actingAs($admin);
        $approveResponse = $this->patch(route('admin.requests.approve', $appointment->id), [
            'admin_notes' => 'Approved schedule by admin verification.',
        ]);
        $approveResponse->assertStatus(302);

        $appointment->refresh();
        $this->assertEquals('approved', $appointment->status);

        // 3. Hospital updates status to 'vaccinated'
        $hospitalUser = $hospital->user ?: User::where('email', $hospital->email)->first();
        $this->actingAs($hospitalUser);

        $updateResponse = $this->post(route('hospital.appointments.status.update', $appointment->id), [
            'status' => 'vaccinated',
            'administered_date' => Carbon::today()->toDateString(),
            'batch_number' => 'LOT-TEST-992',
            'administered_by' => 'Nurse Tester',
            'hospital_remarks' => 'Vaccine administered without complication.',
        ]);
        $updateResponse->assertRedirect(route('hospital.appointments.index'));

        $appointment->refresh();
        $this->assertEquals('vaccinated', $appointment->status);
        $this->assertEquals('LOT-TEST-992', $appointment->batch_number);
    }

    /**
     * Test admin toggle vaccine availability status
     */
    public function test_admin_can_toggle_vaccine_availability(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $vaccine = Vaccine::first();
        $originalStatus = $vaccine->status;

        $response = $this->patch(route('admin.vaccines.toggle', $vaccine->id));
        $response->assertStatus(302);

        $vaccine->refresh();
        $expectedStatus = ($originalStatus === 'available') ? 'unavailable' : 'available';
        $this->assertEquals($expectedStatus, $vaccine->status);
    }

    /**
     * Test admin export reports CSV
     */
    public function test_admin_can_export_vaccination_report_csv(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.reports.export'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }
}
