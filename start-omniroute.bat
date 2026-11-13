@echo off
title OmniRoute Gateway (127.0.0.1:20128)
echo ========================================================
echo   Starting OmniRoute AI Gateway (Studora)
echo   Binding : 127.0.0.1:20128 (Loopback only)
echo   Security: REQUIRE_API_KEY=true
echo ========================================================
set OMNIROUTE_SERVER_HOST=127.0.0.1
set HOSTNAME=127.0.0.1
set HOST=127.0.0.1
set PORT=20128
set REQUIRE_API_KEY=true
omniroute serve --port 20128
