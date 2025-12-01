#!/bin/bash

if [ ! -d "vendor" ]; then
  echo "Missing vendors, downloading ..."
  composer install --optimize-autoloader --quiet
fi

exec "$@"
