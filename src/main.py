#!/usr/bin/env python

import os
import rospy
import json
import time
import pathlib
import logging
from std_msgs.msg import String
from std_msgs.msg import Int8
from rosgraph_msgs.msg import Log
from std_msgs.msg import Bool

dataFile = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json"
logFile = "/home/ubuntu/catkin_ws/src/river/src/main.log"

lastUpdate = None
data = None

assert os.path.exists(dataFile), 'No such file: \'dataTemp.json\''

root_logger= logging.getLogger()
root_logger.setLevel(logging.DEBUG)
handler = logging.FileHandler('main.log', 'w', 'utf-8')
handler.setFormatter(logging.Formatter("%(levelname)s %(asctime)s: %(name)s - %(message)s", "%H:%M:%S"))
root_logger.addHandler(handler)

def update():
	try:
		global lastUpdate
		global data

		with open(dataFile) as file:
			data = json.load(file)

		lastUpdate = pathlib.Path(dataFile).stat().st_mtime
	except Exception as e:
		logging.error("Update Function failed. \n\tError: " + str(e))
		return None
	return True


def textMsgCB(msg):
	try:
		data["show"]["text"]["msg"] = str(msg.data)

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)
		update()
	except Exception as e:
		logging.error("Call back function textMsgCB failed. \n\tError: " + str(e))


def textColorCB(msg):
	try:
		if (msg.data)
		data["show"]["text"]["color"] = str(msg.data)

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)
		update()
	except Exception as e:
		logging.error("Call back function textColorCB failed. \n\tError: " + str(e))

def statusEnabledCB(msg):
	try:
		data["show"]["status"]["enabled"] = msg.data

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)

		update()
	except Exception as e:
		logging.error("Call back function statusEnabledCB failed. \n\tError: " + str(e))

def statusNumCB(msg):
	try:
		data["show"]["status"]["msg"] = str(msg.data)

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)

		update()
	except Exception as e:
		logging.error("Call back function statusNumCB failed. \n\tError: " + str(e))

def statusColorCB(msg):
	try:
		data["show"]["status"]["color"] = str(msg.data)

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)
		update()
	except Exception as e:
		logging.error("Call back function statusColorCB failed. \n\tError: " + str(e))

def autoEnabledCB(msg):
	try:
		data["auto"]["enabled"] = msg.data

		jsonObj = json.dumps(data, indent = 4)

		with open(dataFile, "w") as file:
			file.write(jsonObj)

		update()
	except Exception as e:
		logging.error("Call back function autoEnabledCB failed. \n\tError: " + str(e))

def logCB(msg):
	try:
		if data["auto"]["enabled"]:
			data["auto"]["data"]["msgs"].append(
				{"level": msg.level, 
				"ROStime": msg.header.stamp.to_sec(), 
				"PItime": int(time.time()), 
				"name": msg.name, 
				"topics": msg.topics, 
				"msg": msg.msg})

			jsonObj = json.dumps(data, indent = 4)

			with open(dataFile, "w") as file:
				file.write(jsonObj)

			update()
	except Exception as e:
		logging.error("Call back function logCB failed. \n\tError: " + str(e))

def enabledCB(msg):
	try:
		if data["auto"]["enabled"]:
			data["auto"]["data"]["dbw_enabled"] = msg.data

			jsonObj = json.dumps(data, indent = 4)

			with open(dataFile, "w") as file:
				file.write(jsonObj)

			update()
	except Exception as e:
		logging.error("Call back function enabledCB failed. \n\tError: " + str(e))

if __name__ == '__main__':
	rospy.init_node('display_node')
	print("display_node started!")

	while update() is None:
		logging.error("function call update() returned None state, looping: \n\tError: " + str(e))
		time.sleep(.1)
	data["auto"]["data"]["msgs"] = []

	jsonObj = json.dumps(data, indent = 4)

	with open(dataFile, "w") as file:
		file.write(jsonObj)

	rospy.Subscriber("display/text/msg", String, textMsgCB)
	rospy.Subscriber("display/text/color", String, textColorCB)

	rospy.Subscriber("display/status/enabled", Bool, statusEnabledCB)
	rospy.Subscriber("display/status/num", Int8, statusNumCB)
	rospy.Subscriber("display/status/color", String, statusColorCB)

	rospy.Subscriber("display/auto/enabled", Bool, autoEnabledCB)

	rospy.Subscriber("rosout", Log, logCB)
	rospy.Subscriber("/vehicle/dbw_enabled", Bool, enabledCB)

	rate = rospy.Rate(10)
	while not rospy.is_shutdown():
		try:
			if (lastUpdate != pathlib.Path(dataFile).stat().st_mtime):
				if update() is None:
					logging.error("function call update() returned None state: \n\tError: " + str(e))
			rate.sleep()
		except KeyboardInterrupt:
			exit(0)
		except Exception as e:
			logging.info("try&except Exit: \n\tError: " + str(e))
