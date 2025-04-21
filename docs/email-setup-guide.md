# Email Setup Guide for NTU Health Booking System

This guide explains how to set up email sending for the NTU Health Booking System.

## Setting Up Gmail SMTP

The application is configured to use Gmail SMTP for sending emails. To make this work, you need to:

1. **Use a Gmail account** - We recommend using `ntuhealthbooking@gmail.com` or a similar dedicated account.

2. **Enable 2-Step Verification**:
   - Go to your [Google Account Security Settings](https://myaccount.google.com/security)
   - Scroll to "Signing in to Google" and enable "2-Step Verification"
   - Follow the prompts to set up 2-Step Verification

3. **Generate an App Password**:
   - After enabling 2-Step Verification, go back to [Security Settings](https://myaccount.google.com/security)
   - Scroll to "App passwords" (under "Signing in to Google")
   - Select "Mail" as the app and "Other" as the device (name it "NTU Health Booking")
   - Click "Generate"
   - Google will display a 16-character password - **copy this password**

4. **Update the `.env` file**:
   - Open the `.env` file in the root of the application
   - Find the `MAIL_PASSWORD` setting
   - Replace the placeholder with your 16-character App Password
   - Save the file

5. **Clear the configuration cache**:
   ```
   php artisan config:clear
   ```

6. **Test the email configuration**:
   ```
   php artisan email:test your@email.com
   ```

## Troubleshooting

If emails are not being sent:

1. **Check the logs**:
   - Look in `storage/logs/laravel.log` for any email-related errors

2. **Verify Gmail settings**:
   - Make sure "Less secure app access" is turned OFF (it's not needed with App Passwords)
   - Check that the App Password is correctly entered in the `.env` file
   - Ensure the Gmail account has not reached sending limits

3. **Test with log driver**:
   - Temporarily switch to the log driver to verify the email system is working:
     ```
     # In .env file
     MAIL_MAILER=log
     ```
   - Check the logs to see if emails are being logged correctly

4. **Check firewall settings**:
   - Ensure your server allows outgoing connections on port 587

## Alternative Email Providers

If Gmail SMTP doesn't work for your needs, consider these alternatives:

1. **Mailgun** - Good for transactional emails, offers a free tier
2. **SendGrid** - Robust email service with good deliverability
3. **Amazon SES** - Very cost-effective for high volume

To switch providers, update the corresponding settings in the `.env` file.
