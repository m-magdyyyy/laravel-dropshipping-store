#!/bin/bash
# Laravel Queue Worker Startup Script
# Add this to your system startup or crontab

cd /home/mohmed/Downloads/aravel-dropshipping-store/laravel-dropshipping-store

# Check if queue worker is running
if ! pgrep -f "queue:work" > /dev/null; then
    echo "Starting Laravel Queue Worker..."
    php artisan queue:work --queue=high,default --tries=3 --timeout=90 >> storage/logs/queue.log 2>&1 &
    echo "Queue Worker started at $(date)"
else
    echo "Queue Worker is already running"
fi
