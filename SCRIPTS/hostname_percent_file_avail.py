import subprocess
hostname = subprocess.check_output('hostname').strip('\n')
print(hostname)
avail_space = subprocess.check_output("stat -f -c\"%a\" /", shell=True).strip('\n')
print(avail_space)
total_space = subprocess.check_output("stat -f -c\"%b\" /", shell=True).strip('\n')
print(total_space)
percentage_free = int((float(avail_space) / float(total_space)) * 100.0)
print(percentage_free)

api_command_str = "python fbbs_api.py " + hostname + " " + str(percentage_free) + " '(" + avail_space + "/" + total_space + ")'"
print(api_command_str)
fbbs_api_obj = subprocess.check_output(api_command_str, shell=True)
