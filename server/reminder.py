#! /usr/bin/python

import sys
import time
from twilio.rest import TwilioRestClient

account_sid = "AC350f381e376b7b23a14805e92f1e4193"
auth_token = "23c638848de2ef9f892703a896795589"
client = TwilioRestClient(account_sid,auth_token)

delay = int(sys.argv[1])
number = sys.argv[2]
reminder = sys.argv[3]
time.sleep(delay)
client.messages.create(to=number,from_="+15615718398",body='Reminder: '+reminder)
