#!/bin/bash
# fpp_install.sh — Announce SumUp plugin installer
# Called by FPP when the plugin is installed or updated.

PLUGIN_DIR="$(dirname "$0")"

# Resolve FPP's logs directory the documented way (supports a relocated
# media directory) rather than hard-coding /home/fpp/media/logs, and use
# the single FPP-conformant log file (plugin-<repoName>.log) for both this
# install script and the daemon, per the plugin guidelines' logging rules.
: "${FPPDIR:=/opt/fpp}"
. "${FPPDIR}/scripts/common" 2>/dev/null || true
LOGDIR="$(getSetting logDirectory 2>/dev/null)"
LOGDIR="${LOGDIR:-/home/fpp/media/logs}"
LOGFILE="${LOGDIR}/plugin-fpp-sumup.log"

log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $*"
    mkdir -p "$LOGDIR" 2>/dev/null || true
    echo "$msg" >> "$LOGFILE" 2>/dev/null || echo "$msg"
}

log "=== Announce SumUp install started (user=$(whoami), uid=$(id -u)) ==="

# ── Create media directories ─────────────────────────────────────
# (log() already mkdir -p's $LOGDIR on every call)
# Do this FIRST so the media log path is available.
mkdir -p /home/fpp/media/config

# ── Make scripts executable ──────────────────────────────────────
log "Setting script permissions..."
chmod +x "${PLUGIN_DIR}/scripts/"*.sh 2>/dev/null || true
chmod +x "${PLUGIN_DIR}/fpp_start.sh" 2>/dev/null || true
chmod +x "${PLUGIN_DIR}/fpp_stop.sh" 2>/dev/null || true

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

TRANSACTIONS="${PLUGIN_DIR}/config/plugin.fpp-sumup-transactions.json"
log "Writing default transactions to $TRANSACTIONS"
    cp "${PLUGIN_DIR}/config/fpp-zettle-sumup.json.example" "$TRANSACTIONS" 2>/dev/null || \
    cat > "$TRANSACTIONS" <<'JSONEOF'
[]
JSONEOF

echo "Please restart fppd for new FPP Commands to be visible."
source ${FPPDIR}/scripts/common; setSetting restartFlag 1

log "=== Announce SumUp install complete ==="
exit 0
