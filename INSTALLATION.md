# LifeFlow Installation

## Requirements
- PHP 8.2+
- Composer
- MySQL
- Node 22+ and npm

## Setup
1. Copy `.env.example` to `.env`.
2. Set database values in `.env`, for example:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=lifeflow`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
3. Install PHP dependencies:
   ```bash
   composer install
   ```
4. Generate the app key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start the app:
   ```bash
   php artisan serve
   ```

On Windows PowerShell, use `npm.cmd` instead of `npm` if script execution is blocked. This build uses CDN Bootstrap and Font Awesome, so no frontend build is required for the current UI.

## Firebase Push Notifications
Add these values to `.env` from your Firebase project:

```env
FIREBASE_API_KEY=
FIREBASE_AUTH_DOMAIN=
FIREBASE_PROJECT_ID=
FIREBASE_MESSAGING_SENDER_ID=
FIREBASE_APP_ID=
FIREBASE_VAPID_KEY=
FIREBASE_SERVER_KEY=
```

Then replace the placeholder values in `public/firebase-messaging-sw.js` with the same Firebase web app values. Run the scheduler in production so reminder notifications are checked every minute:

```bash
php artisan schedule:work
```

## Main Screens
- `/dashboard`
- `/tasks`
- `/notes`
- `/money`
- `/money/history`
- `/reminders`
- `/assistant`
- `/profile`
- `/settings`
- `/install`
