# BTCBench API Examples

Developer examples, endpoint notes, and widget snippets for using public BTCBench Bitcoin fee data in websites, dashboards, blogs, newsletters, and internal tools.

BTCBench is an independent Bitcoin fee monitoring and decision-support site. It publishes Bitcoin fee estimates, fee history charts, calculators, embeddable widgets, report archives, and machine-readable data files.

* Website: https://www.btcbench.com/
* API Documentation: https://www.btcbench.com/api-docs.html
* Tools Directory: https://www.btcbench.com/tools.html
* Embed Widgets: https://www.btcbench.com/embed.html
* Fee Calculator: https://www.btcbench.com/calculator.html
* Fee Forecast: https://www.btcbench.com/forecast.html
* Send Now or Wait Tool: https://www.btcbench.com/send-now-or-wait.html
* Stuck Transaction Helper: https://www.btcbench.com/stuck-bitcoin-transaction.html
* Charts: https://www.btcbench.com/charts.html
* Reports: https://www.btcbench.com/reports/
* Methodology: https://www.btcbench.com/methodology.html
* Disclaimer: https://www.btcbench.com/disclaimer.html

---

## What this repository provides

This repository contains simple examples for developers and website owners who want to display or experiment with BTCBench Bitcoin fee data.

The goal is to keep the examples lightweight, transparent, and easy to copy into small projects without exposing any private BTCBench infrastructure, analytics configuration, form handlers, server paths, or internal automation details.

---

## Repository structure

```text
.
├── docs/
│   └── endpoints.md
├── examples/
│   ├── javascript/
│   │   └── current-fees.html
│   ├── php/
│   │   └── current-fees.php
│   ├── python/
│   │   └── current-fees.py
│   └── wordpress/
│       └── shortcode-example.php
└── widgets/
    └── simple-fee-card.html
```

---

## Quick JavaScript example

```javascript
fetch("https://www.btcbench.com/api/v1/fees.json")
  .then(response => response.json())
  .then(data => {
    console.log("Fastest:",  data.fees.fastest,  "sat/vB");
    console.log("Normal:",   data.fees.halfHour, "sat/vB");
    console.log("Economy:",  data.fees.economy,  "sat/vB");
    console.log("BTC Price:", data.btc_price_usd, "USD");
  })
  .catch(error => console.error("BTCBench error:", error));
```

→ **Full file:** [`examples/javascript/current-fees.html`](examples/javascript/current-fees.html)

---

## Quick Python example

*Uses the standard library—no `requests` dependency required.*

```python
import urllib.request, json

url = "https://www.btcbench.com/api/v1/fees.json"

with urllib.request.urlopen(url, timeout=10) as r:
    data = json.loads(r.read())

fees = data["fees"]
print(f"Fastest:  {fees['fastest']}  sat/vB")
print(f"Normal:   {fees['halfHour']} sat/vB")
print(f"Economy:  {fees['economy']}  sat/vB")
print(f"BTC/USD:  ${data['btc_price_usd']}")
```

→ **Full file:** [`examples/python/current-fees.py`](examples/python/current-fees.py)

---

## Quick PHP example

```php
<?php
$url = 'https://www.btcbench.com/api/v1/fees.json';
$data = json_decode(file_get_contents($url), true);

echo "Fastest:  " . $data['fees']['fastest']  . " sat/vB\n";
echo "Normal:   " . $data['fees']['halfHour'] . " sat/vB\n";
echo "Economy:  " . $data['fees']['economy']  . " sat/vB\n";
echo "BTC/USD:  $" . $data['btc_price_usd']   . "\n";
?>
```

→ **Full file:** [`examples/php/current-fees.php`](examples/php/current-fees.php)

---

## WordPress Shortcode Example

The repository includes a ready-to-use WordPress shortcode implementation. Once added to your theme or plugin, you can display fees using:

```text
[btcbench_fees title="Bitcoin Fees" vbytes="140" show_usd="true"]
```

→ **Full file:** [`examples/wordpress/shortcode-example.php`](examples/wordpress/shortcode-example.php)

---

## Widget Examples

The [`widgets/simple-fee-card.html`](widgets/simple-fee-card.html) file demonstrates how a website owner can display a compact Bitcoin fee card using public BTCBench data.

BTCBench also provides copy-paste iframe widgets for websites, blogs, newsletters, and crypto resource pages:

* Embed Widgets: https://www.btcbench.com/embed.html

---

## Live Previews

Live, styled HTML previews of these files are hosted directly on BTCBench.com:

| File               | Live Preview Link                                                                               |
| ------------------ | ----------------------------------------------------------------------------------------------- |
| JavaScript Example | [Preview JS on BTCBench.com](https://www.btcbench.com/examples/javascript/current-fees.html)    |
| PHP Example        | [Preview PHP on BTCBench.com](https://www.btcbench.com/examples/php/current-fees.php)           |
| WordPress Example  | [Preview WP on BTCBench.com](https://www.btcbench.com/examples/wordpress/shortcode-example.php) |
| Widget Example     | [Preview Widget on BTCBench.com](https://www.btcbench.com/widgets/simple-fee-card.html)         |

---

## Public BTCBench Resources

BTCBench provides public web pages and data resources for Bitcoin fee monitoring and transaction-fee decision support.

| Resource          | URL                                                   |
| ----------------- | ----------------------------------------------------- |
| Current fee API   | `https://www.btcbench.com/api/v1/fees.json`           |
| Current fee CSV   | `https://www.btcbench.com/api/v1/fees.csv`            |
| API status        | `https://www.btcbench.com/api/v1/status.json`         |
| API docs          | `https://www.btcbench.com/api-docs.html`              |
| Tools directory   | `https://www.btcbench.com/tools.html`                 |
| Embed widget page | `https://www.btcbench.com/embed.html`                 |
| Daily reports     | `https://www.btcbench.com/reports/daily-v2/`          |
| Weekly reports    | `https://www.btcbench.com/reports/weekly-v2/`         |
| Monthly reports   | `https://www.btcbench.com/reports/monthly-v2/`        |
| AI data manifest  | `https://www.btcbench.com/data/ai-data-manifest.json` |
| Methodology       | `https://www.btcbench.com/methodology.html`           |

---

## Example Use Cases

BTCBench data and examples can be useful for:

* Bitcoin fee dashboards
* Crypto blogs and educational websites
* Wallet education pages
* Transaction-cost widgets
* Bitcoin newsletter resource sections
* Fee-monitoring side projects
* Internal monitoring dashboards
* Developer experiments with BTC fee data
* AI-search and citation-friendly Bitcoin fee references

---

## Safe Integration Notes

These examples are intentionally simple and public-facing. They do not include:

* Private API keys
* Authentication secrets
* Analytics IDs
* Contact form endpoints
* Server paths
* Cron schedules
* Database details
* Internal deployment scripts
* Private monitoring or infrastructure configuration

When building on these examples, avoid committing local credentials, `.env` files, private tokens, analytics configuration, or production server details to public repositories.

---

## Independence and Disclaimer

BTCBench is independent and is not affiliated with mempool.space, Bitcoin.org, CoinGecko, Yahoo Finance, Blockchair, cryptocurrency exchanges, wallet providers, mining pools, blockchain explorers, or other third-party services.

BTCBench data is provided for informational purposes only. Bitcoin network fees can change quickly, and actual transaction costs depend on transaction size, inputs, outputs, address type, wallet behavior, mempool conditions, and the user’s wallet settings.

Always verify fee recommendations in your own wallet before sending Bitcoin transactions.

Full disclaimer: https://www.btcbench.com/disclaimer.html

---

## Contributing

Small improvements to examples, documentation, error handling, and integration notes are welcome.

Useful contribution ideas:

* Add another language example (e.g., Go, Rust, Ruby)
* Improve browser display examples
* Add framework-specific snippets (e.g., React, Vue)
* Improve endpoint documentation
* Add accessibility improvements to widget examples
* Improve defensive error handling

Please keep contributions neutral, factual, and focused on public BTCBench resources.

---

## Attribution & License

This repository is released under the **MIT License**.

You may use, copy, modify, and adapt the example code in this repository, subject to the terms of the MIT License. See the `LICENSE` file for details.

If you use these examples on your site or in a blog post, a link back to [BTCBench.com](https://www.btcbench.com) is highly appreciated!
