# Production Deployment Guide for NTU Health Booking System

This guide provides detailed instructions for deploying the NTU Health Booking System to a production environment.

## Prerequisites

- A server with PHP 8.1+ installed
- Composer installed on the server
- Node.js and npm installed on the server
- MySQL or MariaDB database server
- Web server (Apache or Nginx)
- SSH access to the server

## Step 1: Prepare the Server

1. **Update the server packages**:
   ```bash
   sudo apt update
   sudo apt upgrade -y
   ```

2. **Install required packages**:
   ```bash
   sudo apt install -y git unzip curl
   ```

3. **Configure the web server**:
   - For Apache, create a virtual host configuration
   - For Nginx, create a server block configuration
   - Enable HTTPS with Let's Encrypt

## Step 2: Clone the Repository

1. **Navigate to the web directory**:
   ```bash
   cd /var/www
   ```

2. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/ntu-health-booking.git
   cd ntu-health-booking
   ```

## Step 3: Configure the Environment

1. **Create the production environment file**:
   ```bash
   cp .env.production .env
   ```

2. **Edit the environment file**:
   ```bash
   nano .env
   ```
   
   Update the following settings:
   - `APP_URL`: Set to your domain name
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Set to your database credentials
   - `MAIL_USERNAME`, `MAIL_PASSWORD`: Set to your Gmail credentials

3. **Generate the application key** (if not already set):
   ```bash
   php artisan key:generate
   ```

## Step 4: Set Up Gmail for Email Sending

1. **Use a dedicated Gmail account** (e.g., `ntuhealthbooking@gmail.com`)

2. **Enable 2-Step Verification**:
   - Go to [Google Account Security Settings](https://myaccount.google.com/security)
   - Scroll to "Signing in to Google" and enable "2-Step Verification"
   - Follow the prompts to set up 2-Step Verification

3. **Generate an App Password**:
   - After enabling 2-Step Verification, go back to [Security Settings](https://myaccount.google.com/security)
   - Scroll to "App passwords" (under "Signing in to Google")
   - Select "Mail" as the app and "Other" as the device (name it "NTU Health Booking")
   - Click "Generate"
   - Google will display a 16-character password - **copy this password**

4. **Update the `.env` file**:
   - Find the `MAIL_PASSWORD` setting
   - Replace the placeholder with your 16-character App Password
   - Save the file

## Step 5: Install Dependencies and Build Assets

1. **Install PHP dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Install Node.js dependencies and build assets**:
   ```bash
   npm ci
   npm run build
   ```

## Step 6: Set Up the Database

1. **Run database migrations**:
   ```bash
   php artisan migrate --force
   ```

2. **Seed the database** (if needed):
   ```bash
   php artisan db:seed --class=ProductionSeeder
   ```

## Step 7: Configure File Permissions

1. **Set the correct permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/ntu-health-booking
   sudo find /var/www/ntu-health-booking -type f -exec chmod 644 {} \;
   sudo find /var/www/ntu-health-booking -type d -exec chmod 755 {} \;
   sudo chmod -R 775 /var/www/ntu-health-booking/storage
   sudo chmod -R 775 /var/www/ntu-health-booking/bootstrap/cache
   ```

## Step 8: Optimize for Production

1. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

2. **Optimize for production**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Step 9: Test Email Sending

1. **Run the email test command**:
   ```bash
   php artisan email:test-production your@email.com
   ```

2. **Check if the email was received**

## Step 10: Set Up Scheduled Tasks

1. **Add the Laravel scheduler to crontab**:
   ```bash
   crontab -e
   ```

2. **Add the following line**:
   ```
   * * * * * cd /var/www/ntu-health-booking && php artisan schedule:run >> /dev/null 2>&1
   ```

## Step 11: Set Up Queue Worker (Optional)

If you're using queues for email sending:

1. **Install Supervisor**:
   ```bash
   sudo apt install supervisor
   ```

2. **Create a configuration file**:
   ```bash
   sudo nano /etc/supervisor/conf.d/ntu-health-booking-worker.conf
   ```

3. **Add the following configuration**:
   ```
   [program:ntu-health-booking-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/ntu-health-booking/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/www/ntu-health-booking/storage/logs/worker.log
   ```

4. **Update Supervisor**:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start all
   ```

## Troubleshooting

### Email Issues

If emails are not being sent:

1. **Check the logs**:
   ```bash
   tail -f /var/www/ntu-health-booking/storage/logs/laravel.log
   ```

2. **Verify Gmail settings**:
   - Make sure 2-Step Verification is enabled
   - Verify that the App Password is correct
   - Check that the Gmail account has not reached sending limits

3. **Test with the log driver**:
   - Temporarily switch to the log driver to verify the email system is working:
     ```
     # In .env file
     MAIL_MAILER=log
     ```
   - Check the logs to see if emails are being logged correctly

### Database Issues

If you encounter database issues:

1. **Check the database connection**:
   ```bash
   php artisan db:monitor
   ```

2. **Verify database credentials**:
   - Make sure the database exists
   - Verify that the username and password are correct
   - Check that the database user has the necessary permissions

### Server Issues

If you encounter server issues:

1. **Check the web server logs**:
   - Apache: `/var/log/apache2/error.log`
   - Nginx: `/var/log/nginx/error.log`

2. **Check PHP logs**:
   - `/var/log/php8.1-fpm.log` (adjust version as needed)

3. **Verify file permissions**:
   - Make sure the web server has read/write access to the necessary directories

## Maintenance

### Regular Updates

1. **Pull the latest code**:
   ```bash
   cd /var/www/ntu-health-booking
   git pull
   ```

2. **Update dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```

3. **Run migrations**:
   ```bash
   php artisan migrate --force
   ```

4. **Clear caches**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

5. **Optimize for production**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Backup Strategy

1. **Database backups**:
   ```bash
   php artisan backup:run
   ```

2. **Automate backups**:
   - Add the backup command to the Laravel scheduler
   - Configure offsite backup storage (e.g., AWS S3)

## Contact Information

For deployment issues, contact:
- Technical Lead: [Name] - [Email] - [Phone]
- System Administrator: [Name] - [Email] - [Phone]
