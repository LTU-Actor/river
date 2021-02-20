#!/usr/bin/env python

import os
import rospy
import json
import pathlib
import logging
from std_msgs.msg import String
from std_msgs.msg import Int8
from rosgraph_msgs.msg import Log

dataFile = "/home/ubuntu/catkin_ws/src/river/src/data.json"
logFile = "/home/ubuntu/catkin_ws/src/river/src/main.log"

lastUpdate = None
data = None
text = None
status = None
log = None

logging.basicConfig(filename=logFile, encoding='utf-8', level=logging.INFO)

assert os.path.exists(dataFile), 'No such file: \'data.json\''

def update():
	global lastUpdate
	global data
	global text
	global status

	print("Update!")

	with open(dataFile) as file:
		data = json.load(file)

		text = data["show"]["text"]["msg"]
		status = data["show"]["status"]["msg"]

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
	print(str(msg.level) + " : " + str(msg.name) + " : " + str(msg.topics) + " : " + str(msg.msg) + " : " + str(msg.header.stamp.to_sec()))
	#logging.info(str(msg.level) + " : " + str(msg.name) + " : " + str(msg.topics) + " : " + str(msg.msg) + " : " + str(msg.header.stamp.to_sec()))

if __name__ == '__main__':
	rospy.init_node('display_node')
	print('display_node')
	#logging.info('display_node')

	rospy.Subscriber("display/text", String, textCB)
	rospy.Subscriber("display/status", Int8, statusCB)
	rospy.Subscriber("rosout", Log, logCB)

	rate = rospy.Rate(10)
	while not rospy.is_shutdown():
		if (lastUpdate != pathlib.Path(dataFile).stat().st_mtime):
			update()
		rate.sleep()
