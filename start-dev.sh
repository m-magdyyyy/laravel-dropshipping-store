#!/bin/bash

# Kill any existing processes
pkill -f "php.*artisan serve" 2>/dev/null
pkill -f "vite" 2>/dev/null

# Wait a moment
sleep 2

echo "Starting Laravel server with maximum upload limits..."

# Start PHP server with inline settings - THIS IS THE ONLY WAY THAT WORKS
php -d upload_max_filesize=100M \
    -d post_max_size=100M \
    -d memory_limit=1G \
    -d max_execution_time=600 \
    -d max_input_time=600 \
    -d max_file_uploads=20 \
    -d file_uploads=On \
    -S 127.0.0.1:8000 -t public &

echo "✓ PHP server started on http://127.0.0.1:8000"
echo "✓ Upload limit: 100MB"

# Start Vite
echo "Starting Vite..."
npx vite &

echo ""
echo "═══════════════════════════════════════"
echo "  Development servers are running!"
echo "═══════════════════════════════════════"
echo "  Laravel: http://127.0.0.1:8000"
echo "  Vite:    http://localhost:5173"
echo "═══════════════════════════════════════"
echo ""
echo "Press Ctrl+C to stop both servers"

# Wait for user interrupt
wait