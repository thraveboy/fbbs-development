import subprocess
import string
import fileinput
import json
import time
import sys

currencies = ['BTC', 'BCC', 'ETH', 'STRAT', 'XMR', 'OMG', 'LTH', 'GNT', 'WAVES','QTUM', 'PIVX', 'SC', 'NEO', 'LTC', 'DASH', 'PAY', 'GBYTE', 'FCT', 'ETC', 'XVG']

update_rate_mins = 5
if (len(sys.argv) > 1):
  update_rate_mins = int(float(sys.argv[1]))

while 1:
  json_input = subprocess.check_output("curl https://www.worldcoinindex.com/apiservice/json?key=PdEUH2ZNFMW4kaxKO1gGoFHJS", shell=True)

  json_decoded = json.loads(json_input)
  coin_values = json_decoded['Markets']

  for coin in coin_values:
    try:
      coin_name = str(coin['Label'].replace("/BTC", ""))
      if coin_name in currencies:
        api_command_str = "python fbbs_api_cloud.py 'TradeVolume" + ":" + coin_name + ":" + str(update_rate_mins) + "min'"
        print(api_command_str)
        fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
        api_command_str = "python fbbs_api_cloud.py 'TradeVolume" + ":" + coin_name + ":" + str(update_rate_mins) + "min" + " " + str(coin['Volume_24h']) + "'"
        print(api_command_str)
        fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
    except:
      pass

  time.sleep(60*update_rate_mins)
