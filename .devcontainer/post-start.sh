#!/bin/bash

cd /workspace/frontend

if [ ! -f "config.json" ]; then
    cp config-example.json config.json
    echo "Created config.json from config-example.json"
else
    echo "config.json already exists. Skipping creation."
fi

cd /workspace/frontend/app
if [ ! -d "vendor" ]; then
    composer install
    echo "Installed PHP dependencies."
else
    echo "PHP dependencies already installed. Skipping composer install."
fi

if [ ! -d "node_modules" ]; then
    npm install && npm run build
    echo "Installed JavaScript dependencies and built the project."
else
    echo "JavaScript dependencies already installed. Skipping npm install and build."
fi

php -S 0.0.0.0:8001 index.php
