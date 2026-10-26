# Child Vaccination Management System (e-Vaccination)
### Built with Laravel 12 & Modern Full-Stack Architecture

> **Aptech eProject Standard Implementation**  
> An integrated web application engineered to eliminate missed infant vaccinations through centralized scheduling, multi-role access control, and digital immunization tracking.

---

## 🌟 System Highlights & SRS Module Coverage

| Module | Features Implemented | Route Prefix |
| :--- | :--- | :--- |
| **Admin Panel** | Central KPI analytics, All child details, Date & time of vaccination, Date-wise & child-wise report with CSV export, List of vaccines (available/unavailable toggle & CRUD), Parent request approval/rejection queue, Hospital management (Add, Update, Delete, List), Booking details | `/admin/*` |
| **Parent Portal** | Register & Login, Child profile registration & maintenance (age, blood group, allergies, weight), Automated vaccination dates alerts, Book Hospital appointment request, Report of vaccination taken (printable immunization certificate), Profile settings | `/parent/*` |
| **Hospital Portal** | Register & Login with hospital name, location and operating hours, Facility dashboard, Approved appointment queue, Update Vaccine Status (Vaccinated with lot/batch #, clinician name, remarks, or Not Vaccinated/missed), Facility profile | `/hospital/*` |
| **Public Portal** | Landing page with mission overview and statistics, Official pediatric immunization schedule guide, Searchable partner hospitals & clinics directory | `/*` |

---

## ⚡ Quick Start & Running the Application

### 1. Requirements
- **PHP**: 8.2 or higher
- **Extensions**: `pdo_sqlite`, `pdo_mysql`, `openssl`, `mbstring`, `curl`, `fileinfo`
- **Composer**: 2.x
- **Database**: SQLite (configured out-of-the-box) or MySQL

### 2. Start the Development Server
From the project folder (`C:\Users\User\.gemini\antigravity\scratch\vaccination-management-system`):

```bash
# Using PHP 8.2 from WAMP or your PATH:
$env:Path = "C:\wamp64\bin\php\php8.2.26;" + $env:Path
php artisan serve
```
Open your browser at: **`http://127.0.0.1:8000`**

---

## 🔑 Pre-Configured Demo Accounts (1-Click Login Supported)

The login screen features **1-Click Quick Fill Pills** for all 3 personas:

| Role | Email | Password | Primary Functions |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@vaccine.test` | `password123` | Approve parent requests, manage hospitals, toggle vaccine availability, export reports |
| **Hospital Clinic** | `cityhospital@vaccine.test` | `password123` | Receive approved bookings, update status to Vaccinated with lot # and remarks |
| **Parent** | `parent1@vaccine.test` | `password123` | Register children, book hospital appointments, view immunization certificates |

---

## 🗄️ Database Options

### Option A: SQLite (Default - Ready Instantly)
The application is pre-configured with SQLite at `database/database.sqlite`. It is fully migrated and seeded with demonstration records.

### Option B: MySQL (WAMP / XAMPP)
If you wish to switch to MySQL:
1. Open `.env` and set:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=vaccination_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
2. Create the database and import the ready-to-run script:
   - File: `database/mysql_schema_and_seed.sql`
   - Or run: `php artisan migrate --seed`

---

## 🧪 Automated Testing
Run the complete automated test suite verifying all SRS workflows:
```bash
php artisan test --filter=VaccinationSystemTest
```
Result: **7 passed (28 assertions)** covering authentication, child registration, appointment request, admin approval, and hospital administration.
