import os
import json

dataFile = "/home/ubuntu/catkin_ws/src/river/src/data.json"

with open(dataFile) as file:
	data = json.load(file)

print(data)

with open("remote-master.sh", "w") as file:
        file.write("export ROS_MASTER_URI=http://" + str(data["settings"]["ros"]["coreIP"]) + ":" + str(data["settings"]["ros"]["port"]))
	file.write("\nexport ROS_IP=" + str(data["settings"]["ros"]["riverIP"]))


