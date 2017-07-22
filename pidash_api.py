import requests
import json

class PiDashAPI:
    """Class object for interacting with PiDash API"""
    def __init__(self, in_host, in_username, in_password, in_token=""):
        self.host = in_host
        self.username = in_username
        self.password = in_password
        self.token = in_token
    def set_command(self, table_name, data):
        self.table_name = table_name
        self.data = data
    def send(self):
        token_request = requests.post(self.host, data={'username': self.username, 'password': self.password})
        print(token_request.status_code, token_request.reason)
        print(token_request.text)
        json_decoder = json.JSONDecoder()
        decoded_json = json_decoder.decode(token_request.text)
        if "token" in decoded_json:
          print(decoded_json["token"])

api_test_obj = PiDashAPI("http://localhost/pidash-api.php", "test1_user", "test1_password")

print api_test_obj.username
print api_test_obj.password
print api_test_obj.host
print api_test_obj.token

api_test_obj.set_command("test_table", "test data")
print api_test_obj.table_name
print api_test_obj.data
api_test_obj.send()



