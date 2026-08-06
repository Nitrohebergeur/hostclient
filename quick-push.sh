#!/bin/bash
cd "$(dirname "$0")"
git add install.sh
git commit -m "fix: Ignore composer post-install errors and continue installation"
git push origin main
echo "Pushed! Wait 30 seconds then run install script"
