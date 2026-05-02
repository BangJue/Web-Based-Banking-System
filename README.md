<div align="center">

<img src="https://via.placeholder.com/1000x250/0f172a/3b82f6?text=INDONESIA+NATIONAL+BANK+(INB)" alt="INB Banner" width="100%">

# 🏦 Indonesia National Bank (INB)
**Next-Generation Core Banking System & Financial Management Platform**

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

*A secure, high-performance, and visually stunning banking application designed with strict financial audit trails and an ultra-modern glassmorphism UI.*

</div>

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Design Philosophy & UI/UX](#-design-philosophy--uiux)
- [Comprehensive Features](#-comprehensive-features)
- [Database Schema & Audit Trails](#-database-schema--audit-trails)
- [Tech Stack Breakdown](#-tech-stack-breakdown)
- [Installation & Setup Guide](#️-installation--setup-guide)
- [Security Implementations](#-security-implementations)
- [Author & Credits](#-author--credits)

---

## 🔭 Overview

**Indonesia National Bank (INB)** is not just a CRUD application; it is a meticulously engineered core banking simulation. Built primarily for research, PKM-KC (Student Creativity Program) innovation, and advanced portfolio demonstration, it bridges the gap between complex financial backend logic and a seamless, premium frontend experience.

The system is capable of handling multi-account management, strict transactional auditing (maintaining state before and after balance mutations), loan processing, and dynamic financial data visualization.

---

## 🎨 Design Philosophy & UI/UX

INB adopts a **Modern High-Tech** aesthetic, moving away from traditional, boring banking interfaces. 

* 🌌 **Deep Dark Mode:** Utilizes slate and true black backgrounds (`#0f172a`, `#000000`) to reduce eye strain and highlight vibrant data.
* 💎 **Glassmorphism Elements:** Heavy use of `backdrop-blur`, semi-transparent white/blue layers (`bg-white/5`), and soft glowing shadows (`shadow-blue-500/40`) to create a sense of depth.
* ⭕ **Extreme Radius:** Components feature high rounded corners (`rounded-[2rem]`) for a soft, friendly, and modern tactile feel.
* 📊 **Interactive Visualizations:** Real-time Chart.js integration mapping out 7-day credit vs. debit trajectories seamlessly.

---

## 🚀 Comprehensive Features

<details>
<summary><b>👤 1. Customer (User) Features</b> <i>(Click to expand)</i></summary>

* **Multi-Account Ecosystem:** Users can possess multiple active savings accounts under a single profile.
* **Dynamic Financial Dashboard:** * Real-time aggregated total balance.
    * Interactive Line Charts comparing Income vs. Outcome over customizable timeframes.
    * Automated Monthly Outcome calculation based on debit/withdrawal activities.
* **Fund Transfers:** Secure intra-bank transfer capabilities with instant ledger updates.
* **Bill Payments Integration:** Pay electricity, internet, and water bills. Logic includes robust status checking and receipt generation.
* **Loan Management:** Apply for loans, view active/overdue loans, and track payment schedules directly from the dashboard.
* **Transaction Auditing:** Every mutation displays a clear visual indicator (Red arrow down for outcome, Green arrow up for income) with unique `TRX-` reference codes.
</details>

<details>
<summary><b>🛡️ 2. Administrator Features</b> <i>(Click to expand)</i></summary>

* **Global Overview:** Dashboard displaying total platform liquidity, total active users, and system-wide transaction volume.
* **User & Account Moderation:** Ability to block suspicious accounts or verify new user identities.
* **Loan Underwriting:** Interface to approve, reject, or request revisions on pending customer loan applications.
* **System Analytics:** Daily transaction volume charts and distribution of transaction types (Transfer vs. Bills vs. Withdrawals).
</details>

---

## 🗄 Database Schema & Audit Trails

INB employs enterprise-grade database discipline to prevent data truncation and ensure financial accuracy.

### The `transactions` Table (Core Ledger)
To prevent "Ghost Money" and ensure auditability, the transaction logic requires strict state tracking:
* `type`: Handled via strict string validation or ENUMs (`deposit`, `withdrawal`, `transfer`, `bills payment`, `loan_payment`).
* `amount`: The exact mutated value.
* `balance_before`: **CRITICAL AUDIT FIELD.** Captures the exact account balance *before* the math operation.
* `balance_after`: Captures the balance *after* the operation.
* `status`: Transaction state (`pending`, `success`, `failed`).

*Database operations are wrapped in `DB::transaction()` closures. If a bill payment cuts the balance but fails to generate a receipt, the entire operation rolls back automatically.*

---

## 🛠 Tech Stack Breakdown

### Backend Layer
* **Framework:** Laravel 11
* **Language:** PHP 8.3
* **Database:** MySQL 8.0+
* **Authentication:** Laravel Breeze / Built-in Auth
* **ORM:** Eloquent with Eager Loading optimization

### Frontend Layer
* **Styling Engine:** Tailwind CSS 3.4
* **Templating:** Blade Engine
* **Iconography:** FontAwesome 6 Free/Pro
* **Data Visualization:** Chart.js
* **Asset Bundler:** Vite

---

## ⚙️ Installation & Setup Guide

Follow these steps to deploy Indonesia National Bank (INB) on your local machine for development or testing.

**1. Clone the Repository**
```bash
git clone [https://github.com/yourusername/inb.git](https://github.com/yourusername/inb.git)
cd inb
```

**2. Install Dependencies & Compile Assets**
```bash
# Install PHP backend dependencies
composer install
# Install and compile frontend assets
npm install
npm run build
```

**3. Setup Environment**
```bash
composer install
npm install

Edit file .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=example
DB_USERNAME=root
DB_PASSWORD=
```

**4. Run Application**
```bash
php artisan serve
npm run dev
```
