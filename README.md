# BTCBench API Examples

Developer examples, endpoint notes, and widget snippets for using public BTCBench Bitcoin fee data in websites, dashboards, blogs, newsletters, and internal tools.

BTCBench is an independent Bitcoin fee monitoring and decision-support site. It publishes Bitcoin fee estimates, fee history charts, calculators, embeddable widgets, report archives, and machine-readable data files.

- Website: https://www.btcbench.com/
- API Documentation: https://www.btcbench.com/api-docs.html
- Tools Directory: https://www.btcbench.com/tools.html
- Embed Widgets: https://www.btcbench.com/embed.html
- Fee Calculator: https://www.btcbench.com/calculator.html
- Fee Forecast: https://www.btcbench.com/forecast.html
- Send Now or Wait Tool: https://www.btcbench.com/send-now-or-wait.html
- Stuck Transaction Helper: https://www.btcbench.com/stuck-bitcoin-transaction.html
- Charts: https://www.btcbench.com/charts.html
- Reports: https://www.btcbench.com/reports/
- Methodology: https://www.btcbench.com/methodology.html
- Disclaimer: https://www.btcbench.com/disclaimer.html

## What this repository provides

This repository contains simple examples for developers and website owners who want to display or experiment with BTCBench Bitcoin fee data.

The goal is to keep the examples lightweight, transparent, and easy to copy into small projects without exposing any private BTCBench infrastructure, analytics configuration, form handlers, server paths, or internal automation details.

## Public BTCBench resources

BTCBench provides public web pages and data resources for Bitcoin fee monitoring and transaction-fee decision support.

| Resource | URL |
|---|---|
| Current fee API | `https://www.btcbench.com/api/v1/fees.json` |
| Current fee CSV | `https://www.btcbench.com/api/v1/fees.csv` |
| API status | `https://www.btcbench.com/api/v1/status.json` |
| API docs | `https://www.btcbench.com/api-docs.html` |
| Tools directory | `https://www.btcbench.com/tools.html` |
| Embed widget page | `https://www.btcbench.com/embed.html` |
| Daily reports | `https://www.btcbench.com/reports/daily-v2/` |
| Weekly reports | `https://www.btcbench.com/reports/weekly-v2/` |
| Monthly reports | `https://www.btcbench.com/reports/monthly-v2/` |
| AI data manifest | `https://www.btcbench.com/data/ai-data-manifest.json` |
| Methodology | `https://www.btcbench.com/methodology.html` |

## Features highlighted by BTCBench

- Bitcoin transaction fee estimates
- JSON and CSV data endpoints
- No API key required for the public examples
- Browser-friendly access for simple integrations
- Fee calculator and decision tools
- Bitcoin fee forecast and send-now-or-wait guidance
- Stuck transaction helper for underpriced pending transactions
- Historical Bitcoin fee charts
- Bitcoin DCA and price-history tools
- Free iframe widgets for blogs and websites
- Daily, weekly, and monthly report archives
- Machine-readable report and manifest files for developer and AI discovery workflows

## Repository structure

```text
README.md
/docs
  endpoints.md
/examples
  /javascript
    current-fees.html
  /python
    current-fees.py
/widgets
  simple-fee-card.html
```

## Quick JavaScript example

```js
fetch("https://www.btcbench.com/api/v1/fees.json")
  .then((response) => {
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }
    return response.json();
  })
  .then((data) => {
    console.log("BTCBench fee data:", data);
  })
  .catch((error) => {
    console.error("Error loading BTCBench data:", error);
  });
```

## Quick Python example

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

## Widget examples

The `widgets/simple-fee-card.html` file demonstrates how a website owner can display a compact Bitcoin fee card using public BTCBench data.

BTCBench also provides copy-paste iframe widgets for websites, blogs, newsletters, and crypto resource pages:

- Embed Widgets: https://www.btcbench.com/embed.html
- Tools Directory: https://www.btcbench.com/tools.html
- API Documentation: https://www.btcbench.com/api-docs.html

## Example use cases

BTCBench data and examples can be useful for:

- Bitcoin fee dashboards
- Crypto blogs and educational websites
- Wallet education pages
- Transaction-cost widgets
- Bitcoin newsletter resource sections
- Fee-monitoring side projects
- Internal monitoring dashboards
- Developer experiments with BTC fee data
- AI-search and citation-friendly Bitcoin fee references

## BTCBench tools

BTCBench includes multiple public tools for Bitcoin users and website owners:

- Bitcoin Fee Calculator: https://www.btcbench.com/calculator.html
- Bitcoin Fee Forecast: https://www.btcbench.com/forecast.html
- Send Bitcoin Now or Wait?: https://www.btcbench.com/send-now-or-wait.html
- Stuck Bitcoin Transaction Helper: https://www.btcbench.com/stuck-bitcoin-transaction.html
- Best Time to Send Bitcoin: https://www.btcbench.com/best-time-to-send-bitcoin.html
- Bitcoin Fee Charts: https://www.btcbench.com/charts.html
- Bitcoin DCA Calculator: https://www.btcbench.com/dca.html
- Bitcoin Price History Chart: https://www.btcbench.com/bitcoin-price-history-chart.html
- Embed Bitcoin Fee Widgets: https://www.btcbench.com/embed.html

## Reports and machine-readable data

BTCBench publishes report archives and machine-readable metadata intended to make Bitcoin fee history easier to inspect, cite, and reuse.

- Reports: https://www.btcbench.com/reports/
- Daily Reports: https://www.btcbench.com/reports/daily-v2/
- Weekly Reports: https://www.btcbench.com/reports/weekly-v2/
- Monthly Reports: https://www.btcbench.com/reports/monthly-v2/
- AI Data Manifest: https://www.btcbench.com/data/ai-data-manifest.json

For details on data collection, calculations, report generation, and limitations, see the BTCBench methodology page:

https://www.btcbench.com/methodology.html

## Safe integration notes

These examples are intentionally simple and public-facing. They do not include:

- Private API keys
- Authentication secrets
- Analytics IDs
- Contact form endpoints
- Server paths
- Cron schedules
- Database details
- Internal deployment scripts
- Private monitoring or infrastructure configuration

When building on these examples, avoid committing local credentials, `.env` files, private tokens, analytics configuration, or production server details.

## Independence and disclaimer

BTCBench is independent and is not affiliated with mempool.space, Bitcoin.org, CoinGecko, Yahoo Finance, Blockchair, cryptocurrency exchanges, wallet providers, mining pools, blockchain explorers, or other third-party services.

BTCBench data is provided for informational purposes only. Bitcoin network fees can change quickly, and actual transaction costs depend on transaction size, inputs, outputs, address type, wallet behavior, mempool conditions, and the user’s wallet settings.

Always verify fee recommendations in your own wallet before sending Bitcoin transactions.

Full disclaimer:

https://www.btcbench.com/disclaimer.html

## Contributing

Small improvements to examples, documentation, error handling, and integration notes are welcome.

Useful contribution ideas:

- Add another language example
- Improve browser display examples
- Add framework-specific snippets
- Improve endpoint documentation
- Add accessibility improvements to widget examples
- Improve defensive error handling

Please keep contributions neutral, factual, and focused on public BTCBench resources.

## License

This repository is released under the MIT License.

You may use, copy, modify, and adapt the example code in this repository, subject to the terms of the MIT License. See the LICENSE file for details.
