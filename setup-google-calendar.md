# Setting up Google Calendar API for Salon Raya

This guide will walk you through setting up Google Calendar integration for Salon Raya, allowing appointments to be automatically added to your Google Calendar.

## 1. Create a Google Cloud Project

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project by clicking on the project dropdown at the top of the page and selecting "New Project"
3. Name your project (e.g., "Salon Raya") and click "Create"
4. Select your new project after creation

## 2. Enable the Google Calendar API

1. From the Google Cloud Console dashboard, navigate to "APIs & Services" > "Library"
2. Search for "Google Calendar API"
3. Click on the result and click "Enable"

## 3. Create OAuth 2.0 Credentials

1. In the Google Cloud Console, navigate to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. If prompted, configure the OAuth consent screen:
   - User Type: External
   - App name: "Salon Raya"
   - User support email: Your email address
   - Developer contact information: Your email address
   - Save and continue

4. Back in the "Create OAuth client ID" screen:
   - Application type: Web application
   - Name: "Salon Raya Web Client"
   - Authorized JavaScript origins: Add `http://localhost:8000` and your actual website domain
   - Authorized redirect URIs: Add `http://localhost:8000/google-auth.php` and `https://your-domain.com/google-auth.php`
   - Click "Create"

5. You'll receive a client ID and client secret. Save these somewhere safe.

## 4. Download Your Credentials

1. After creating your credentials, a download button will appear
2. Click the download button to download your credentials as a JSON file
3. Rename this file to `google-credentials.json`

## 5. Set Up Your Application

1. Create a `credentials` folder in your project root directory
2. Move the `google-credentials.json` file into the `credentials` folder
3. Make sure the folder permissions allow PHP to read the file
4. Install the required PHP libraries by running:
   ```
   composer require google/apiclient:^2.12
   ```

## 6. Testing the Integration

1. Navigate to the worker dashboard in your application
2. Click on the "Google Calendar" button in the header
3. Follow the authentication flow to connect your Google Calendar
4. Create a test booking to ensure it appears in your Google Calendar

## Troubleshooting

- Check the `google-auth.log` and `appointment.log` files for error messages
- Make sure the redirect URIs in your Google Cloud Console exactly match the URLs in your application
- If you're using HTTPS, ensure all URLs in your application also use HTTPS
- If you get a "redirect_uri_mismatch" error, verify that the URI in your code and in the Google Console match exactly

## Security Notes

- Keep your `google-credentials.json` file secure and never commit it to public repositories
- Use environment variables for sensitive information in production
- Regularly check for any unauthorized access in your Google Cloud Console
- Consider adding IP restrictions in the Google Cloud Console for added security 