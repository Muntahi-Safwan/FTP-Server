# FTP Server

A web-based content management system with role-based access control. Built with vanilla PHP, MySQL, and modern CSS.

## Features

- **Member Portal** — Browse and download categorized media content.
- **Moderator Panel** — Upload content, manage all uploads, and respond to member requests.
- **Admin Panel** — Full system control including user management, content moderation, and request handling.
- **Content Requests** — Members can request specific content; moderators and admins can fulfill or reject them.
- **AJAX-First** — Subcategory loading, uploads, deletions, and status updates use XMLHttpRequest for a seamless experience.

## Tech Stack

- PHP 8+
- MySQL
- Vanilla JavaScript (XMLHttpRequest)
- CSS Custom Properties (dark theme)

## Project Structure

```
public/              # Web root — entry point and assets
src/
  config/            # Database configuration
  controller/        # Business logic handlers
  includes/          # Auth guards, CSRF protection
  model/             # Data access layer
  view/              # Templates (admin, moderator, public)
    layouts/         # Shared sidebar components
docs/                # ER diagram and database schema
```

## Setup

1. Import the database schema from `docs/db.sql`.
2. Update `src/config/db.php` with your database credentials.
3. Ensure the web server points to the `public/` directory.
4. Make `public/uploads/` writable for file storage.

## Roles

| Role       | Capabilities                                      |
|------------|---------------------------------------------------|
| Member     | Browse, search, download, request content         |
| Moderator  | Upload, delete any content, manage requests       |
| Admin      | All moderator features + user and system management |

## Security

- CSRF tokens on all state-changing forms and AJAX requests.
- Role-based access checks on every protected view and controller.
- File extension and size validation on uploads.
- Uploaded files stored outside web-accessible PHP execution scope.

## License

This project is for academic purposes.
