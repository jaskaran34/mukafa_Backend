# Reward Loyalty Installation Guide

## Introduction

**Reward Loyalty** is a cutting-edge digital savings card solution, ideal for businesses aiming to enhance customer loyalty. This versatile tool is perfect for both individual retailers and multi-retailer setups, making it an invaluable resource for marketing and digital agencies. Think of it as a modern treasure hunt that rewards customer loyalty through unique QR codes linked to digital loyalty cards.

Visit our [online documentation](https://nowsquare.com/en-us/reward-loyalty/docs/introduction) for more information.

## Technology Stack

This project employs the following frameworks and technologies:

### Backend

- **PHP**: Version 8.2.0 or higher
- **Framework**: Laravel (Version 11.x)
- **Supported Databases**:
  - **SQLite**: Version 3.9 or above
  - **MySQL**: Version 5.7 or above
  - **MariaDB**: Version 10.3 or above

### Frontend

- **CSS Framework**: Tailwind CSS (Version 3.x)
- **Components**: Flowbite (Version 1.x) - A component library for Tailwind CSS
- **UI Kit**: Tailwind Elements (Version 1.x) - Open-source UI components

### Tooling

- **Packaging**: NPM Vite - Used for bundling JavaScript and CSS

For a detailed list of PHP libraries, refer to the `composer.json` file located in the root directory. For JavaScript libraries, please check the `package.json` file.

## Prerequisites

Before proceeding with the installation, please ensure you meet the following requirements:

### Core Requirements

- **PHP Version**: 8.2.0 or higher
- **Web Server**: Apache
- **Supported Databases**:
  - SQLite (Version 3.9 or above)
  - MySQL (Version 5.7 or above)
  - MariaDB (Version 10.3 or above)

### Essential PHP Extensions

Ensure the following PHP extensions are installed and activated on your server. Most hosting providers offer these extensions pre-installed, and the installation process will also check for their presence:

- Bcmath (`ext-bcmath`)
- Ctype (`ext-ctype`)
- cURL (`ext-curl`)
- DOM (`ext-dom`)
- Exif (`ext-exif`)
- Fileinfo (`ext-fileinfo`)
- Filter (`ext-filter`)
- GD (`ext-gd`)
- Hash (`ext-hash`)
- Iconv (`ext-iconv`)
- Intl (`ext-intl`)
- JSON (`ext-json`)
- Libxml (`ext-libxml`)
- Mbstring (`ext-mbstring`)
- OpenSSL (`ext-openssl`)
- PCRE (`ext-pcre`)
- PDO (`ext-pdo`)
- PDO SQLite (`ext-pdo_sqlite`)
- Session (`ext-session`)
- Tokenizer (`ext-tokenizer`)
- XML (`ext-xml`)
- Zlib (`ext-zlib`)

### Note On Shared Hosting

If you are using shared hosting, ensure you can enable the PHP functions `proc_open` and `proc_close`. These functions are essential and safe to use with our software, despite common misconceptions about their security risks.

## Installation Process

1. **Upload Files**: Extract all files from the `public_html` directory located inside the zip file and transfer them to your website's root directory.
2. **Access the URL**: Navigate to the URL where you've uploaded the files to encounter the installation screen.
3. **Follow On-Screen Instructions**: Complete the steps as prompted to install the script.

**Important:** Once installed, log in using the admin credentials at `example.com/en-us/admin`. As an admin, you'll have the capability to create partners, allowing them to generate loyalty cards and rewards. Do not install the script in a subdirectory like `example.com/loyalty`. Instead, use a subdomain, e.g., `loyalty.example.com`.

### Localhost Installation

For local environment setups, utilize Laravel's built-in `artisan serve` command:

```sh
php artisan serve
```

## Upgrading

### Check Your Current Version

To determine your current version, sign in as an admin at `example.com/en-us/admin`. The version number is displayed on the dashboard. You can also refer to the `version.txt` file included in the provided zip file.

### Upgrade Procedure

1. **Locate the Upgrade Files**: In the provided zip file, navigate to the `upgrade` directory. Here, you'll find zip files named in the format `upgrade-x.x.x-to-[version].zip`.
2. **Determine the Correct File**: Identify the upgrade file that matches your current script version. If your script's version is older than the one indicated in the zip filename, you're eligible for the upgrade.
3. **Extract and Overwrite**: Unzip the contents of the appropriate `upgrade-x.x.x-to-[version].zip` and overwrite the existing files in your script's web root directory.

**Example:** If you have version `1.2.0` and aim to upgrade to `1.6.1`, start with `upgrade-1.x.x-to-1.6.0.zip`, then `upgrade-1.6.x-to-1.6.1.zip`. If you're already on version `1.6.0`, only extract `upgrade-1.6.x-to-1.6.1.zip`.

### Database Update

1. **Log In**: After updating the files, log in as an admin at `example.com/en-us/admin`.
2. **Look for Update Prompt**: If your database requires an update, a message will show: "An update is required for your database. Click here to apply the update." Click on this prompt to carry out the necessary database updates.

Always check for database synchronization after every upgrade. Non-updated database migrations could lead to functional issues or errors.

### Troubleshooting the 500 Error

If you face a 500 error after clicking on the database update link:

1. **Check the Log**: Refer to the `storage/logs/laravel.log` for detailed error information.
2. **Review PHP FPM Settings**: Within the PHP directives, find `disable_functions`. This directive lists all deactivated PHP functions on your server.
3. **Adjust Settings**: If `proc_open` and `proc_close` are listed, remove them. Save the changes, which might resolve the 500 error.

## Troubleshooting

Ensure to review the log file located at `logs/laravel.log`.

## Conclusion

If you encounter any issues or have specific questions, consult our [Support Page](https://nowsquare.com/en-us/reward-loyalty/support) for assistance.