# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Development Commands

### Laravel/PHP Commands
- **Development server**: `composer dev` (runs server, queue worker, logs, and Vite concurrently)
- **Build assets**: `npm run build` 
- **Development assets**: `npm run dev`
- **Run tests**: `composer test` (clears config and runs PHPUnit)
- **Code style/linting**: `./vendor/bin/pint` (Laravel Pint for PHP formatting)
- **Database operations**: `php artisan migrate`, `php artisan db:seed`
- **Queue processing**: `php artisan queue:work` (separate from dev command for production)
- **Application logs**: `php artisan pail --timeout=0`

### Docker/Services
- **Start all services**: `docker compose up -d`
- **Laravel application**: Available on port 80 (configurable via APP_PORT)
- **MySQL**: Port 3306 (FORWARD_DB_PORT)
- **Redis**: Port 6379 (FORWARD_REDIS_PORT) 
- **Mailpit**: Port 8025 for dashboard, 1025 for SMTP
- **MinIO**: Port 9000 for API, 8900 for console
- **n8n workflow**: Port 5678
- **PgVector database**: Port 5435

### Video Transcription
- **Transcribe videos**: `python3 transcribe_videos.py` (requires Whisper installation)
- Videos stored in: `storage/app/private/videos/`
- Transcriptions output: `storage/app/private/transcriptions/`

## Project Architecture

### Core Domain Models
The application is built around an educational content hierarchy:
- **Products** → **Tracks** → **Paths** → **Modules** → **Lessons**
- **Users** enroll in **Products** and track progress through **LessonStatus**
- **Comments** and **Ratings** are tied to lessons for student engagement
- **Messages** support AI chat functionality per lesson

### Key Integrations
- **Panda Video**: Video hosting service integrated via PandaServices for lesson content
- **Eduzz**: E-commerce platform integration for product management
- **AI Chat Agent**: Livewire-powered chat system with external AI service integration
- **n8n**: Workflow automation service running in Docker
- **Filament**: Admin panel framework (v4.0) for content management

### Database Architecture
- **Pivot tables**: `product_track`, `product_track_path`, `product_user` manage complex many-to-many relationships
- **Soft deletes**: Enabled on main content entities (Products, Tracks, Paths, Modules)
- **Progress tracking**: `lesson_statuses` table tracks user completion and timestamps
- **Content hierarchy**: Enforced through foreign keys with positional ordering

### Frontend Stack  
- **Laravel Livewire**: For interactive components (ChatAgent, GlobalChat)
- **Tailwind CSS v4**: For styling with Vite plugin
- **Vite**: Asset building and hot reloading
- **Custom components**: Panda video player integration in JavaScript

### File Storage & Processing
- **Private storage**: Videos and transcriptions stored in `storage/app/private/`
- **AWS S3**: Configured for file storage (via League Flysystem)
- **Video transcription**: Python script using Whisper for automated transcriptions
- **Caching**: PandaServices caches video metadata indefinitely

### Testing Strategy
- **PHPUnit**: Configured for Unit and Feature tests
- **Test database**: Uses separate SQLite database for testing
- **Environment isolation**: Testing environment variables configured in phpunit.xml

## Important Configuration

### Required Environment Variables
- `PANDA_VIDEO_API_KEY`: For video service integration
- `EDUZZ_SECRET_KEY`: For e-commerce platform integration
- Database credentials for MySQL
- AWS credentials for S3 storage

### Custom Artisan Commands
The application may have custom commands - check `routes/console.php` and `app/Console/Commands/`

### Queue Configuration
- Uses database queue driver by default
- Job batches supported for complex background processing
- Queue workers should be monitored in production

### Multi-language Support
- Uses `laravellegends/pt-br-validator` for Brazilian Portuguese validation
- Application supports localization (APP_LOCALE, APP_FALLBACK_LOCALE)

## Development Notes

### Model Relationships
Pay attention to the complex pivot table relationships when working with enrollment and progress tracking. The `ProductTrackPath` model serves as a bridge for the hierarchical content structure.

### Video Integration  
When working with lessons, video data comes from Panda Video service. Use `PandaServices::getVideoDetails()` for video metadata and caching is handled automatically.

### Livewire Components
The chat system is built with Livewire - be mindful of component lifecycle and real-time updates when modifying chat functionality.

### Database Seeding
Default admin user: `admin@teste.com` / `password`
Roles are seeded: `admin`, `Member`