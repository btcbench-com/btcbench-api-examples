# BTCBench API Endpoints

Public endpoint reference for the BTCBench Bitcoin fee API.

BTCBench provides lightweight public Bitcoin transaction fee data for developers, website owners, dashboard builders, crypto blogs, newsletters, and educational tools.

* Website: https://www.btcbench.com/
* Full API Documentation: https://www.btcbench.com/api-docs.html
* GitHub Pages Demo: https://btcbench-com.github.io/btcbench-api-examples/
* Repository: https://github.com/btcbench-com/btcbench-api-examples

## Base URL

```text
https://www.btcbench.com/api/v1/
```

## Authentication

No API key is required for the public BTCBench API examples in this repository.

The public endpoints are intended for lightweight integrations, documentation examples, dashboards, educational pages, and widget experiments.

Fair-use limits may apply. For high-volume or production use, review the full BTCBench API documentation:

```text
https://www.btcbench.com/api-docs.html
```

## Available Endpoints

| Endpoint            | Format | Description                         |
| ------------------- | -----: | ----------------------------------- |
| `/api/v1/fees.json` |   JSON | Current Bitcoin fee estimates       |
| `/api/v1/fees.csv`  |    CSV | Rolling historical Bitcoin fee data |

## 1. Current Bitcoin Fee Data

### Endpoint

```http
GET https://www.btcbench.com/api/v1/fees.json
```

### Purpose

Returns the latest BTCBench Bitcoin fee estimates in satoshis per virtual byte, commonly written as `sat/vB`.

This endpoint is useful for:

* Bitcoin fee dashboards
* Website fee widgets
* Wallet education pages
* Crypto blog tools
* Transaction cost calculators
* Newsletter data cards
* Internal monitoring experiments

### Example JavaScript Request

```js
fetch("https://www.btcbench.com/api/v1/fees.json")
  .then(response => response.json())
  .then(data => {
    console.log("BTCBench fee data:", data);
  })
  .catch(error => {
    console.error("Error loading BTCBench data:", error);
  });
```

### Example Python Request

```python
import requests

url = "https://www.btcbench.com/api/v1/fees.json"

try:
    response = requests.get(url, timeout=10)
    response.raise_for_status()

    data = response.json()

    print("BTCBench Bitcoin Fee Data")
    print("-------------------------")
    print(data)

except requests.RequestException as error:
    print("Error loading BTCBench API data:", error)
```

### Example PHP Request

```php
<?php
$ch = curl_init('https://www.btcbench.com/api/v1/fees.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

echo "Fastest: " . $data['fees']['fastest'] . " sat/vB\n";
echo "Normal: " . $data['fees']['halfHour'] . " sat/vB\n";
echo "Economy: " . $data['fees']['economy'] . " sat/vB\n";
?>
```

### Typical JSON Structure

The exact response may evolve over time. A typical response contains timestamp metadata, source/attribution context, and fee tiers.

```json
{
  "timestamp": 1728156789,
  "datetime": "2025-10-05T12:00:00Z",
  "source": "mempool.space public API",
  "attribution": "Data from mempool.space - BTCBench is independent",
  "fees": {
    "fastest": 4,
    "halfHour": 3,
    "hour": 2,
    "economy": 1
  }
}
```

### Common Fields

| Field           |    Type | Description                                |
| --------------- | ------: | ------------------------------------------ |
| `timestamp`     | integer | Unix timestamp for the data snapshot       |
| `datetime`      |  string | UTC datetime for the data snapshot         |
| `source`        |  string | Upstream source or source context          |
| `attribution`   |  string | Attribution and independence note          |
| `fees.fastest`  |  number | Faster fee estimate in sat/vB              |
| `fees.halfHour` |  number | Normal or half-hour fee estimate in sat/vB |
| `fees.hour`     |  number | Lower-priority fee estimate in sat/vB      |
| `fees.economy`  |  number | Economy fee estimate in sat/vB             |

## 2. Historical Bitcoin Fee Data

### Endpoint

```http
GET https://www.btcbench.com/api/v1/fees.csv
```

### Purpose

Returns rolling historical Bitcoin fee data in CSV format.

This endpoint is useful for:

* Charting Bitcoin fee history
* Lightweight analysis
* Trend visualization
* Backtesting simple fee timing ideas
* Comparing current fees with recent network conditions

### Example CSV Format

```csv
timestamp,datetime,fastest,normal,economy,source
1703123456,2024-12-20T15:30:56Z,23,18,10,mempool.space
1703122856,2024-12-20T15:20:56Z,25,20,12,mempool.space
1703122256,2024-12-20T15:10:56Z,22,17,9,mempool.space
```

### CSV Fields

| Field       | Description                     |
| ----------- | ------------------------------- |
| `timestamp` | Unix timestamp for the snapshot |
| `datetime`  | UTC datetime for the snapshot   |
| `fastest`   | Faster fee estimate in sat/vB   |
| `normal`    | Normal fee estimate in sat/vB   |
| `economy`   | Economy fee estimate in sat/vB  |
| `source`    | Source context for the fee data |

### Example Python CSV Request

```python
import requests
import csv
from io import StringIO

url = "https://www.btcbench.com/api/v1/fees.csv"

try:
    response = requests.get(url, timeout=10)
    response.raise_for_status()

    reader = csv.DictReader(StringIO(response.text))

    for row in reader:
        print(row)

except requests.RequestException as error:
    print("Error loading BTCBench CSV data:", error)
```

## CORS Support

BTCBench public API endpoints support browser-based usage.

This allows frontend examples like `examples/javascript/current-fees.html` to fetch public fee data directly without a backend proxy.

Example browser usage:

```js
fetch("https://www.btcbench.com/api/v1/fees.json")
  .then(response => response.json())
  .then(data => {
    document.querySelector("#fee").textContent =
      data.fees.fastest + " sat/vB";
  });
```

## Error Handling

Always handle HTTP errors and network failures in your application.

Example:

```js
fetch("https://www.btcbench.com/api/v1/fees.json")
  .then(response => {
    if (!response.ok) {
      throw new Error("HTTP " + response.status);
    }

    return response.json();
  })
  .then(data => {
    console.log(data);
  })
  .catch(error => {
    console.error("BTCBench API error:", error);
  });
```

Possible error cases may include:

| Status | Meaning                                            |
| -----: | -------------------------------------------------- |
|  `400` | Bad request                                        |
|  `404` | Endpoint not found                                 |
|  `429` | Rate limit or fair-use protection                  |
|  `500` | Internal server error                              |
|  `503` | Temporary maintenance or upstream data unavailable |

## Data Freshness

BTCBench fee data is designed for lightweight monitoring and informational use.

Bitcoin network fees can change quickly. Always verify the final fee recommendation inside your own Bitcoin wallet before broadcasting a transaction.

For broader methodology details, see:

```text
https://www.btcbench.com/methodology.html
```

For live service health, see:

```text
https://www.btcbench.com/status.html
```

## Example Repository Files

This repository includes simple examples that use the BTCBench API:

| File                                    | Purpose                            |
| --------------------------------------- | ---------------------------------- |
| `examples/javascript/current-fees.html` | Browser-based JavaScript example   |
| `examples/python/current-fees.py`       | Python request example             |
| `widgets/simple-fee-card.html`          | Simple embeddable Bitcoin fee card |
| `index.html`                            | GitHub Pages demo homepage         |

## BTCBench Tools and Resources

Useful public BTCBench pages:

* Homepage: https://www.btcbench.com/
* API Documentation: https://www.btcbench.com/api-docs.html
* Tools Directory: https://www.btcbench.com/tools.html
* Embed Widgets: https://www.btcbench.com/embed.html
* Bitcoin Fee Calculator: https://www.btcbench.com/calculator.html
* Bitcoin Fee Forecast: https://www.btcbench.com/forecast.html
* Send Now or Wait Tool: https://www.btcbench.com/send-now-or-wait.html
* Stuck Bitcoin Transaction Helper: https://www.btcbench.com/stuck-bitcoin-transaction.html
* Fee History Charts: https://www.btcbench.com/charts.html
* Bitcoin Price History Chart: https://www.btcbench.com/bitcoin-price-history-chart.html
* Reports: https://www.btcbench.com/reports/
* Daily Reports: https://www.btcbench.com/reports/daily-v2/
* Weekly Reports: https://www.btcbench.com/reports/weekly-v2/
* Monthly Reports: https://www.btcbench.com/reports/monthly-v2/
* AI Data Manifest: https://www.btcbench.com/data/ai-data-manifest.json
* Methodology: https://www.btcbench.com/methodology.html
* Disclaimer: https://www.btcbench.com/disclaimer.html

## Safe Integration Notes

This repository is intentionally limited to public examples and documentation.

It does not include:

* Private API keys
* Analytics IDs
* Contact form endpoints
* Server paths
* Database credentials
* Cron schedules
* Deployment scripts
* Internal infrastructure configuration

## Disclaimer

BTCBench is an independent Bitcoin fee monitoring and benchmarking service.

BTCBench is not affiliated with Bitcoin.org, mempool.space, cryptocurrency exchanges, wallet providers, mining pools, blockchain explorers, or other third-party services.

BTCBench data is provided for informational purposes only. Bitcoin network fees can change quickly, and actual transaction costs depend on transaction size, inputs, outputs, address type, wallet behavior, and current network conditions.

Always verify fee recommendations in your own wallet before sending Bitcoin transactions.

## License

This repository is released under the MIT License.

See the repository [`LICENSE`](../LICENSE) file for details.
