#!/bin/bash

PLUGIN_DIR="$(dirname "$0")"

# Log to /tmp first (always writable), then also try the media logs dir
LOGFILE="/tmp/fppSumUp_install.log"
MEDIA_LOG="/home/fpp/media/logs/fppSumUp_install.log"

log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $*"
    echo "$msg" | tee -a "$LOGFILE"
    echo "$msg" >> "$MEDIA_LOG" 2>/dev/null || true
}

log "=== Announce SumUp install started (user=$(whoami), uid=$(id -u)) ==="

# ── Create media directories ─────────────────────────────────────
# Do this FIRST so the media log path is available.
mkdir -p /home/fpp/media/logs
mkdir -p /home/fpp/media/config

# Now that the dir exists, copy /tmp log into media log
cat "$LOGFILE" >> "$MEDIA_LOG" 2>/dev/null || true

# ── Make scripts executable ──────────────────────────────────────
log "Setting script permissions..."
chmod +x "${PLUGIN_DIR}/scripts/"*.sh 2>/dev/null || true

# ── Write default config if none exists ─────────────────────────
CONFIG="/home/fpp/media/config/plugin.fpp-sumup.json"
if [[ ! -f "$CONFIG" ]]; then
log "Writing default config to $CONFIG"
    cp "${PLUGIN_DIR}/config/fpp-sumup.json.example" "$CONFIG" 2>/dev/null || \
    cat > "$CONFIG" <<'JSONEOF'
{
	"effect_activate": "no",
	"command": "",
	"publish": {
		"activate": "yes"
	},
	"pushover": {
    "activate": "no",
    "app_token": "",
    "user_key": "",
    "message": ""
  },
  "other": {
    "currency": "GBP"
  }
}
JSONEOF
fi

sudo chown fpp /home/fpp/media/config/plugin.fpp-sumup.json

echo "Please restart fppd for new FPP Commands to be visible."
. /opt/fpp/scripts/common
setSetting restartFlag 1
