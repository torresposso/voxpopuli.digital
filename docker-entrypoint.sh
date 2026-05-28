#!/bin/sh
set -e

if [ "$(id -u)" = "0" ]; then
    if [ ! -f /data/database/.ht.sqlite ] && [ -f /app/storage/database/.ht.sqlite ]; then
        cp -a /app/storage/database/. /data/database/
    fi

    chown -R appuser:appuser /data 2>/dev/null || true
    exec su-exec appuser "$@"
fi

exec "$@"
