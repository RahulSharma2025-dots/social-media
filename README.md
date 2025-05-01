# Social Media Application

A modern social media platform built with Laravel, featuring user interactions, content sharing, and admin management.

## Features

### User Features
- User authentication and registration
- Profile management with customizable avatars
- Post creation with text, images, and videos
- Multiple media upload support (up to 10 files per post)
- Like and comment functionality
- Follow/unfollow users
- Real-time notifications
- Direct messaging system
- Bookmark posts
- Explore page for discovering content
- User search functionality

### Content Management
- Create posts with rich text
- Upload multiple images and videos
- Media preview before posting
- Post editing and deletion
- Media slider for viewing multiple images/videos
- Content categorization with topics

### Live Sessions
- Create and join live sessions
- One-on-one session booking
- Session management
- Real-time interaction

### Wallet System
- Deposit and withdraw funds
- Transaction history
- Secure payment processing

### Admin Panel
- Secure admin authentication
- User management
- Content moderation
- Influencer verification
- User banning/unbanning
- Analytics dashboard

## Technical Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: Blade Templates, JavaScript, CSS
- **Database**: MySQL
- **Authentication**: Laravel Auth with multiple guards
- **File Storage**: Local/Cloud storage for media files
- **Real-time Features**: WebSockets/Laravel Echo

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database:
```bash
php artisan migrate
php artisan db:seed
```

5. Start the development server:
```bash
php artisan serve
```

## Configuration

### Environment Variables
- Database configuration
- Mail settings
- File storage settings
- Payment gateway credentials

### Admin Setup
1. Create admin user through seeder or manually
2. Access admin panel at `/admin`
3. Configure admin settings

## Usage

### User Access
- Register/Login at `/login`
- Access dashboard at `/home`
- Create posts, follow users, and interact with content

### Admin Access
- Login at `/admin/login`
- Access dashboard at `/admin/dashboard`
- Manage users and content

## Security Features

- CSRF protection
- XSS prevention
- Input validation
- Secure file uploads
- Role-based access control
- Session management
- Rate limiting

## File Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   └── Auth/
│   │   └── Middleware/
│   └── Models/
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── auth/
│   │   └── feed/
│   └── assets/
├── routes/
│   ├── web.php
│   └── api.php
└── public/
    └── storage/
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, email [support@example.com] or create an issue in the repository.
