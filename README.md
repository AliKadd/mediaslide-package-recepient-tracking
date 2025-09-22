---

# Mediaslide Packages API

---

## Database Design Choices

1. **Packages & Versions**

    * `packages` table stores core package metadata.
    * `package_versions` ensures **per-recipient versioning**, so recipients always see the package as it was when sent.

2. **Models & Packages**

    * Models are stored in `model_profiles`.
    * Relation between models and packages is stored in `package_models`.
    * Relation between models and package versions is stored in `package_version_models` (with fields like `notes`, `shortlisted`).

3. **Recipients**

    * Recipients are stored in `recipients`.
    * Sending a package to a recipient creates a record in `package_recipients`, with a unique **secure token**.

4. **Events**

    * `recipient_events` table logs recipient actions (view, shortlist, download, comment).
    * This table is **partitioned by month** for scalability (to handle millions of events efficiently).
    * Command is created (app:create-recipient-events-partition) to be used in a monthly cron job so your table always has a partition ready.

---

## 🔑 Tokens for Recipient Access

When a package is sent:

* A **unique token** is generated in `package_recipients`.
* All recipient-facing APIs (view package, shortlist model, add comment, log event) require this token.
* This ensures:

    * No need for recipients to have system accounts.
    * Links in emails are secure & time-limited (`expires_at`).

Example email link:

```
https://example.com/api/packages/view/{token}
```

---

## 🚀 Running the Project

### 1. Clone & Install

```bash
git clone <repo_url>
cd mediaslide-packages
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

### 2. Configure Environment

* Set up your `.env` with database connection.
* Ensure you have **MySQL 8+** (required for partitioning).
* Configure mail settings if you want to queue emails.

### 3. Run Migrations & Seeder

```bash
php artisan migrate --seed
```

### 4. Start the Server

```bash
php artisan serve
```

API will be available at:

```
http://127.0.0.1:8000/api
```

## Notes

* `recipient_events` is partitioned **by month** to keep queries efficient even at **millions of rows** scale.
* Bulk inserts (`DB::table()->insert()`) are used for logging events for better performance over Eloquent in high-throughput scenarios.
* Background jobs (queues) are used for sending recipient emails with secure tokens.
* For Login:
  * User: test@example.com
  * password: test123

---
