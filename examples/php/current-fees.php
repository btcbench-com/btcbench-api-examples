<?php
/**
 * BTCBench PHP Example
 *
 * Fetch current Bitcoin fee estimates from the public BTCBench API.
 *
 * API endpoint:
 * https://www.btcbench.com/api/v1/fees.json
 *
 * Full API documentation:
 * https://www.btcbench.com/api-docs.html
 *
 * This example is intentionally simple and does not include private API keys,
 * analytics IDs, server paths, credentials, or internal infrastructure details.
 */

declare(strict_types=1);

$apiUrl = 'https://www.btcbench.com/api/v1/fees.json';

/**
 * Fetch JSON data from the BTCBench API using cURL.
 */
function btcbench_fetch_json(string $url): array
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_USERAGENT => 'BTCBench PHP Example/1.0',
    ]);

    $responseBody = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($responseBody === false) {
        throw new RuntimeException('cURL error: ' . $curlError);
    }

    if ($httpCode < 200 || $httpCode >= 300) {
        throw new RuntimeException('BTCBench API returned HTTP status ' . $httpCode);
    }

    $data = json_decode($responseBody, true);

    if (!is_array($data)) {
        throw new RuntimeException('Invalid JSON response from BTCBench API.');
    }

    return $data;
}

/**
 * Safely read nested fee values from the BTCBench response.
 */
function btcbench_fee_value(array $data, string $key): string
{
    if (
        isset($data['fees']) &&
        is_array($data['fees']) &&
        array_key_exists($key, $data['fees'])
    ) {
        return (string) $data['fees'][$key];
    }

    return 'N/A';
}

/**
 * Escape output for safe HTML rendering.
 */
function btcbench_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

try {
    $data = btcbench_fetch_json($apiUrl);

    $fastest = btcbench_fee_value($data, 'fastest');
    $normal = btcbench_fee_value($data, 'halfHour');
    $hour = btcbench_fee_value($data, 'hour');
    $economy = btcbench_fee_value($data, 'economy');

    $datetime = isset($data['datetime']) ? (string) $data['datetime'] : 'Latest BTCBench snapshot';
    $source = isset($data['source']) ? (string) $data['source'] : 'BTCBench public API';
    $attribution = isset($data['attribution']) ? (string) $data['attribution'] : 'BTCBench is independent';

} catch (Throwable $error) {
    $fastest = 'N/A';
    $normal = 'N/A';
    $hour = 'N/A';
    $economy = 'N/A';
    $datetime = 'Unable to load BTCBench fee data';
    $source = 'BTCBench public API';
    $attribution = 'Data unavailable';
    $errorMessage = $error->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTCBench PHP Current Fees Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
            line-height: 1.6;
        }

        .card {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 1.6rem;
        }

        .subtitle {
            margin: 0 0 20px;
            color: #6b7280;
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .fee-row:last-of-type {
            border-bottom: none;
        }

        .fee-label {
            font-weight: 700;
        }

        .fee-value {
            color: #f7931a;
            font-weight: 800;
        }

        .meta {
            margin-top: 20px;
            padding: 12px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            color: #78350f;
            font-size: 0.92rem;
        }

        .error {
            margin-top: 16px;
            padding: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            color: #991b1b;
            font-size: 0.92rem;
        }

        .links {
            margin-top: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .links a {
            color: #4f46e5;
            font-weight: 700;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .disclaimer {
            margin-top: 18px;
            color: #6b7280;
            font-size: 0.88rem;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>BTCBench Current Bitcoin Fees</h1>
        <p class="subtitle">Simple PHP example using the public BTCBench Bitcoin fee API.</p>

        <div class="fee-row">
            <span class="fee-label">Fastest</span>
            <span class="fee-value"><?php echo btcbench_escape($fastest); ?> sat/vB</span>
        </div>

        <div class="fee-row">
            <span class="fee-label">Normal</span>
            <span class="fee-value"><?php echo btcbench_escape($normal); ?> sat/vB</span>
        </div>

        <div class="fee-row">
            <span class="fee-label">Hour</span>
            <span class="fee-value"><?php echo btcbench_escape($hour); ?> sat/vB</span>
        </div>

        <div class="fee-row">
            <span class="fee-label">Economy</span>
            <span class="fee-value"><?php echo btcbench_escape($economy); ?> sat/vB</span>
        </div>

        <div class="meta">
            <strong>Updated:</strong> <?php echo btcbench_escape($datetime); ?><br>
            <strong>Source:</strong> <?php echo btcbench_escape($source); ?><br>
            <strong>Attribution:</strong> <?php echo btcbench_escape($attribution); ?>
        </div>

        <?php if (isset($errorMessage)): ?>
            <div class="error">
                <strong>Error:</strong> <?php echo btcbench_escape($errorMessage); ?>
            </div>
        <?php endif; ?>

        <div class="links">
            <a href="https://www.btcbench.com/" target="_blank" rel="noopener noreferrer">BTCBench</a>
            <a href="https://www.btcbench.com/api-docs.html" target="_blank" rel="noopener noreferrer">API Docs</a>
            <a href="https://www.btcbench.com/tools.html" target="_blank" rel="noopener noreferrer">Tools</a>
            <a href="https://www.btcbench.com/embed.html" target="_blank" rel="noopener noreferrer">Embed Widgets</a>
        </div>

        <p class="disclaimer">
            BTCBench data is provided for informational purposes only. Bitcoin network fees can change quickly.
            Always verify transaction fees in your own wallet before sending Bitcoin.
        </p>
    </main>
</body>
</html>
