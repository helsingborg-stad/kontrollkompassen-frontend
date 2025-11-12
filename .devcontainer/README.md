# Kontrollkompassen Dev Container

This directory contains the development container configuration for the Kontrollkompassen frontend application.

## Quick Start

### Prerequisites

- Docker and Docker Compose installed
- VS Code with the "Dev Containers" extension
- GitHub Personal Access Token (PAT) with `read:packages` scope for private npm packages

### Setup Steps

1. **Clone the repository** (if you haven't already):

   ```bash
   git clone https://github.com/helsingborg-stad/kontrollkompassen-frontend.git
   cd kontrollkompassen-frontend
   ```

2. **Create environment file**:

   ```bash
   cp .devcontainer/.env.example .devcontainer/.env
   ```

3. **Configure your GitHub token**:
   Edit `.devcontainer/.env` and add your GitHub Personal Access Token:

   ```
   GITHUB_TOKEN=ghp_xxxxxxxxxxxx
   ```

4. **Open in Dev Container**:
   - Open the workspace in VS Code
   - Click the "Dev Containers" icon in the bottom-left corner (or use Command Palette)
   - Select "Reopen in Container"

The container will automatically:

- Install PHP dependencies (Composer)
- Install Node dependencies (npm)
- Configure GitHub authentication for private packages
- Set up development tools

## Development Workflow

### Available Commands

Run these commands from `/workspace/frontend/app`:

```bash
# Install dependencies
composer install      # PHP dependencies
npm install          # Node dependencies

# Build assets
npm run build         # Production build
npm run build:dev     # Development build
npm run watch        # Watch mode for continuous rebuilds
npm run tsc:watch    # TypeScript watch mode

# Run tests
npm test             # Run all tests (PHP)
```

### Starting the Development Server

```bash
cd frontend/app
php -S 0.0.0.0:8001 index.php
```

Then open your browser to `http://localhost:8001`

The built-in server will:

- Route all requests through the Slim application
- Serve static assets directly from disk
- Provide a fast development experience

### Accessing from Host Machine

The dev container forwards port **8001** to your host machine:

- Container: `http://0.0.0.0:8001`
- Host: `http://localhost:8001`

## Included Tools & Extensions

### VS Code Extensions

- **Intelephense** - PHP language support and intellisense
- **Prettier** - Code formatter
- **ESLint** - JavaScript linting
- **GitLens** - Git integration
- **Laravel Blade** - Blade template syntax highlighting
- **PHP Sniffer & Beautifier** - PHP code quality

### Development Tools

- **PHP 8.2+** with Xdebug (debug mode off by default)
- **Node.js & npm** - JavaScript tooling
- **Composer** - PHP package manager
- **Git** - Version control
- **Vite** - Frontend build tool

## Environment Configuration

### .env File

Create `.devcontainer/.env` with the following variables:

```env
# Required: GitHub Personal Access Token (for private npm packages)
# Scope: read:packages
GITHUB_TOKEN=ghp_xxxxxxxxxxxx
```

**Note**: The `.env` file is gitignored for security. Never commit tokens to the repository.

## Project Structure

```
frontend/
├── app/                           # Main application
│   ├── index.php                 # Entry point
│   ├── config_slim/              # Slim framework configuration
│   │   ├── bootstrap.php
│   │   ├── container.php
│   │   ├── middleware.php
│   │   └── routes.php
│   ├── source/
│   │   ├── php/                  # PHP backend code
│   │   │   ├── Action/           # Request handlers
│   │   │   ├── Services/         # Business logic
│   │   │   ├── Models/           # Data models
│   │   │   ├── Helper/           # Utility functions
│   │   │   └── Interfaces/       # Contracts
│   │   ├── ts/                   # TypeScript source
│   │   ├── sass/                 # SASS stylesheets
│   │   └── js/                   # Legacy JavaScript
│   ├── views/                    # Blade templates
│   ├── tests/                    # PHP unit tests
│   ├── composer.json
│   ├── package.json
│   ├── tsconfig.json
│   ├── vite.config.mjs           # Vite configuration
│   └── postcss.config.cjs
└── config.json                   # Application configuration
```

## Troubleshooting

### "GITHUB_TOKEN is not set" Error

**Problem**: Container fails to start with token warning.

**Solution**:

1. Create `.devcontainer/.env` file
2. Add your GitHub PAT: `GITHUB_TOKEN=ghp_xxxxxxxxxxxx`
3. Restart the container

### Blade Templates Not Updating

**Problem**: Template changes don't appear in the browser.

**Solution**: The Blade cache is stored in `/tmp/cache/`. The container will clear it on startup. If you still see cached content:

```bash
rm -rf /tmp/cache/*
```

### Port 8001 Already in Use

**Problem**: `php -S` fails because port 8001 is already bound.

**Solution**:

```bash
# Find and kill the process using port 8001
sudo lsof -i :8001
sudo kill -9 <PID>

# Or use a different port
php -S 0.0.0.0:8002 index.php
```

### npm Package Installation Fails

**Problem**: Private `@helsingborg-stad` packages fail to install.

**Solution**:

1. Ensure `GITHUB_TOKEN` is set in `.devcontainer/.env`
2. Restart the container to apply the token
3. Run `npm install` again

## Debugging

### Enable Xdebug

To enable debugging in VS Code:

1. Open `.devcontainer/devcontainer.json`
2. Change `"XDEBUG_MODE": "off"` to `"XDEBUG_MODE": "debug"`
3. Restart the container
4. Set breakpoints in VS Code and use the debugger

### View Application Logs

```bash
# Check if running in Docker
docker ps

# Access container logs
docker logs <container_id>

# Real-time logs
docker logs -f <container_id>
```

## Testing

### Run All Tests

```bash
cd frontend/app
npm test
```

### Run Specific Test File

```bash
cd frontend/app
./vendor/bin/phpunit --testdox tests/AuthTest.php
```

## Additional Resources

- [Kontrollkompassen Repository](https://github.com/helsingborg-stad/kontrollkompassen-frontend)
- [Slim Framework Documentation](https://www.slimframework.com/)
- [Vite Documentation](https://vitejs.dev/)
- [Laravel Blade Documentation](https://laravel.com/docs/blade)
- [PHP-DI Documentation](https://php-di.org/)

## Getting Help

For issues or questions:

1. Check the main [README.md](/workspace/README.md) in the project root
2. Review [Copilot Instructions](/workspace/.github/copilot-instructions.md) for architecture details
3. Open an issue on [GitHub](https://github.com/helsingborg-stad/kontrollkompassen-frontend/issues)
