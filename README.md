# BTCBench API Examples

BTCBench provides free Bitcoin transaction fee data, charts, calculators, widgets, and report archives for developers and Bitcoin users.

- Website: https://www.btcbench.com/
- API Documentation: https://www.btcbench.com/api-docs.html
- Fee Calculator: https://www.btcbench.com/calculator.html
- Tools: https://www.btcbench.com/tools.html
- Charts: https://www.btcbench.com/charts.html
- Reports: https://www.btcbench.com/reports/
- Methodology: https://www.btcbench.com/methodology.html
- Disclaimer: https://www.btcbench.com/disclaimer.html

## What is BTCBench?

BTCBench is an independent Bitcoin fee monitoring and benchmarking service. It helps users, developers, and website owners check Bitcoin transaction fee conditions and understand BTC network cost trends.

BTCBench data can be useful for Bitcoin dashboards, crypto blogs, wallet education pages, transaction fee widgets, newsletters, and developer experiments.

## Features

* Bitcoin fee estimates
* JSON and CSV endpoints
* No API key required
* Developer-friendly API examples
* Simple widget example for websites
* Fee calculator and chart pages
* Daily, weekly, and monthly report archives
* Machine-readable data for tools and AI discovery

## Repository Structure

* `docs/endpoints.md` — BTCBench API endpoint documentation and useful links
* `examples/javascript/current-fees.html` — simple browser example using the BTCBench API
* `examples/python/current-fees.py` — simple Python example using the BTCBench API
* `widgets/simple-fee-card.html` — simple embeddable Bitcoin fee card example

## Quick JavaScript Example

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

## Python Example

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

## Widget Example

The `widgets/simple-fee-card.html` file shows how a website owner can display a small Bitcoin fee card using BTCBench API data.

For production use, review the BTCBench API documentation:

https://www.btcbench.com/api-docs.html

## Example Use Cases

BTCBench data can be used for:

* Bitcoin fee dashboards
* Crypto blogs and websites
* Wallet education pages
* Transaction cost widgets
* Bitcoin newsletter tools
* Developer experiments
* Internal monitoring tools

## Useful BTCBench Links

* Homepage: https://www.btcbench.com/
* API Documentation: https://www.btcbench.com/api-docs.html
* Fee Calculator: https://www.btcbench.com/calculator.html
* Charts: https://www.btcbench.com/charts.html
* Reports: https://www.btcbench.com/reports/
* Daily Reports: https://www.btcbench.com/reports/daily-v2/
* Weekly Reports: https://www.btcbench.com/reports/weekly-v2/
* Monthly Reports: https://www.btcbench.com/reports/monthly-v2/
* AI Data Manifest: https://www.btcbench.com/data/ai-data-manifest.json
* Methodology: https://www.btcbench.com/methodology.html
* Disclaimer: https://www.btcbench.com/disclaimer.html

## Disclaimer

BTCBench is independent and is not affiliated with mempool.space, Bitcoin.org, cryptocurrency exchanges, wallet providers, mining pools, blockchain explorers, or other third-party services.

BTCBench data is provided for informational purposes only. Bitcoin network fees can change quickly, and actual transaction costs depend on transaction size, inputs, outputs, address type, wallet behavior, and current network conditions.

Always verify fee recommendations in your own wallet before sending Bitcoin transactions.
