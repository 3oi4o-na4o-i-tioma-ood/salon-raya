# The Barber Shop Website

A modern, responsive website for a barber shop with an online booking system. Built with vanilla JavaScript and PHP.

## Features

- Responsive design
- Online booking system
- Email confirmation
- Modern UI/UX
- Mobile-friendly navigation

## Requirements

- PHP 7.4 or higher
- PostgreSQL 12 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone the repository to your web server directory:
```bash
git clone https://github.com/yourusername/barbershop.git
```

2. Create a PostgreSQL database and import the database structure:
```bash
psql -U postgres -f database.sql
```

3. Update the database configuration in `process_booking.php`:
```php
$host = 'localhost';
$dbname = 'barbershop';
$username = 'postgres';
$password = '1111';
$port = '5432';
```

4. Configure your web server to point to the project directory.

5. Make sure the following directories have write permissions:
```bash
chmod 755 images/
```

## File Structure

```
├── index.php           # Main landing page
├── booking.php         # Booking form page
├── process_booking.php # Booking form handler
├── database.sql        # Database structure
├── css/
│   ├── style.css      # Main styles
│   └── booking.css    # Booking form styles
├── js/
│   └── main.js        # JavaScript functionality
└── images/            # Image assets
```

## Usage

1. Visit the website through your web browser
2. Navigate to the booking page
3. Fill out the booking form
4. Receive confirmation email

## Customization

- Update the color scheme in `css/style.css`
- Modify booking form fields in `booking.php`
- Customize email template in `process_booking.php`

## Security Considerations

- Update database credentials
- Implement HTTPS
- Add CSRF protection
- Sanitize user inputs (already implemented)
- Use environment variables for sensitive data

## License

MIT License 