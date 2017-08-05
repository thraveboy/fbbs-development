import subprocess
import string
ticker_values = subprocess.check_output('curl https://www.worldcoinindex.com/ | sed -f coin-scrap.sed | sed -f sed-clean.sed | grep Ticker', shell=True)
ticker_value_array = string.split(ticker_values,'\n')
ticker_values_in_array = []
for ticker_value in ticker_value_array:
    print(ticker_value)
    ticker_values_in_array.append(string.split(ticker_value,' '))
for ticker_value_in_array in ticker_values_in_array:
    print(ticker_value_in_array[0])
    print(ticker_value_in_array[1])

#api_command_str = "python fbbs_api.py " + "monitor_" + hostname + "_memused '" + str(mem_used_percentage) + "%'"
# fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
