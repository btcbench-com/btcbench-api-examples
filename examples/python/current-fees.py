#!/usr/bin/env python3
"""
BTCBench Python Current Fees Example
https://www.btcbench.com/

Fetches current Bitcoin fee estimates from the public BTCBench API
and displays them with approximate USD transaction cost.
"""

import json
import urllib.request
from datetime import datetime, timezone

# ─────────────────────────────────────────────
#  Config
# ─────────────────────────────────────────────

API_URL          = "https://www.btcbench.com/api/v1/fees.json"
DEFAULT_TX_VBYTES = 140

# ─────────────────────────────────────────────
#  Helpers
# ─────────────────────────────────────────────

def fee_to_usd(fee_rate_sat_per_vb, btc_usd_price):
    """
    Convert a sat/vB fee rate to an approximate USD cost
    based on a default transaction size.
    """
    try:
        fee    = float(fee_rate_sat_per_vb)
        price  = float(btc_usd_price)
        sats   = fee * DEFAULT_TX_VBYTES
        usd    = (sats / 100_000_000) * price
        return f"~${usd:.2f}"
    except (TypeError, ValueError):
        return "~$—"


def parse_date(data):
    """
    Extract and parse the timestamp from the API response.
    Tries multiple known field names.
    """
    raw = (
        data.get("datetime")
        or data.get("btc_price_fetched_at")
        or data.get("updated_at")
        or data.get("timestamp")
    )

    if raw is None:
        return None

    # Unix timestamp (integer)
    if isinstance(raw, (int, float)):
        return datetime.fromtimestamp(raw, tz=timezone.utc)

    # String timestamp
    raw_str = str(raw).strip()

    # Normalise space-separated datetimes to ISO format
    if "T" not in raw_str:
        raw_str = raw_str.replace(" ", "T")
        if not raw_str.endswith("Z"):
            raw_str += "Z"

    try:
        # Python 3.11+ supports Z suffix natively
        return datetime.fromisoformat(raw_str.replace("Z", "+00:00"))
    except ValueError:
        return None


def format_utc_date(date):
    """
    Format a datetime object as a readable UTC string.
    """
    if not date:
        return "Latest BTCBench snapshot"
    return date.strftime("%d %b %Y  %H:%M UTC")


def minutes_ago(date):
    """
    Return a human-readable 'Updated X min ago' string.
    """
    if not date:
        return "Updated recently"

    diff_seconds = (datetime.now(tz=timezone.utc) - date).total_seconds()
    diff_min     = max(0, round(diff_seconds / 60))

    if diff_min < 1:
        return "Updated just now"
    if diff_min == 1:
        return "Updated 1 min ago"
    if diff_min < 60:
        return f"Updated {diff_min} min ago"

    diff_hours = round(diff_min / 60)
    if diff_hours == 1:
        return "Updated 1 hour ago"

    return f"Updated {diff_hours} hours ago"


# ─────────────────────────────────────────────
#  Fetch
# ─────────────────────────────────────────────

def fetch_fees():
    """
    Fetch fee data from the BTCBench public API.
    Returns parsed JSON dict or raises on failure.
    """
    req = urllib.request.Request(
        API_URL,
        headers={"User-Agent": "BTCBench-Python-Example/1.0"}
    )

    with urllib.request.urlopen(req, timeout=10) as response:
        if response.status != 200:
            raise RuntimeError(f"HTTP {response.status}")
        raw = response.read().decode("utf-8")
        return json.loads(raw)


# ─────────────────────────────────────────────
#  Render
# ─────────────────────────────────────────────

def render(data):
    """
    Validate and display the fee data in the terminal.
    """
    if not data or "fees" not in data:
        raise ValueError("Invalid BTCBench API response")

    fees          = data["fees"]
    fastest       = fees.get("fastest",  "N/A")
    normal        = fees.get("halfHour", "N/A")
    economy       = fees.get("economy",  "N/A")
    btc_price_usd = data.get("btc_price_usd")

    date = parse_date(data)

    # ── Output ──────────────────────────────
    print()
    print("=" * 44)
    print("  BTCBench — Current Bitcoin Fee Estimates")
    print("=" * 44)
    print(f"  {'Tier':<12} {'sat/vB':>8}   {'USD ~140 vB':>12}")
    print("-" * 44)
    print(f"  {'Fastest':<12} {str(fastest):>8}   {fee_to_usd(fastest, btc_price_usd):>12}")
    print(f"  {'Normal':<12} {str(normal):>8}   {fee_to_usd(normal,  btc_price_usd):>12}")
    print(f"  {'Economy':<12} {str(economy):>8}   {fee_to_usd(economy, btc_price_usd):>12}")
    print("-" * 44)
    print(f"  {minutes_ago(date)}")
    print(f"  {format_utc_date(date)}")
    print("=" * 44)
    print("  Powered by BTCBench · https://www.btcbench.com")
    print("  USD estimate assumes 140 vbyte transaction.")
    print("  Always verify fees in your own wallet.")
    print("=" * 44)
    print()


# ─────────────────────────────────────────────
#  Main
# ─────────────────────────────────────────────

def main():
    print("\n  Fetching BTCBench fee data…")
    try:
        data = fetch_fees()
        render(data)
    except Exception as error:
        print(f"\n  [ERROR] Could not load BTCBench fee data: {error}")
        print("  Please check the BTCBench API or status page.")
        print("  https://www.btcbench.com\n")


if __name__ == "__main__":
    main()
