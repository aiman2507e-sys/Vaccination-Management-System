<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Child;
use App\Models\Vaccine;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder
 * 
 * Populates initial demonstration data matching all SRS scenarios:
 * 1. Admin account for centralized management and request approval.
 * 2. Hospital clinic facilities and login users for status updates.
 * 3. Parents with registered infants and children.
 * 4. Comprehensive WHO-standard pediatric vaccine schedule.
 * 5. Appointments demonstrating Pending, Approved, and Vaccinated states.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. CREATE ADMIN USER
        // -------------------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@vaccine.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+1 (555) 019-2831',
                'address' => 'Ministry of Health & Family Welfare, Center 1',
                'city' => 'Metropolis',
            ]
        );

        // -------------------------------------------------------------
        // 2. CREATE HOSPITALS AND CORRESPONDING USERS
        // -------------------------------------------------------------
        $hospitalsData = [
            [
                'user_name' => 'City Children Hospital Admin',
                'user_email' => 'cityhospital@vaccine.test',
                'hospital_name' => 'City Children & Maternity Hospital',
                'phone' => '+1 (555) 234-5678',
                'address' => '742 Evergreen Terrace, Medical District',
                'city' => 'Metropolis',
                'location' => 'Central Zone, Block C',
                'contact_person' => 'Dr. Eleanor Vance (Pediatric Chief)',
                'operating_hours' => 'Mon-Sat 08:00 AM - 05:00 PM',
            ],
            [
                'user_name' => 'St. Mary General Hospital Admin',
                'user_email' => 'stmary@vaccine.test',
                'hospital_name' => 'St. Mary General Healthcare Center',
                'phone' => '+1 (555) 345-6789',
                'address' => '108 Healthcare Blvd, Downtown',
                'city' => 'Metropolis',
                'location' => 'South Zone, Sector 4',
                'contact_person' => 'Dr. Robert Chen',
                'operating_hours' => 'Mon-Sun 24/7 Vaccination Wing',
            ],
            [
                'user_name' => 'Sunrise Pediatric Clinic Admin',
                'user_email' => 'sunrise@vaccine.test',
                'hospital_name' => 'Sunrise Pediatric & Immunization Clinic',
                'phone' => '+1 (555) 456-7890',
                'address' => '42 Park Avenue, North Hills',
                'city' => 'Metropolis',
                'location' => 'North District, Plaza 2',
                'contact_person' => 'Nurse Sarah Jenkins',
                'operating_hours' => 'Mon-Fri 08:30 AM - 04:30 PM',
            ],
            [
                'user_name' => 'Hope Community Health Center Admin',
                'user_email' => 'hope@vaccine.test',
                'hospital_name' => 'Hope Community Health Center',
                'phone' => '+1 (555) 567-8901',
                'address' => '19 East River Road',
                'city' => 'Metropolis',
                'location' => 'East Riverside',
                'contact_person' => 'Dr. Marcus Brody',
                'operating_hours' => 'Mon-Sat 09:00 AM - 03:00 PM',
            ],
        ];

        $createdHospitals = [];
        foreach ($hospitalsData as $h) {
            $user = User::firstOrCreate(
                ['email' => $h['user_email']],
                [
                    'name' => $h['user_name'],
                    'password' => Hash::make('password123'),
                    'role' => 'hospital',
                    'phone' => $h['phone'],
                    'address' => $h['address'],
                    'city' => $h['city'],
                ]
            );

            $hospital = Hospital::firstOrCreate(
                ['email' => $h['user_email']],
                [
                    'user_id' => $user->id,
                    'name' => $h['hospital_name'],
                    'phone' => $h['phone'],
                    'address' => $h['address'],
                    'city' => $h['city'],
                    'location' => $h['location'],
                    'contact_person' => $h['contact_person'],
                    'operating_hours' => $h['operating_hours'],
                    'status' => 'active',
                ]
            );
            $createdHospitals[] = $hospital;
        }

        // -------------------------------------------------------------
        // 3. CREATE PARENTS
        // -------------------------------------------------------------
        $parentsData = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'parent1@vaccine.test',
                'password' => Hash::make('password123'),
                'phone' => '+1 (555) 901-1122',
                'address' => '12 Maple Leaf Street, Apt 4B',
                'city' => 'Metropolis',
            ],
            [
                'name' => 'David Miller',
                'email' => 'parent2@vaccine.test',
                'password' => Hash::make('password123'),
                'phone' => '+1 (555) 902-3344',
                'address' => '88 Oakridge Drive',
                'city' => 'Metropolis',
            ],
            [
                'name' => 'Amina Khan',
                'email' => 'parent3@vaccine.test',
                'password' => Hash::make('password123'),
                'phone' => '+1 (555) 903-5566',
                'address' => '305 Sunset Boulevard',
                'city' => 'Metropolis',
            ],
        ];

        $createdParents = [];
        foreach ($parentsData as $p) {
            $createdParents[] = User::firstOrCreate(
                ['email' => $p['email']],
                array_merge($p, ['role' => 'parent'])
            );
        }

        // -------------------------------------------------------------
        // 4. CREATE VACCINE REGISTRY
        // -------------------------------------------------------------
        $vaccinesData = [
            [
                'name' => 'BCG (Bacillus Calmette-Guérin)',
                'short_code' => 'BCG',
                'target_disease' => 'Tuberculosis (TB)',
                'recommended_age_days' => 0,
                'recommended_age_label' => 'At Birth',
                'dosage' => '0.05 ml',
                'route' => 'Intradermal',
                'status' => 'available',
                'description' => 'Provides essential childhood protection against severe forms of tuberculosis, including tuberculous meningitis.',
                'precautions' => 'Do not administer to immunodeficient infants or those with generalized eczema.',
            ],
            [
                'name' => 'Hepatitis B (Birth Dose)',
                'short_code' => 'HEP-B-0',
                'target_disease' => 'Hepatitis B Virus (Liver infection)',
                'recommended_age_days' => 0,
                'recommended_age_label' => 'At Birth (within 24 hrs)',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular (Anterolateral thigh)',
                'status' => 'available',
                'description' => 'Prevents perinatal transmission of Hepatitis B from mother to infant.',
                'precautions' => 'Recommended within 24 hours of delivery.',
            ],
            [
                'name' => 'OPV 0 (Oral Polio Vaccine - Birth Dose)',
                'short_code' => 'OPV-0',
                'target_disease' => 'Poliomyelitis (Infantile Paralysis)',
                'recommended_age_days' => 0,
                'recommended_age_label' => 'At Birth',
                'dosage' => '2 drops',
                'route' => 'Oral',
                'status' => 'available',
                'description' => 'Live attenuated oral polio drops to induce early mucosal gut immunity against poliovirus.',
                'precautions' => 'If child spits it up within 10 minutes, administer another dose.',
            ],
            [
                'name' => 'Pentavalent 1 (DTP + HepB + Hib)',
                'short_code' => 'PENTA-1',
                'target_disease' => 'Diphtheria, Tetanus, Pertussis, Hep B, Hib Pneumonia/Meningitis',
                'recommended_age_days' => 42,
                'recommended_age_label' => '6 Weeks',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular',
                'status' => 'available',
                'description' => '5-in-1 combo vaccine protecting against five dangerous childhood killers.',
                'precautions' => 'Mild local swelling or low fever may occur.',
            ],
            [
                'name' => 'OPV 1 (Oral Polio Dose 1)',
                'short_code' => 'OPV-1',
                'target_disease' => 'Poliomyelitis',
                'recommended_age_days' => 42,
                'recommended_age_label' => '6 Weeks',
                'dosage' => '2 drops',
                'route' => 'Oral',
                'status' => 'available',
                'description' => 'Second milestone dose of oral polio drops.',
                'precautions' => 'Keep infant calm before oral dropper application.',
            ],
            [
                'name' => 'Rotavirus 1',
                'short_code' => 'ROTA-1',
                'target_disease' => 'Rotavirus Diarrhea & Dehydration',
                'recommended_age_days' => 42,
                'recommended_age_label' => '6 Weeks',
                'dosage' => '5 drops (oral)',
                'route' => 'Oral',
                'status' => 'available',
                'description' => 'Protects infants from severe life-threatening diarrhea caused by Rotavirus.',
                'precautions' => 'Contraindicated in infants with history of intussusception.',
            ],
            [
                'name' => 'PCV 1 (Pneumococcal Conjugate Vaccine)',
                'short_code' => 'PCV-1',
                'target_disease' => 'Pneumococcal Pneumonia & Meningitis',
                'recommended_age_days' => 42,
                'recommended_age_label' => '6 Weeks',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular',
                'status' => 'available',
                'description' => 'Guards against Streptococcus pneumoniae bacterial infections.',
                'precautions' => 'Administer in opposite thigh from Pentavalent.',
            ],
            [
                'name' => 'Pentavalent 2 (DTP + HepB + Hib)',
                'short_code' => 'PENTA-2',
                'target_disease' => 'Diphtheria, Tetanus, Pertussis, Hep B, Hib',
                'recommended_age_days' => 70,
                'recommended_age_label' => '10 Weeks',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular',
                'status' => 'available',
                'description' => 'Second dose of 5-in-1 combo vaccine.',
                'precautions' => 'Verify minimum 4-week interval from Penta-1.',
            ],
            [
                'name' => 'Pentavalent 3 (DTP + HepB + Hib)',
                'short_code' => 'PENTA-3',
                'target_disease' => 'Diphtheria, Tetanus, Pertussis, Hep B, Hib',
                'recommended_age_days' => 98,
                'recommended_age_label' => '14 Weeks',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular',
                'status' => 'available',
                'description' => 'Third primary dose completing the infant Pentavalent foundation.',
                'precautions' => 'Assess child for febrile illness before injection.',
            ],
            [
                'name' => 'Measles & Rubella 1 (MR-1)',
                'short_code' => 'MR-1',
                'target_disease' => 'Measles & Rubella',
                'recommended_age_days' => 270,
                'recommended_age_label' => '9 Months',
                'dosage' => '0.5 ml',
                'route' => 'Subcutaneous',
                'status' => 'available',
                'description' => 'Essential milestone to prevent high mortality measles outbreaks and rubella.',
                'precautions' => 'Reconstitute only with supplied diluent.',
            ],
            [
                'name' => 'MMR Booster (Measles, Mumps, Rubella)',
                'short_code' => 'MMR-2',
                'target_disease' => 'Measles, Mumps, Rubella',
                'recommended_age_days' => 450,
                'recommended_age_label' => '15 - 18 Months',
                'dosage' => '0.5 ml',
                'route' => 'Subcutaneous',
                'status' => 'available',
                'description' => 'Booster dose ensuring lifetime antibody protection against Measles, Mumps and Rubella.',
                'precautions' => 'Observe for rash or mild fever 7-10 days post immunization.',
            ],
            [
                'name' => 'Typhoid Conjugate Vaccine (TCV)',
                'short_code' => 'TCV-1',
                'target_disease' => 'Typhoid Fever (Salmonella Typhi)',
                'recommended_age_days' => 730,
                'recommended_age_label' => '2 Years',
                'dosage' => '0.5 ml',
                'route' => 'Intramuscular',
                'status' => 'unavailable', // Demonstrating availability toggle as requested in SRS
                'description' => 'Protects young children from waterborne typhoid enteric fever.',
                'precautions' => 'Stock currently undergoing batch quality verification.',
            ],
        ];

        $createdVaccines = [];
        foreach ($vaccinesData as $v) {
            $createdVaccines[$v['short_code']] = Vaccine::firstOrCreate(
                ['short_code' => $v['short_code']],
                $v
            );
        }

        // -------------------------------------------------------------
        // 5. CREATE CHILDREN UNDER PARENTS
        // -------------------------------------------------------------
        $childrenData = [
            // Parent 1 children
            [
                'parent_id' => $createdParents[0]->id,
                'name' => 'Leo Johnson',
                'dob' => Carbon::now()->subMonths(3), // ~12 weeks old
                'gender' => 'Male',
                'blood_group' => 'A+',
                'birth_weight' => 3.35,
                'birth_place' => 'City Maternity Clinic',
                'allergies' => 'None known',
                'medical_history' => 'Full-term normal delivery, cried immediately at birth.',
            ],
            [
                'parent_id' => $createdParents[0]->id,
                'name' => 'Mia Johnson',
                'dob' => Carbon::now()->subMonths(16), // ~16 months old
                'gender' => 'Female',
                'blood_group' => 'O+',
                'birth_weight' => 3.10,
                'birth_place' => 'City Hospital',
                'allergies' => 'Mild lactose sensitivity',
                'medical_history' => 'Up to date with primary immunization series.',
            ],
            // Parent 2 children
            [
                'parent_id' => $createdParents[1]->id,
                'name' => 'Oliver Miller',
                'dob' => Carbon::now()->subWeeks(7), // ~7 weeks old
                'gender' => 'Male',
                'blood_group' => 'B+',
                'birth_weight' => 3.50,
                'birth_place' => 'St. Mary General Healthcare',
                'allergies' => 'None',
                'medical_history' => 'Healthy infant, breastfed.',
            ],
            // Parent 3 children
            [
                'parent_id' => $createdParents[2]->id,
                'name' => 'Zara Khan',
                'dob' => Carbon::now()->subMonths(10), // ~10 months old
                'gender' => 'Female',
                'blood_group' => 'AB+',
                'birth_weight' => 2.95,
                'birth_place' => 'Sunrise Pediatric Clinic',
                'allergies' => 'None reported',
                'medical_history' => 'Born at 38 weeks. Strong vital signs.',
            ],
        ];

        $createdChildren = [];
        foreach ($childrenData as $c) {
            $createdChildren[] = Child::firstOrCreate(
                ['name' => $c['name'], 'parent_id' => $c['parent_id']],
                $c
            );
        }

        // -------------------------------------------------------------
        // 6. CREATE REALISTIC APPOINTMENTS & VACCINATION HISTORIES
        // -------------------------------------------------------------
        // Appointment 1: Completed vaccination for Leo Johnson (BCG at birth)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1001'],
            [
                'parent_id' => $createdParents[0]->id,
                'child_id' => $createdChildren[0]->id, // Leo
                'vaccine_id' => $createdVaccines['BCG']->id,
                'hospital_id' => $createdHospitals[0]->id,
                'appointment_date' => Carbon::now()->subMonths(3),
                'appointment_time' => '09:00 AM - 10:00 AM',
                'status' => 'vaccinated',
                'admin_notes' => 'Approved automatically for birth immunization.',
                'admin_action_at' => Carbon::now()->subMonths(3),
                'administered_date' => Carbon::now()->subMonths(3),
                'batch_number' => 'BCG-SERUM-8821B',
                'administered_by' => 'Nurse Claire Adams',
                'hospital_remarks' => 'Administered intradermally in left upper arm. No adverse immediate reaction.',
            ]
        );

        // Appointment 2: Completed vaccination for Leo Johnson (Pentavalent 1 at 6 weeks)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1002'],
            [
                'parent_id' => $createdParents[0]->id,
                'child_id' => $createdChildren[0]->id, // Leo
                'vaccine_id' => $createdVaccines['PENTA-1']->id,
                'hospital_id' => $createdHospitals[0]->id,
                'appointment_date' => Carbon::now()->subWeeks(6),
                'appointment_time' => '10:30 AM - 11:30 AM',
                'status' => 'vaccinated',
                'admin_notes' => 'Approved schedule.',
                'admin_action_at' => Carbon::now()->subWeeks(6)->subDay(),
                'administered_date' => Carbon::now()->subWeeks(6),
                'batch_number' => 'PEN-GSK-40291X',
                'administered_by' => 'Dr. Eleanor Vance',
                'hospital_remarks' => 'Child tolerated dose well. Parent advised on infant paracetamol for low fever.',
            ]
        );

        // Appointment 3: Approved upcoming appointment for Leo (Pentavalent 2 at 10 weeks)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1003'],
            [
                'parent_id' => $createdParents[0]->id,
                'child_id' => $createdChildren[0]->id, // Leo
                'vaccine_id' => $createdVaccines['PENTA-2']->id,
                'hospital_id' => $createdHospitals[0]->id,
                'appointment_date' => Carbon::now()->addDays(3),
                'appointment_time' => '09:30 AM - 10:30 AM',
                'status' => 'approved',
                'admin_notes' => 'Appointment approved. Hospital confirmed slot availability.',
                'admin_action_at' => Carbon::now()->subDay(),
            ]
        );

        // Appointment 4: Pending appointment request from Parent 2 for Oliver (Pentavalent 1)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1004'],
            [
                'parent_id' => $createdParents[1]->id,
                'child_id' => $createdChildren[2]->id, // Oliver
                'vaccine_id' => $createdVaccines['PENTA-1']->id,
                'hospital_id' => $createdHospitals[1]->id,
                'appointment_date' => Carbon::now()->addDays(5),
                'appointment_time' => '11:00 AM - 12:00 PM',
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        // Appointment 5: Completed vaccination for Mia Johnson (MR-1 at 9 months)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1005'],
            [
                'parent_id' => $createdParents[0]->id,
                'child_id' => $createdChildren[1]->id, // Mia
                'vaccine_id' => $createdVaccines['MR-1']->id,
                'hospital_id' => $createdHospitals[2]->id,
                'appointment_date' => Carbon::now()->subMonths(7),
                'appointment_time' => '02:00 PM - 03:00 PM',
                'status' => 'vaccinated',
                'admin_notes' => 'Approved routine 9-month immunization.',
                'admin_action_at' => Carbon::now()->subMonths(7),
                'administered_date' => Carbon::now()->subMonths(7),
                'batch_number' => 'MR-SII-77109',
                'administered_by' => 'Nurse Sarah Jenkins',
                'hospital_remarks' => 'Measles-Rubella vaccine administered right upper arm. Child rested 15 minutes before discharge.',
            ]
        );

        // Appointment 6: Pending request from Parent 3 for Zara (MR-1 at 10 months)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1006'],
            [
                'parent_id' => $createdParents[2]->id,
                'child_id' => $createdChildren[3]->id, // Zara
                'vaccine_id' => $createdVaccines['MR-1']->id,
                'hospital_id' => $createdHospitals[2]->id,
                'appointment_date' => Carbon::now()->addDays(7),
                'appointment_time' => '10:00 AM - 11:00 AM',
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        // Appointment 7: Approved appointment ready for Hospital status update (Mia - MMR booster)
        Appointment::firstOrCreate(
            ['booking_reference' => 'VAC-2026-1007'],
            [
                'parent_id' => $createdParents[0]->id,
                'child_id' => $createdChildren[1]->id, // Mia
                'vaccine_id' => $createdVaccines['MMR-2']->id,
                'hospital_id' => $createdHospitals[0]->id,
                'appointment_date' => Carbon::now(), // Today!
                'appointment_time' => '10:00 AM - 11:00 AM',
                'status' => 'approved',
                'admin_notes' => 'Approved for 16-month booster visit.',
                'admin_action_at' => Carbon::now()->subDays(2),
            ]
        );
    }
}
