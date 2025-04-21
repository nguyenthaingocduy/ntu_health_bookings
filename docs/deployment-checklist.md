# Deployment Checklist for NTU Health Booking System

This checklist helps ensure that all necessary steps are completed when deploying the NTU Health Booking System to production.

## Pre-Deployment Tasks

- [ ] Back up the database
- [ ] Run all tests to ensure functionality
- [ ] Check for any pending migrations
- [ ] Review and update environment variables

## Email Configuration

- [ ] Set up Gmail account with 2-Step Verification
- [ ] Generate App Password for the application
- [ ] Update `.env` file with correct SMTP settings:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=ntuhealthbooking@gmail.com
  MAIL_PASSWORD=your-16-character-app-password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="no-reply@ntu-health-booking.com"
  MAIL_FROM_NAME="${APP_NAME}"
  ```
- [ ] Clear configuration cache: `php artisan config:clear`
- [ ] Test email sending: `php artisan email:test-production your@email.com`

## Deployment Steps

1. **Update Code**
   - [ ] Pull latest code from repository
   - [ ] Install/update dependencies: `composer install --no-dev --optimize-autoloader`
   - [ ] Compile assets: `npm run build`

2. **Update Database**
   - [ ] Run migrations: `php artisan migrate --force`
   - [ ] Seed any necessary data: `php artisan db:seed --class=SpecificSeeder`

3. **Cache Configuration**
   - [ ] Clear all caches:
     ```
     php artisan config:clear
     php artisan route:clear
     php artisan view:clear
     php artisan cache:clear
     ```
   - [ ] Optimize for production:
     ```
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

4. **Final Checks**
   - [ ] Verify application is running correctly
   - [ ] Test user registration flow
   - [ ] Test appointment booking flow
   - [ ] Verify email notifications are being sent

## Post-Deployment Tasks

- [ ] Monitor application logs for any errors
- [ ] Check email delivery logs
- [ ] Verify all scheduled tasks are running correctly
- [ ] Update documentation if necessary

## Rollback Plan

In case of deployment issues:

1. Restore the database backup
2. Revert to the previous code version
3. Clear all caches
4. Verify application functionality

## Contact Information

For deployment issues, contact:
- Technical Lead: [Name] - [Email] - [Phone]
- System Administrator: [Name] - [Email] - [Phone]
