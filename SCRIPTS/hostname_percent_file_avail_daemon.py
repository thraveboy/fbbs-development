import subprocess
import time

hostname = subprocess.check_output('hostname').strip('\n')
total_space = subprocess.check_output("stat -f -c\"%b\" /", shell=True).strip('\n')

while 1:
    avail_space = subprocess.check_output("stat -f -c\"%a\" /", shell=True).strip('\n')
    percentage_free = int((float(avail_space) / float(total_space)) * 100.0)
    api_command_str = "python fbbs_api.py monitor_" + hostname + "_avail_disk '" + str(percentage_free) + "%'"
    fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
    time.sleep(10)
