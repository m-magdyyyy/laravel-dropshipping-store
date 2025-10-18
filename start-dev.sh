#!/bin/bash

# Kill any existing processes
pkill -f "php.*artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null

# Wait a moment
sleep 2

echo "Starting Laravel server with maximum upload limits..."

# Start PHP server with custom php.ini settings
php -d upload_max_filesize=100M \
    -d post_max_size=100M \
    -d memory_limit=512M \
    -d max_execution_time=300 \
    -d max_input_time=300 \
    -S 127.0.0.1:8000 -t public &

# Start Vite dev server
npm run dev &

echo ""
echo "✅ Servers started successfully!"
echo ""
echo "📦 Laravel: http://127.0.0.1:8000"
echo "⚡ Vite: http://localhost:5173"
echo ""
echo "🚀 Upload limit: 100MB"
echo "💾 Memory limit: 512MB"
echo ""
echo "Press Ctrl+C to stop all servers"
echo ""

# Wait for all background processes
wait
