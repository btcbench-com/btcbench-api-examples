<?php
/**
 * BTCBench PHP Current Fees Example
 * https://www.btcbench.com/
 *
 * Fetches current Bitcoin fee estimates from the public BTCBench API
 * and displays them with approximate USD transaction cost.
 *  
 * Source : https://www.btcbench.com
 * API    : https://www.btcbench.com/api-docs.html
 * GitHub : https://github.com/btcbench-com/btcbench-api-examples
 * Please keep this credit if you use or share this code.
 */

declare(strict_types=1);

// ─────────────────────────────────────────────
//  Config
// ─────────────────────────────────────────────

const API_URL           = 'https://www.btcbench.com/api/v1/fees.json';
const DEFAULT_TX_VBYTES = 140;

// ─────────────────────────────────────────────
//  Helpers
// ─────────────────────────────────────────────

/**
 * Convert a sat/vB fee rate to an approximate USD cost
 * based on a default transaction size.
 */
function fee_to_usd(mixed $fee_rate_sat_per_vb, mixed $btc_usd_price): string
{
    $fee   = filter_var($fee_rate_sat_per_vb, FILTER_VALIDATE_FLOAT);
    $price = filter_var($btc_usd_price,       FILTER_VALIDATE_FLOAT);

    if ($fee === false || $price === false) {
        return '~$—';
    }

    $sats = $fee * DEFAULT_TX_VBYTES;
    $usd  = ($sats / 100_000_000) * $price;

    return '~$' . number_format($usd, 2);
}


/**
 * Extract and parse the timestamp from the API response.
 * Tries multiple known field names.
 */
function parse_date(array $data): ?DateTimeImmutable
{
    $raw = $data['datetime']
        ?? $data['btc_price_fetched_at']
        ?? $data['updated_at']
        ?? $data['timestamp']
        ?? null;

    if ($raw === null) {
        return null;
    }

    // Unix timestamp (integer or float)
    if (is_numeric($raw)) {
        $dt = DateTimeImmutable::createFromFormat(
            'U',
            (string)(int)$raw
        );
        return $dt !== false ? $dt->setTimezone(new DateTimeZone('UTC')) : null;
    }

    // String timestamp — normalise to ISO 8601
    $raw_str = trim((string)$raw);

    if (!str_contains($raw_str, 'T')) {
        $raw_str = str_replace(' ', 'T', $raw_str);
        if (!str_ends_with($raw_str, 'Z')) {
            $raw_str .= 'Z';
        }
    }

    try {
        return new DateTimeImmutable($raw_str, new DateTimeZone('UTC'));
    } catch (Exception) {
        return null;
    }
}


/**
 * Format a DateTimeImmutable object as a readable UTC string.
 */
function format_utc_date(?DateTimeImmutable $date): string
{
    if ($date === null) {
        return 'Latest BTCBench snapshot';
    }

    return $date->format('d M Y  H:i') . ' UTC';
}


/**
 * Return a human-readable 'Updated X min ago' string.
 */
function minutes_ago(?DateTimeImmutable $date): string
{
    if ($date === null) {
        return 'Updated recently';
    }

    $diff_seconds = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
        ->getTimestamp() - $date->getTimestamp();

    $diff_min = max(0, (int)round($diff_seconds / 60));

    if ($diff_min < 1)  return 'Updated just now';
    if ($diff_min === 1) return 'Updated 1 min ago';
    if ($diff_min < 60) return "Updated {$diff_min} min ago";

    $diff_hours = (int)round($diff_min / 60);

    if ($diff_hours === 1) return 'Updated 1 hour ago';

    return "Updated {$diff_hours} hours ago";
}


// ─────────────────────────────────────────────
//  Fetch
// ─────────────────────────────────────────────

/**
 * Fetch fee data from the BTCBench public API.
 * Returns parsed array or throws on failure.
 *
 * @throws RuntimeException
 */
function fetch_fees(): array
{
    $context = stream_context_create([
        'http' => [
            'method'          => 'GET',
            'header'          => "User-Agent: BTCBench-PHP-Example/1.0\r\n",
            'timeout'         => 10,
            'ignore_errors'   => true,
        ],
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
        ],
    ]);

    $raw = @file_get_contents(API_URL, false, $context);

    if ($raw === false) {
        throw new RuntimeException('Request failed — could not reach BTCBench API.');
    }

    // Check HTTP status from response headers
    $status_line = $http_response_header[0] ?? '';
    preg_match('/HTTP\/\S+\s+(\d+)/', $status_line, $matches);
    $status_code = (int)($matches[1] ?? 0);

    if ($status_code !== 200) {
        throw new RuntimeException("HTTP {$status_code}");
    }

    $data = json_decode($raw, associative: true);

    if (!is_array($data)) {
        throw new RuntimeException('Failed to decode BTCBench API JSON response.');
    }

    return $data;
}


// ─────────────────────────────────────────────
//  Render
// ─────────────────────────────────────────────

/**
 * Validate and display the fee data in the terminal.
 *
 * @throws InvalidArgumentException
 */
function render(array $data): void
{
    if (empty($data['fees'])) {
        throw new InvalidArgumentException('Invalid BTCBench API response.');
    }

    $fees          = $data['fees'];
    $fastest       = $fees['fastest']  ?? 'N/A';
    $normal        = $fees['halfHour'] ?? 'N/A';
    $economy       = $fees['economy']  ?? 'N/A';
    $btc_price_usd = $data['btc_price_usd'] ?? null;

    $date = parse_date($data);

    $div  = str_repeat('=', 44);
    $line = str_repeat('-', 44);

    echo PHP_EOL;
    echo $div . PHP_EOL;
    echo '  BTCBench — Current Bitcoin Fee Estimates'  . PHP_EOL;
    echo $div . PHP_EOL;
    echo sprintf(
        "  %-12s %8s   %12s\n",
        'Tier', 'sat/vB', 'USD ~140 vB'
    );
    echo $line . PHP_EOL;
    echo sprintf(
        "  %-12s %8s   %12s\n",
        'Fastest',
        (string)$fastest,
        fee_to_usd($fastest, $btc_price_usd)
    );
    echo sprintf(
        "  %-12s %8s   %12s\n",
        'Normal',
        (string)$normal,
        fee_to_usd($normal, $btc_price_usd)
    );
    echo sprintf(
        "  %-12s %8s   %12s\n",
        'Economy',
        (string)$economy,
        fee_to_usd($economy, $btc_price_usd)
    );
    echo $line . PHP_EOL;
    echo '  ' . minutes_ago($date)    . PHP_EOL;
    echo '  ' . format_utc_date($date) . PHP_EOL;
    echo $div . PHP_EOL;
    echo '  Powered by BTCBench · https://www.btcbench.com' . PHP_EOL;
    echo '  USD estimate assumes 140 vbyte transaction.'    . PHP_EOL;
    echo '  Always verify fees in your own wallet.'         . PHP_EOL;
    echo $div . PHP_EOL;
    echo PHP_EOL;
}


// ─────────────────────────────────────────────
//  Main
// ─────────────────────────────────────────────

function main(): void
{
    echo PHP_EOL . '  Fetching BTCBench fee data…' . PHP_EOL;

    try {
        $data = fetch_fees();
        render($data);
    } catch (Throwable $error) {
        echo PHP_EOL;
        echo '  [ERROR] Could not load BTCBench fee data: ' . $error->getMessage() . PHP_EOL;
        echo '  Please check the BTCBench API or status page.' . PHP_EOL;
        echo '  https://www.btcbench.com' . PHP_EOL;
        echo PHP_EOL;
    }
}

main();
