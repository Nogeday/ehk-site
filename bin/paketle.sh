#!/usr/bin/env bash
# WordPress yönetim panelinden yüklenebilecek ZIP dosyalarını üretir:
#   dist/ktuehk.zip       → Görünüm › Temalar › Yeni ekle › Tema yükle
#   dist/ktuehk-core.zip  → Eklentiler › Yeni ekle › Eklenti yükle
set -euo pipefail

cd "$(dirname "$0")/.."
mkdir -p dist
rm -f dist/ktuehk.zip dist/ktuehk-core.zip

(cd wp-content/themes && zip -qr ../../dist/ktuehk.zip ktuehk -x '*.DS_Store')
(cd wp-content/plugins && zip -qr ../../dist/ktuehk-core.zip ktuehk-core -x '*.DS_Store')

ls -lh dist/*.zip
