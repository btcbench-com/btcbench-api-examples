# BTCBench API Examples

BTCBench provides free Bitcoin transaction fee data, charts, calculators, and report archives for developers and Bitcoin users.

- Website: https://www.btcbench.com/
- API Documentation: https://www.btcbench.com/api-docs.html
- Fee Calculator: https://www.btcbench.com/calculator.html
- Charts: https://www.btcbench.com/charts.html
- Reports: https://www.btcbench.com/reports/
- Methodology: https://www.btcbench.com/methodology.html

## What is BTCBench?

BTCBench is an independent Bitcoin fee monitoring and benchmarking service. It helps users, developers, and website owners check Bitcoin transaction fee conditions and understand BTC network cost trends.

## Features

- Bitcoin fee estimates
- JSON and CSV endpoints
- No API key required
- Developer-friendly API examples
- Fee calculator and chart pages
- Daily, weekly, and monthly report archives
- Machine-readable data for tools and AI discovery
  
  ## Repository Structure

- `docs/endpoints.md` — BTCBench API endpoint documentation and useful links
- `examples/javascript/current-fees.html` — simple browser example using the BTCBench API
- `examples/python/current-fees.py` — simple Python example using the BTCBench API
- `widgets/simple-fee-card.html` — simple embeddable Bitcoin fee card example

## Widget Example

The `widgets/simple-fee-card.html` file shows how a website owner can display a small Bitcoin fee card using BTCBench API data.

For production use, review the BTCBench API documentation:

https://www.btcbench.com/api-docs.html

## Example Use Cases

BTCBench data can be used for:

- Bitcoin fee dashboards
- Crypto blogs and websites
- Wallet education pages
- Transaction cost widgets
- Bitcoin newsletter tools
- Developer experiments

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
