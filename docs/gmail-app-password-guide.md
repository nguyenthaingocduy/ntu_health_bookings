# Setting Up Gmail App Password for NTU Health Booking System

This guide will walk you through the process of setting up a Gmail App Password for use with the NTU Health Booking System.

## What is an App Password?

An App Password is a 16-character code that gives a less secure app or device permission to access your Google Account. App Passwords can only be used with accounts that have 2-Step Verification turned on.

## Step 1: Enable 2-Step Verification

1. Go to your [Google Account](https://myaccount.google.com/).
2. Select **Security** from the left navigation panel.
3. Under "Signing in to Google," select **2-Step Verification**.
4. Click **Get started** and follow the on-screen steps.
5. Complete the verification process to enable 2-Step Verification.

## Step 2: Create an App Password

1. Go to your [Google Account](https://myaccount.google.com/).
2. Select **Security** from the left navigation panel.
3. Under "Signing in to Google," select **App passwords**.
   - Note: If you don't see this option, it might be because:
     - 2-Step Verification is not set up for your account.
     - 2-Step Verification is set up for security keys only.
     - Your account is through work, school, or other organization.
     - Advanced Protection is turned on.
4. At the bottom, click **Select app** and choose **Mail**.
5. Click **Select device** and choose **Other (Custom name)**.
6. Enter "NTU Health Booking" as the name.
7. Click **Generate**.
8. The App Password will be displayed. **Copy this password** (the 16-character code).
9. Click **Done**.

## Step 3: Update the .env File

1. Open the `.env` file in the root of your application.
2. Find the `MAIL_PASSWORD` setting.
3. Replace the placeholder with your 16-character App Password:
   ```
   MAIL_PASSWORD=your16charpassword
   ```
   - Note: Enter the password without spaces, even though Google displays it with spaces.
4. Save the file.

## Step 4: Clear Configuration Cache

Run the following command to clear the configuration cache:

```bash
php artisan config:clear
```

## Step 5: Test Email Sending

Run the following command to test email sending:

```bash
php artisan email:test-production your@email.com
```

## Troubleshooting

If you encounter issues:

1. **Verify App Password**: Make sure you've copied the App Password correctly.
2. **Check Gmail Settings**: Ensure that "Less secure app access" is turned OFF (it's not needed with App Passwords).
3. **Check Logs**: Look in `storage/logs/laravel.log` for any email-related errors.
4. **Try Another Gmail Account**: If problems persist, try setting up App Passwords with another Gmail account.

## Security Notes

- Keep your App Password secure. Anyone with your App Password can access your Google Account.
- If you suspect your App Password has been compromised, you can revoke it at any time from your Google Account security settings.
- App Passwords are meant for applications that don't support OAuth, which is a more secure authentication method.
