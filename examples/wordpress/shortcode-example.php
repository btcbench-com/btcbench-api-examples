<?php
/**
 * BTCBench WordPress Shortcode Example
 *
 * Shortcode:
 * [btcbench_fees]
 *
 * Optional attributes:
 * [btcbench_fees title="Bitcoin Fees" show_source="yes"]
 *
 * This example fetches current Bitcoin fee estimates from the public
 * BTCBench API and displays them in a simple WordPress-friendly card.
 *
 * API endpoint:
 * https://www.btcbench.com/api/v1/fees.json
 *
 * Full API documentation:
 * https://www.btcbench.com/api-docs.html
 *
 * Important:
 * - This is an example snippet, not a full plugin.
 * - Add it to a small custom plugin or your child theme's functions.php.
 * - Avoid editing a parent theme's functions.php directly.
 * - BTCBench data is informational only. Always verify fees in your own wallet.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register shortcode.
 */
add_shortcode('btcbench_fees', 'btcbench_fees_shortcode');

/**
 * Render BTCBench fee card.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function btcbench_fees_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title'       => 'BTCBench Bitcoin Fees',
            'show_source' => 'yes',
        ),
        $atts,
        'btcbench_fees'
    );

    $cache_key = 'btcbench_current_fees';
    $data = get_transient($cache_key);

    if (false === $data) {
        $response = wp_remote_get(
            'https://www.btcbench.com/api/v1/fees.json',
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept'     => 'application/json',
                    'User-Agent' => 'BTCBench WordPress Shortcode Example/1.0',
                ),
            )
        );

        if (is_wp_error($response)) {
            return btcbench_fees_error_card(
                'Unable to load BTCBench fee data right now.'
            );
        }

        $status_code = wp_remote_retrieve_response_code($response);

        if ($status_code < 200 || $status_code >= 300) {
            return btcbench_fees_error_card(
                'BTCBench API returned HTTP status ' . intval($status_code) . '.'
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!is_array($data) || empty($data['fees']) || !is_array($data['fees'])) {
            return btcbench_fees_error_card(
                'Invalid BTCBench API response.'
            );
        }

        /*
         * Cache for 5 minutes.
         * BTCBench public fee data is updated periodically,
         * and caching avoids unnecessary repeated requests.
         */
        set_transient($cache_key, $data, 5 * MINUTE_IN_SECONDS);
    }

    $fees = isset($data['fees']) && is_array($data['fees']) ? $data['fees'] : array();

    $fastest = btcbench_fees_get_value($fees, 'fastest');
    $normal  = btcbench_fees_get_value($fees, 'halfHour');
    $hour    = btcbench_fees_get_value($fees, 'hour');
    $economy = btcbench_fees_get_value($fees, 'economy');

    $datetime = !empty($data['datetime'])
        ? sanitize_text_field((string) $data['datetime'])
        : 'Latest BTCBench snapshot';

    $source = !empty($data['source'])
        ? sanitize_text_field((string) $data['source'])
        : 'BTCBench public API';

    $show_source = strtolower((string) $atts['show_source']) === 'yes';

    ob_start();
    ?>

```
<div class="btcbench-fee-card">
    <style>
        .btcbench-fee-card {
            max-width: 460px;
            margin: 1.5rem 0;
            padding: 0;
            border: 1px solid #dbe3f5;
            border-top: 4px solid #6366f1;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            color: #111827;
        }

        .btcbench-fee-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f7f8ff 100%);
            border-bottom: 1px solid #e5e7eb;
        }

        .btcbench-fee-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.25;
        }

        .btcbench-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #d1fae5;
            border: 1px solid #99f6e4;
            color: #064e3b;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .btcbench-live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
        }

        .btcbench-fee-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 13px 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .btcbench-fee-row:last-of-type {
            border-bottom: none;
        }

        .btcbench-fee-name {
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .btcbench-fee-help {
            margin-top: 2px;
            color: #9aa5b8;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .btcbench-fee-value {
            color: #f7931a;
            font-size: 1.25rem;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .btcbench-fee-unit {
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 700;
            margin-left: 3px;
        }

        .btcbench-fee-footer {
            padding: 12px 16px;
            background: #f4f5ff;
            border-top: 1px solid #dbe3f5;
            color: #6b7280;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .btcbench-fee-footer a {
            color: #4f46e5;
            font-weight: 700;
            text-decoration: none;
        }

        .btcbench-fee-footer a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="btcbench-fee-header">
        <h3 class="btcbench-fee-title"><?php echo esc_html($atts['title']); ?></h3>
        <span class="btcbench-live-pill">
            <span class="btcbench-live-dot" aria-hidden="true"></span>
            Live
        </span>
    </div>

    <div class="btcbench-fee-row">
        <div>
            <div class="btcbench-fee-name">Fastest</div>
            <div class="btcbench-fee-help">Higher-priority estimate</div>
        </div>
        <div class="btcbench-fee-value">
            <?php echo esc_html($fastest); ?><span class="btcbench-fee-unit">sat/vB</span>
        </div>
    </div>

    <div class="btcbench-fee-row">
        <div>
            <div class="btcbench-fee-name">Normal</div>
            <div class="btcbench-fee-help">Typical confirmation target</div>
        </div>
        <div class="btcbench-fee-value">
            <?php echo esc_html($normal); ?><span class="btcbench-fee-unit">sat/vB</span>
        </div>
    </div>

    <div class="btcbench-fee-row">
        <div>
            <div class="btcbench-fee-name">Hour</div>
            <div class="btcbench-fee-help">Lower-priority estimate</div>
        </div>
        <div class="btcbench-fee-value">
            <?php echo esc_html($hour); ?><span class="btcbench-fee-unit">sat/vB</span>
        </div>
    </div>

    <div class="btcbench-fee-row">
        <div>
            <div class="btcbench-fee-name">Economy</div>
            <div class="btcbench-fee-help">Lowest listed estimate</div>
        </div>
        <div class="btcbench-fee-value">
            <?php echo esc_html($economy); ?><span class="btcbench-fee-unit">sat/vB</span>
        </div>
    </div>

    <div class="btcbench-fee-footer">
        <strong>Updated:</strong> <?php echo esc_html($datetime); ?><br>
        <?php if ($show_source): ?>
            <strong>Source:</strong> <?php echo esc_html($source); ?><br>
        <?php endif; ?>
        Powered by <a href="https://www.btcbench.com/" target="_blank" rel="noopener noreferrer">BTCBench</a>.
        See <a href="https://www.btcbench.com/api-docs.html" target="_blank" rel="noopener noreferrer">API docs</a>
        and <a href="https://www.btcbench.com/embed.html" target="_blank" rel="noopener noreferrer">embed widgets</a>.
        <br>
        <small>Informational estimate only. Always verify fees in your own wallet before sending Bitcoin.</small>
    </div>
</div>
<?php

return ob_get_clean();
```

}

/**

* Safely fetch a fee value from the fees array.
*
* @param array  $fees Fee object from BTCBench response.
* @param string $key  Fee key.
* @return string
  */
  function btcbench_fees_get_value(array $fees, string $key): string {
  if (array_key_exists($key, $fees) && $fees[$key] !== null && $fees[$key] !== '') {
  return (string) $fees[$key];
  }

  return 'N/A';
  }

/**

* Render a safe error card.
*
* @param string $message Error message.
* @return string
  */
  function btcbench_fees_error_card(string $message): string {
  ob_start();
  ?>

   <div class="btcbench-fee-card btcbench-fee-error" style="max-width:460px;margin:1.5rem 0;padding:16px;border:1px solid #fecaca;border-radius:14px;background:#fef2f2;color:#991b1b;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;">
       <strong>BTCBench fee data unavailable.</strong><br>
       <?php echo esc_html($message); ?><br>
       <small>Please try again later or visit <a href="https://www.btcbench.com/" target="_blank" rel="noopener noreferrer" style="color:#991b1b;font-weight:700;">BTCBench</a>.</small>
   </div>
   <?php

  return ob_get_clean();
  }
  ?>
