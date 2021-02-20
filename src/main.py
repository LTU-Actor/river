#!/usr/bin/env python

import os
import rospy
import json
import pathlib
import logging
from std_msgs.msg import String
from std_msgs.msg import Int8
from rosgraph_msgs.msg import Log
from std_msgs.msg import Bool

dataFile = "/home/ubuntu/catkin_ws/src/river/src/data.json"
logFile = "/home/ubuntu/catkin_ws/src/river/src/main.log"

lastUpdate = None
data = None

assert os.path.exists(dataFile), 'No such file: \'data.json\''

def update():
	global lastUpdate
	global data

	with open(dataFile) as file:
		data = json.load(file)

	lastUpdate = pathlib.Path(dataFile).stat().st_mtime


def textCB(msg):
	data["show"]["text"]["msg"] = str(msg.data)

	jsonObj = json.dumps(data, indent = 4)

	with open(dataFile, "w") as file:
		file.write(jsonObj)
	update()

def statusCB(msg):
	data["show"]["status"]["msg"] = str(msg.data)

	jsonObj = json.dumps(data, indent = 4)

	with open(dataFile, "w") as file:
		file.write(jsonObj)

	update()

def logCB(msg):
	data["auto"]["data"]["msg"].append(
		[msg.level, 
		msg.header.stamp.to_sec(), 
		msg.name, 
		msg.topics, 
		msg.msg])

	jsonObj = json.dumps(data, indent = 4)

	with open(dataFile, "w") as file:
		file.write(jsonObj)

	update()

def enabledCB(msg):
	data["auto"]["data"]["dbw_enabled"] = msg.data

	jsonObj = json.dumps(data, indent = 4)

	with open(dataFile, "w") as file:
		file.write(jsonObj)

	update()

if __name__ == '__main__':
	rospy.init_node('display_node')

	update()
	data["auto"]["data"]["msg"] = []

	jsonObj = json.dumps(data, indent = 4)
	
	with open(dataFile, "w") as file:
		file.write(jsonObj)


	rospy.Subscriber("display/text", String, textCB)
	rospy.Subscriber("display/status", Int8, statusCB)
	rospy.Subscriber("rosout", Log, logCB)
	rospy.Subscriber("/vehicle/dbw_enabled", Bool, enabledCB)

	rate = rospy.Rate(10)
	while not rospy.is_shutdown():
		if (lastUpdate != pathlib.Path(dataFile).stat().st_mtime):
			update()
		rate.sleep()
