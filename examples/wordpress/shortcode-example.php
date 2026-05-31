<?php
/**
 * BTCBench WordPress Shortcode Example
 * https://www.btcbench.com/
 *
 * Readable source example showing how to add a BTCBench fee widget
 * to your WordPress site using a shortcode.
 *
 * HOW TO USE
 * ──────────────────────────────────────────────────────────────────
 * 1. Copy the code from the "SHORTCODE HANDLER" section below.
 * 2. Paste it into your theme's functions.php file
 *    OR into a custom plugin file.
 * 3. Use the shortcode in any post, page or widget:
 *
 *    [btcbench_fees]
 *
 * 4. Optional attributes:
 *
 *    [btcbench_fees title="Bitcoin Fees" vbytes="140" show_usd="true"]
 *
 * ──────────────────────────────────────────────────────────────────
 * NOTE: This file is a readable source reference only.
 *       It is NOT intended to be executed directly.
 *       Copy the relevant sections into WordPress as described above.
 * ──────────────────────────────────────────────────────────────────
 */

// =============================================================================
//  SECTION 1 — SHORTCODE HANDLER
//  Copy this entire section into your functions.php or custom plugin file.
// =============================================================================

/*

// ── 1a. Register the shortcode ────────────────────────────────────────────────

add_shortcode( 'btcbench_fees', 'btcbench_fees_shortcode' );


// ── 1b. Shortcode callback ────────────────────────────────────────────────────

function btcbench_fees_shortcode( $atts ) {

    // ── Default attributes ───────────────────────────────────────────────────
    $atts = shortcode_atts(
        [
            'title'    => 'Current Bitcoin Fees',
            'vbytes'   => 140,
            'show_usd' => 'true',
        ],
        $atts,
        'btcbench_fees'
    );

    $title    = sanitize_text_field( $atts['title'] );
    $vbytes   = absint( $atts['vbytes'] );
    $show_usd = filter_var( $atts['show_usd'], FILTER_VALIDATE_BOOLEAN );

    // ── Enqueue styles and scripts ───────────────────────────────────────────
    wp_enqueue_style(
        'btcbench-fees',
        get_template_directory_uri() . '/assets/css/btcbench-fees.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'btcbench-fees',
        get_template_directory_uri() . '/assets/js/btcbench-fees.js',
        [],
        '1.0.0',
        true
    );

    // ── Pass PHP config to JavaScript via wp_localize_script ─────────────────
    wp_localize_script(
        'btcbench-fees',
        'BTCBenchConfig',
        [
            'apiUrl'   => 'https://www.btcbench.com/api/v1/fees.json',
            'vbytes'   => $vbytes,
            'showUsd'  => $show_usd,
            'nonce'    => wp_create_nonce( 'btcbench_fees_nonce' ),
        ]
    );

    // ── Build and return widget HTML ─────────────────────────────────────────
    ob_start();
    ?>

    <div class="btcbench-widget" id="btcbench-widget">

        <div class="btcbench-header">
            <div class="btcbench-brand">
                <span class="btcbench-mark">B</span>
                <span class="btcbench-name">BTCBench</span>
            </div>
            <div class="btcbench-title">
                <?php echo esc_html( $title ); ?>
            </div>
            <div class="btcbench-status" id="btcbench-status">
                <span class="btcbench-dot" aria-hidden="true"></span>
                <span id="btcbench-status-text">Live</span>
            </div>
        </div>

        <table class="btcbench-table" aria-label="Bitcoin fee estimates">
            <thead>
                <tr>
                    <th scope="col">Tier</th>
                    <th scope="col">sat/vB</th>
                    <?php if ( $show_usd ) : ?>
                        <th scope="col">~USD</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <tr class="btcbench-row btcbench-fastest">
                    <td>
                        <span class="btcbench-tier-name">Fastest</span>
                        <span class="btcbench-tier-help">~10 min confirm</span>
                    </td>
                    <td>
                        <span id="btcbench-fastest-fee">—</span>
                    </td>
                    <?php if ( $show_usd ) : ?>
                        <td>
                            <span id="btcbench-fastest-usd" class="btcbench-usd">~$—</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr class="btcbench-row btcbench-normal">
                    <td>
                        <span class="btcbench-tier-name">Normal</span>
                        <span class="btcbench-tier-help">~30 min confirm</span>
                    </td>
                    <td>
                        <span id="btcbench-normal-fee">—</span>
                    </td>
                    <?php if ( $show_usd ) : ?>
                        <td>
                            <span id="btcbench-normal-usd" class="btcbench-usd">~$—</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr class="btcbench-row btcbench-economy">
                    <td>
                        <span class="btcbench-tier-name">Economy</span>
                        <span class="btcbench-tier-help">Low priority</span>
                    </td>
                    <td>
                        <span id="btcbench-economy-fee">—</span>
                    </td>
                    <?php if ( $show_usd ) : ?>
                        <td>
                            <span id="btcbench-economy-usd" class="btcbench-usd">~$—</span>
                        </td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>

        <div class="btcbench-footer">
            <div class="btcbench-freshness">
                <span class="btcbench-freshness-dot" aria-hidden="true"></span>
                <span id="btcbench-freshness-text">Loading BTCBench data…</span>
            </div>
            <div class="btcbench-datetime" id="btcbench-datetime">—</div>
            <div class="btcbench-powered">
                Powered by
                <a
                    href="https://www.btcbench.com/"
                    target="_blank"
                    rel="noopener noreferrer"
                >BTCBench</a>
                · Independent · Public Bitcoin fee data
            </div>
            <div class="btcbench-note">
                USD estimate assumes <?php echo esc_html( $vbytes ); ?> vbyte transaction.
                Always verify fees in your own wallet.
            </div>
            <div class="btcbench-error" id="btcbench-error" style="display:none;">
                Could not load BTCBench fee data right now.
            </div>
        </div>

    </div>

    <?php
    return ob_get_clean();
}

*/


// =============================================================================
//  SECTION 2 — JAVASCRIPT
//  Save as: /wp-content/themes/your-theme/assets/js/btcbench-fees.js
// =============================================================================

/*

(function () {
    "use strict";

    const cfg         = window.BTCBenchConfig || {};
    const API_URL     = cfg.apiUrl  || "https://www.btcbench.com/api/v1/fees.json";
    const VBYTES      = cfg.vbytes  || 140;
    const SHOW_USD    = cfg.showUsd !== false;
    const REFRESH_MS  = 60000;

    // ── Element references ───────────────────────────────────────────────────

    const el = (id) => document.getElementById(id);

    const fastestFeeEl    = el("btcbench-fastest-fee");
    const normalFeeEl     = el("btcbench-normal-fee");
    const economyFeeEl    = el("btcbench-economy-fee");

    const fastestUsdEl    = el("btcbench-fastest-usd");
    const normalUsdEl     = el("btcbench-normal-usd");
    const economyUsdEl    = el("btcbench-economy-usd");

    const freshnessTextEl = el("btcbench-freshness-text");
    const datetimeLineEl  = el("btcbench-datetime");
    const statusEl        = el("btcbench-status");
    const statusTextEl    = el("btcbench-status-text");
    const errorEl         = el("btcbench-error");

    // ── Helpers ──────────────────────────────────────────────────────────────

    function setStatus(isOk) {
        if (isOk) {
            statusEl.classList.remove("error");
            statusTextEl.textContent = "Live";
        } else {
            statusEl.classList.add("error");
            statusTextEl.textContent = "Error";
        }
    }

    function parseDate(data) {
        const raw =
            data.datetime             ||
            data.btc_price_fetched_at ||
            data.updated_at           ||
            data.timestamp            ||
            null;

        if (!raw) return null;

        if (typeof raw === "number") {
            return new Date(raw * 1000);
        }

        const str        = String(raw).trim();
        const normalized = str.includes("T")
            ? str
            : str.replace(" ", "T") + "Z";

        const date = new Date(normalized);
        return isNaN(date.getTime()) ? null : date;
    }

    function formatUtcDate(date) {
        if (!date) return "Latest BTCBench snapshot";
        return date.toLocaleString(undefined, {
            timeZone:     "UTC",
            year:         "numeric",
            month:        "short",
            day:          "2-digit",
            hour:         "2-digit",
            minute:       "2-digit",
            timeZoneName: "short",
        });
    }

    function minutesAgo(date) {
        if (!date) return "Updated recently";

        const diffMin = Math.max(0, Math.round((Date.now() - date.getTime()) / 60000));

        if (diffMin < 1)  return "Updated just now";
        if (diffMin === 1) return "Updated 1 min ago";
        if (diffMin < 60) return "Updated " + diffMin + " min ago";

        const diffHours = Math.round(diffMin / 60);
        if (diffHours === 1) return "Updated 1 hour ago";
        return "Updated " + diffHours + " hours ago";
    }

    function feeToUsd(feeRate, btcPrice) {
        const fee   = Number(feeRate);
        const price = Number(btcPrice);
        if (!isFinite(fee) || !isFinite(price)) return "~$—";
        const usd = ((fee * VBYTES) / 100_000_000) * price;
        return "~$" + usd.toFixed(2);
    }

    // ── Render ───────────────────────────────────────────────────────────────

    function renderData(data) {
        if (!data || !data.fees) {
            throw new Error("Invalid BTCBench API response");
        }

        const fastest  = data.fees.fastest;
        const normal   = data.fees.halfHour;
        const economy  = data.fees.economy;
        const btcPrice = data.btc_price_usd;

        fastestFeeEl.textContent = fastest ?? "N/A";
        normalFeeEl.textContent  = normal  ?? "N/A";
        economyFeeEl.textContent = economy ?? "N/A";

        if (SHOW_USD && fastestUsdEl) {
            fastestUsdEl.textContent = feeToUsd(fastest, btcPrice);
            normalUsdEl.textContent  = feeToUsd(normal,  btcPrice);
            economyUsdEl.textContent = feeToUsd(economy, btcPrice);
        }

        const date = parseDate(data);
        freshnessTextEl.textContent = minutesAgo(date);
        datetimeLineEl.textContent  = formatUtcDate(date);

        errorEl.style.display = "none";
        setStatus(true);
    }

    // ── Fetch ────────────────────────────────────────────────────────────────

    async function loadFees() {
        try {
            const response = await fetch(API_URL, { cache: "no-store" });
            if (!response.ok) throw new Error("HTTP " + response.status);
            const data = await response.json();
            renderData(data);
        } catch (err) {
            console.error("BTCBench shortcode error:", err);
            errorEl.style.display       = "block";
            freshnessTextEl.textContent = "Unable to refresh BTCBench data";
            datetimeLineEl.textContent  = "Please check the BTCBench API or status page.";
            setStatus(false);
        }
    }

    // ── Init ─────────────────────────────────────────────────────────────────

    loadFees();

    window.setInterval(function () {
        if (document.visibilityState === "visible") loadFees();
    }, REFRESH_MS);

})();

*/


// =============================================================================
//  SECTION 3 — CSS
//  Save as: /wp-content/themes/your-theme/assets/css/btcbench-fees.css
// =============================================================================

/*

.btcbench-widget {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    border: 1px solid #cbd7ff;
    border-top: 4px solid #6366f1;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 16px 42px rgba(15, 23, 42, 0.10);
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
}

.btcbench-header {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    background: linear-gradient(180deg, #ffffff 0%, #f7f8ff 100%);
    border-bottom: 1px solid #dbe3f5;
}

.btcbench-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btcbench-mark {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 58%, #f7931a 100%);
    color: #ffffff;
    font-size: 1.2rem;
    font-weight: 900;
    box-shadow: 0 8px 18px rgba(99, 102, 241, 0.35);
}

.btcbench-name {
    font-size: 1.05rem;
    font-weight: 900;
    letter-spacing: -0.03em;
    color: #111827;
}

.btcbench-title {
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #6b7280;
    text-align: center;
    padding: 6px 14px;
    border-radius: 999px;
    border: 1px solid #b8c3ff;
    background: #eef0ff;
}

.btcbench-status {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
    padding: 7px 12px;
    border-radius: 999px;
    background: #d1fae5;
    border: 1px solid #99f6e4;
    color: #064e3b;
    font-size: 0.78rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    white-space: nowrap;
    justify-self: end;
}

.btcbench-status.error {
    background: #fee2e2;
    border-color: #fecaca;
    color: #991b1b;
}

.btcbench-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    animation: btcbench-pulse 2s infinite;
}

.btcbench-status.error .btcbench-dot {
    background: #ef4444;
    animation: none;
}

@keyframes btcbench-pulse {
    0%, 100% { box-shadow: 0 0 0 0   rgba(16, 185, 129, 0.45); }
    50%       { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0);    }
}

.btcbench-table {
    width: 100%;
    border-collapse: collapse;
}

.btcbench-table thead tr {
    background: #f8fafc;
    border-bottom: 1px solid #eef2f7;
}

.btcbench-table thead th {
    padding: 10px 18px;
    font-size: 0.76rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9aa3b5;
    text-align: left;
}

.btcbench-table thead th:not(:first-child) {
    text-align: right;
}

.btcbench-row td {
    padding: 14px 18px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
}

.btcbench-row:last-child td {
    border-bottom: none;
}

.btcbench-tier-name {
    display: block;
    font-size: 0.88rem;
    font-weight: 900;
    letter-spacing: 0.07em;
    text-transform: uppercase;
}

.btcbench-fastest .btcbench-tier-name { color: #4f46e5; }
.btcbench-normal  .btcbench-tier-name { color: #0e7490; }
.btcbench-economy .btcbench-tier-name { color: #059669; }

.btcbench-tier-help {
    display: block;
    margin-top: 2px;
    font-size: 0.76rem;
    color: #9aa5b8;
    font-weight: 700;
}

.btcbench-row td:not(:first-child) {
    text-align: right;
    font-size: 1.25rem;
    font-weight: 900;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.04em;
    white-space: nowrap;
}

.btcbench-fastest td:nth-child(2) { color: #dc2626; }
.btcbench-normal  td:nth-child(2) { color: #f7931a; }
.btcbench-economy td:nth-child(2) { color: #059669; }

.btcbench-usd {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 8px;
    background: #f4f6fb;
    color: #6b7280;
    font-size: 0.88rem;
    font-weight: 900;
    font-variant-numeric: tabular-nums;
}

.btcbench-footer {
    padding: 13px 18px 14px;
    background: #f4f5ff;
    border-top: 1px solid #dbe3f5;
}

.btcbench-freshness {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #4b5563;
    font-size: 0.84rem;
    font-weight: 900;
    margin-bottom: 4px;
}

.btcbench-freshness-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    flex: 0 0 auto;
}

.btcbench-datetime {
    color: #6b7280;
    font-size: 0.78rem;
    margin-bottom: 4px;
}

.btcbench-powered {
    color: #9aa3b5;
    font-size: 0.76rem;
    line-height: 1.5;
}

.btcbench-powered a {
    color: #7c86a2;
    font-weight: 850;
    text-decoration: none;
}

.btcbench-note {
    margin-top: 4px;
    color: #9aa3b5;
    font-size: 0.74rem;
}

.btcbench-error {
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    font-size: 0.82rem;
    font-weight: 800;
}

@media (max-width: 440px) {
    .btcbench-header {
        grid-template-columns: 1fr auto;
        padding: 12px;
    }
    .btcbench-title {
        display: none;
    }
    .btcbench-row td {
        padding: 12px 14px;
    }
}

*/


// =============================================================================
//  SECTION 4 — SHORTCODE USAGE REFERENCE
// =============================================================================

/*

    Basic usage — place in any post, page or widget:

    [btcbench_fees]


    With optional attributes:

    [btcbench_fees title="Bitcoin Fees"  vbytes="140"  show_usd="true"]
    [btcbench_fees title="Fee Estimates" vbytes="250"  show_usd="true"]
    [btcbench_fees title="BTC Fees"      vbytes="140"  show_usd="false"]


    Attribute reference:

    ┌─────────────┬───────────────────────────────┬─────────────┐
    │ Attribute   │ Description                   │ Default     │
    ├─────────────┼───────────────────────────────┼─────────────┤
    │ title       │ Widget heading / pill label   │ Current     │
    │             │                               │ Bitcoin     │
    │             │                               │ Fees        │
    ├─────────────┼───────────────────────────────┼─────────────┤
    │ vbytes      │ Assumed transaction size for  │ 140         │
    │             │ USD cost estimate             │             │
    ├─────────────┼───────────────────────────────┼─────────────┤
    │ show_usd    │ Show or hide the USD column   │ true        │
    └─────────────┴───────────────────────────────┴─────────────┘

*/


// =============================================================================
//  SECTION 5 — FILE PLACEMENT REFERENCE
// =============================================================================

/*

    WordPress file structure for this integration:

    your-wordpress-site/
    └── wp-content/
        └── themes/
            └── your-theme/
                ├── functions.php                  ← Paste Section 1 here
                └── assets/
                    ├── js/
                    │   └── btcbench-fees.js       ← Paste Section 2 here
                    └── css/
                        └── btcbench-fees.css      ← Paste Section 3 here

    ──────────────────────────────────────────────────────────────────
    Alternatively — as a standalone plugin:

    your-wordpress-site/
    └── wp-content/
        └── plugins/
            └── btcbench-fees/
                ├── btcbench-fees.php              ← Paste Section 1 here
                ├── assets/
                │   ├── js/
                │   │   └── btcbench-fees.js       ← Paste Section 2 here
                │   └── css/
                │       └── btcbench-fees.css      ← Paste Section 3 here
                └── readme.txt                     ← Optional readme

*/
