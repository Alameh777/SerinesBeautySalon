# 💇‍♀️ Beauty Salon Management System

A full-featured web application built with **Laravel** for managing salon bookings, services, employees, and clients — designed to simplify daily operations for beauty salons.

---

## 🌟 Features

- 🧾 **Appointment Booking:** Clients can book, update, and cancel appointments.
- 💆 **Employee Management:** Add, edit, and assign staff to appointments.
- 💅 **Service Management:** Manage salon services with customizable prices and durations.
- 🪞 **Dashboard:** Admin panel to monitor all bookings, clients, and activities.
- 💳 **Payment Tracking:** Keep track of completed and pending payments.
- 🔐 **User Authentication:** Secure login and role-based access (Admin / Employee).

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-------------|
| Backend | Laravel 10+ |
| Frontend | Blade Templates, Bootstrap 5 |
| Database | MySQL |
| Server | Apache (XAMPP / Laragon / Localhost) |
| Version Control | Git + GitHub |

---

## ⚙️ Installation & Setup

### 1️⃣ Clone the repository
```bash
git clone https://github.com/yourusername/BeautySalon.git
cd BeautySalon


2️⃣ Install dependencies
composer install
npm install


3️⃣ Configure the environment

Copy .env.example to .env:

cp .env.example .env

Then update your database details:

DB_DATABASE=beauty_salon
DB_USERNAME=root
DB_PASSWORD=

4️⃣ Generate app key
php artisan key:generate


5️⃣ Run migrations
php artisan migrate

6️⃣ Start the development server
php artisan serve
Now visit http://127.0.0.1:8000

Author

Moe Alameh
📧 mohammadalameh376@gmail.com
