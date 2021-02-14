#!/usr/bin/env python3
import os
import json

dataFile = str(os.path.normpath(os.getcwd() + os.sep + os.pardir)) + "/src/data.json"

with open(dataFile) as file:
	data = json.load(file)

with open("remote-master.sh", "w") as file:
	file.write("export ROS_MASTER_URI=http://" + str(data["settings"]["ros"]["coreIP"]) + ":" + str(data["settings"]["ros"]["port"]))
	file.write("\nexport ROS_IP=" + str(data["settings"]["ros"]["riverIP"]))

print("restarting")
#os.system('reboot')
