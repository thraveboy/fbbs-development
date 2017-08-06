import subprocess
import string
import time
import sys

update_rate_mins = 5
if (len(sys.argv) > 1):
    update_rate_mins = float(sys.argv[1])

while 1:
    ticker_values = subprocess.check_output('curl https://www.worldcoinindex.com/ | sed -f coin-scrap.sed | sed -f sed-clean.sed | grep Ticker', shell=True)
    ticker_value_array = string.split(ticker_values,'\n')
    ticker_values_in_array = []
    for ticker_value in ticker_value_array:
        print(ticker_value)
        ticker_values_in_array.append(string.split(ticker_value,' '))
    for ticker_value_in_array in ticker_values_in_array:
        if (ticker_value_in_array[0] == "Ticker:BTC") or (ticker_value_in_array[0] == "Ticker:BCC") or (ticker_value_in_array[0] == "Ticker:ETH"):
            ticker_value = str(ticker_value_in_array[1])
            api_command_str = "python fbbs_api.py '" + str(ticker_value_in_array[0]) + ":" + str(update_rate_mins) + "min' " + str(ticker_value)
            print(api_command_str)
            fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
    time.sleep(60 * update_rate_mins)
