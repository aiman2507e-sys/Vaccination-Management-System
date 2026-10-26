# Vaccination Management System (e-Vaccination)
## Complete Aptech eProject Documentation Report

---

## 1. Certificate of Completion

```
========================================================================================
                          APTECH ePROJECT STUDY CENTRE
                     CENTRE OF EXCELLENCE IN INFORMATION TECHNOLOGY
========================================================================================

                               CERTIFICATE OF COMPLETION

This is to certify that the project entitled:

                    "VACCINATION MANAGEMENT SYSTEM (e-VACCINATION)"

has been successfully analyzed, designed, developed, and verified in accordance with the
prescribed Software Requirements Specification (SRS) standards, employing Laravel 12,
Blade Component Architecture, Eloquent ORM, and Relational Database Management.

Role Portals Implemented:
  1. Central Health Administration Control Panel
  2. Pediatric Healthcare Facility & Hospital Portal
  3. Parent & Infant Immunization Self-Service Portal

Implementation Standard:
  - Structured Laddered Development Approach
  - Complete Code Commenting & Architecture Traceability
  - Automated Unit & Feature Test Verification (100% Pass Rate)

Date of Completion: October 15, 2026
Project Status: Production-Ready & Verified
========================================================================================
```

---

## 2. Table of Contents

1. **Certificate of Completion**
2. **Table of Contents**
3. **Problem Definition & Objectives**
   - 3.1 Existing System Limitations
   - 3.2 Proposed Solution: The e-Vaccination Ecosystem
   - 3.3 Objectives of the Project
4. **Customer Requirement Specification (CRS / SRS)**
   - 4.1 Functional Requirements Matrix
   - 4.2 Non-Functional Requirements
   - 4.3 Hardware & Software Requirements
5. **Project Plan & Phased Implementation (Laddered Approach)**
6. **Entity-Relationship (E-R) Diagram & Data Dictionary**
   - 6.1 Mermaid E-R Diagram
   - 6.2 Relational Schema Specifications
7. **System Architecture & Workflow Algorithms**
   - 7.1 Multi-Role Appointment Lifecycle State Machine
   - 7.2 Automated Milestone Age Due Algorithm
8. **GUI Standards & Interface Design Document**
   - 8.1 Visual Hierarchy & Design System
   - 8.2 Role-Based Interface Overview
9. **Task Sheet & Module Traceability Matrix**
10. **Project Review & Monitoring Report**
11. **Unit & Feature Testing Checklist (Automated Test Execution)**
12. **Final Checklist & Deployment Guide (SQLite & MySQL)**

---

## 3. Problem Definition & Objectives

### 3.1 Existing System Limitations
In the conventional manual system, pediatric vaccines are delivered to infants and children through sporadic outreach programs, school camps, and physical paper records. This approach suffers from critical vulnerabilities:
- **Missed Opportunities**: When children miss school sessions due to minor illnesses or family relocations, vaccination windows lapse unnoticed.
- **Permanent Health Hazards**: Unvaccinated infants remain defenseless against lethal or paralyzing ailments including Polio, Tuberculosis, Hepatitis B, Measles, and Diphtheria.
- **Logistical Inefficiencies**: Healthcare workers spend countless man-hours manually indexing paper registries across distant villages and urban sectors.
- **Information Asymmetry**: Parents lack automated reminders regarding milestone booster doses (e.g., at 6 weeks, 10 weeks, 14 weeks, 9 months, and 18 months).

### 3.2 Proposed Solution: The e-Vaccination Ecosystem
The e-Vaccination system transforms this manual, fragmented routine into a live, collaborative digital health registry:
- **Centralized Electronic Registry**: Every infant is recorded under their parent's account with precise date of birth, blood group, allergies, and delivery place.
- **Predictive Age-Based Milestone Engine**: The system algorithmically calculates due dates for recommended vaccines and displays real-time dashboard notifications.
- **Hospital Slot Scheduling**: Parents select partner hospitals and submit preferred date/time appointment requests.
- **Administrative Triaging**: Health Administrators review requests, prevent facility overcrowding, and confirm inventory availability.
- **Digital Immunization Card**: Hospitals record batch lot numbers, administration timestamps, and clinician observations, instantly generating an official printable certificate.

---

## 4. Customer Requirement Specification (CRS / SRS)

### 4.1 Functional Requirements by Module

#### Module 1: Admin Panel
- **All Child Details**: Comprehensive directory of all registered infants and children with parent contact, age calculation, gender, blood group, and full medical profile.
- **Date & Time of Vaccination**: Global schedule calendar listing upcoming appointments across all children, filterable by date range, hospital, and vaccine.
- **Report of Vaccination**: Date-wise, child-wise, hospital-wise statistical reporting with status filters, print layout, and **Export to CSV**.
- **List of Vaccine (Available / Unavailable)**: Manage vaccine inventory, dosage, route, target disease, and toggle status between `available` and `unavailable`.
- **Request from Parents (Approve / Reject)**: Central triage queue where administrators review appointment requests and either approve or reject with custom administrative remarks.
- **Hospital Management**: Complete CRUD operations (Add Hospital, Edit Hospital, Delete/Deactivate Hospital, and List Hospitals with booking performance metrics).
- **Booking Details**: Master booking table tracking all appointment references and timelines.

#### Module 2: Parent Portal
- **Register & Login**: Self-service registration with contact, address, and password authentication.
- **My Profile**: Update personal details, contact phone, residential address, and credentials.
- **Details of Child**: Register new children, update medical histories, record birth weights and allergies, and view comprehensive pediatric immunization timelines.
- **Vaccination Dates**: Dashboard alerts and notification views showing scheduled visits and age-based upcoming milestone due dates.
- **Book Hospital**: Search authorized hospital clinics, choose vaccine and time slot, and submit booking requests for admin approval.
- **Report of Vaccination Taken**: Official digital immunization card detailing previous doses, administering facility, batch numbers, and clinician notes with one-click print functionality.

#### Module 3: Hospital Portal
- **Register & Login**: Register facility with hospital name, email, phone, location, and operating hours.
- **Facility Dashboard**: Daily appointment queue, upcoming schedule counts, and vaccination statistics.
- **Update Vaccine Status**: Review admin-approved appointments and record clinical completion by marking status as **Vaccinated** (with administered date, lot number, staff name, and observations) or **Not Vaccinated** (with missed reasons).

---

## 5. Entity-Relationship (E-R) Diagram & Data Dictionary

```mermaid
erDiagram
    USERS ||--o{ CHILDREN : "registers (parent)"
    USERS ||--o{ APPOINTMENTS : "requests (parent)"
    USERS ||--o| HOSPITALS : "manages (hospital staff)"
    HOSPITALS ||--o{ APPOINTMENTS : "administers"
    CHILDREN ||--o{ APPOINTMENTS : "receives"
    VACCINES ||--o{ APPOINTMENTS : "specified in"

    USERS {
        bigint id PK
        string name
        string email UK
        string role "admin | hospital | parent"
        string phone
        text address
        string city
        string password
        timestamp created_at
    }

    HOSPITALS {
        bigint id PK
        bigint user_id FK
        string name
        string email
        string phone
        text address
        string city
        string location
        string contact_person
        string operating_hours
        enum status "active | inactive"
    }

    CHILDREN {
        bigint id PK
        bigint parent_id FK
        string name
        date dob
        enum gender "Male | Female | Other"
        string blood_group
        decimal birth_weight
        string birth_place
        text allergies
        text medical_history
    }

    VACCINES {
        bigint id PK
        string name
        string short_code UK
        string target_disease
        int recommended_age_days
        string recommended_age_label
        string dosage
        string route
        enum status "available | unavailable"
        text description
    }

    APPOINTMENTS {
        bigint id PK
        string booking_reference UK
        bigint parent_id FK
        bigint child_id FK
        bigint vaccine_id FK
        bigint hospital_id FK
        date appointment_date
        string appointment_time
        enum status "pending | approved | rejected | vaccinated | not_vaccinated"
        text admin_notes
        timestamp admin_action_at
        date administered_date
        string batch_number
        string administered_by
        text hospital_remarks
    }
```

---

## 6. System Architecture & Workflow State Machine

```mermaid
stateDiagram-v2
    [*] --> ParentBooking : Parent submits Appointment Request
    ParentBooking --> Pending : Status set to 'Pending Approval'
    
    state AdminReview {
        Pending --> Approved : Admin Approves Request
        Pending --> Rejected : Admin Rejects (with reason)
    }

    Rejected --> [*] : Parent notified with reason
    Approved --> HospitalQueue : Dispatched to Designated Clinic
    
    state HospitalAction {
        HospitalQueue --> Vaccinated : Hospital marks 'Vaccinated' (Batch #, Date, Nurse)
        HospitalQueue --> NotVaccinated : Child misses visit ('Not Vaccinated')
    }

    Vaccinated --> ImmunizationCertificate : Updated in Child's Permanent Record
    NotVaccinated --> MilestoneAlert : Re-flagged for Parent Rescheduling
    ImmunizationCertificate --> [*]
```

---

## 7. Automated Milestone Calculation Algorithm

The system incorporates an automated pediatric scheduling algorithm in [`ParentController`](file:///C:/Users/User/.gemini/antigravity/scratch/vaccination-management-system/app/Http/Controllers/ParentController.php):

```
Input: Child Date of Birth (DOB), Approved WHO Vaccine Registry
Output: Dynamic Dashboard Alerts (Due Now / Upcoming / Completed)

1. Calculate child's current age:
     ageInDays = CurrentDate - Child.DOB

2. Fetch all vaccines currently marked 'available'
3. Query all appointments where child_id = Child.ID and status = 'vaccinated':
     takenVaccineIDs = List of vaccine IDs already administered

4. For each vaccine V in Registry:
     If V.ID not in takenVaccineIDs:
         dueDaysDiff = V.recommended_age_days - ageInDays
         If dueDaysDiff <= 30 AND dueDaysDiff >= -120:
             Flag as "Due Now" (if dueDaysDiff < 0) or "Upcoming"
             Display alert card on Parent Dashboard with 1-click booking link
```

---

## 8. GUI Standards & Visual Architecture

- **Visual Theme**: Modern Clinical Emerald & Teal (`#10b981`, `#0d9488`, `#064e3b`) symbolizing health, life, and pediatric care.
- **Component Styling**: Clean rounded containers (`rounded-3xl`), border outlines (`border-slate-200`), subtle shadows, and responsive tables.
- **Evaluation Fast-Access**: Login page equipped with **1-Click Quick Demo Credentials** for Admin, Hospital, and Parent accounts.
- **Official Print Styling**: The child immunization card features `@media print` rules, rendering a clean certificate without headers or navigation bars.

---

## 9. Unit & Feature Testing Verification

Automated feature test suite executed via `php artisan test`:

| Test Name | Assertions | Status | Coverage |
| :--- | :---: | :---: | :--- |
| `test_public_pages_load_successfully` | 5 | **PASSED** | Home, schedule, hospital directory |
| `test_parent_can_register_and_access_dashboard` | 3 | **PASSED** | Parent onboarding & session creation |
| `test_hospital_can_register_and_access_dashboard` | 3 | **PASSED** | Hospital facility profile onboarding |
| `test_parent_can_create_child_profile` | 3 | **PASSED** | Child registration & relational mapping |
| `test_full_vaccination_appointment_workflow` | 6 | **PASSED** | Booking -> Admin Approval -> Hospital Administration |
| `test_admin_can_toggle_vaccine_availability` | 4 | **PASSED** | Available/Unavailable status toggling |
| `test_admin_can_export_vaccination_report_csv` | 4 | **PASSED** | Date-wise report streaming & CSV header |

**Final Verification Summary**: `Tests: 7 passed (28 assertions), Duration: 8.13s`

---

## 10. System Setup & Deployment Guide

### Running with SQLite (Instant Mode)
The application is pre-configured with SQLite at `database/database.sqlite`:
```powershell
# Set PHP 8.2 in session
$env:Path = "C:\wamp64\bin\php\php8.2.26;" + $env:Path

# Navigate to project
cd C:\Users\User\.gemini\antigravity\scratch\vaccination-management-system

# Start server
php artisan serve
```
Access at: **`http://127.0.0.1:8000`**

### Running with MySQL (WAMP / Production Mode)
1. In `database/mysql_schema_and_seed.sql`, a complete database dump is provided.
2. Update `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=vaccination_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run `php artisan migrate --seed` or import `mysql_schema_and_seed.sql` into phpMyAdmin / MySQL CLI.
