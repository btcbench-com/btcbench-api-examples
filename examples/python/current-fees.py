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
