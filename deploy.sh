set -e

echo "Deploying application ..."

set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git pull https://devsquad-id@github.com/devsquad-id/wabot-api-2021.git
# Exit maintenance mode
php artisan up

echo "Application deployed!"
