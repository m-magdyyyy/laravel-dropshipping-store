#!/bin/bash

# Kill any existing processes
pkill -f "artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null

# Wait a moment for processes to die
sleep 2

# Start Vite in background
echo "Starting Vite..."
npm run build > /dev/null 2>&1 &

# Start PHP server with explicit settings
echo "Starting PHP server with custom settings..."
php \
    -d upload_max_filesize=100M \
    -d post_max_size=100M \
    -d memory_limit=1G \
    -d max_execution_time=600 \
    -d max_input_time=600 \
    -d max_file_uploads=20 \
    -d display_errors=Off \
    -d log_errors=On \
    -S 127.0.0.1:8000 \
    -t public \
    public/index.php