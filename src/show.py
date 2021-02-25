#!/usr/bin/env python3

import os
import time
import json
import board
import pathlib
import neopixel
import logging
from PIL import Image, ImageDraw, ImageFont, ImageOps

font = None
data = None
pixels = None
lastUpdate = None

displayQueue = []
currentMsg = None

dataFile = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json"

offset = 0
count = 0

statusColors = {
	"success": "#4F8A10",
	"info" : "#00529B",
	"warning": "#9F6000",
	"error": "#D8000C"
}

assert os.geteuid() is 0, '\'show.py\' must be run as administrator!'
assert os.path.exists(dataFile), 'No such file: \'dataTemp.json\''

root_logger= logging.getLogger()
root_logger.setLevel(logging.DEBUG)
handler = logging.FileHandler('show.log', 'w', 'utf-8')
handler.setFormatter(logging.Formatter("%(levelname)s %(asctime)s: %(name)s - %(message)s", "%H:%M:%S"))
root_logger.addHandler(handler)

def update():
	def verify(data, excpectedType):
		try:
			if type(data) is not excpectedType:
				return excpectedType(data)
		except Exception as e:
			logging.error("In update() verify(): " + str(data) + " could not be converted to " + excpectedType + ". \n\tError: " + str(e))
			return None

	global font
	global data
	global pixels
	global lastUpdate
	global offset

	if data is None:
		proirText = None
	else:
		proirText = data["show"]["text"]

	with open(dataFile) as file:
		data = json.load(file)
	lastUpdate = pathlib.Path(dataFile).stat().st_mtime

	if not (proirText == data["show"]["text"]):
		offset = 0
	
	try:
		#Verify Setting atributes
		data = verify(data["settings"]["rate"], float)
		data = verify(data["settings"]["heartbeat"]["color"], str)
		data = verify(data["settings"]["heartbeat"]["enabled"], bool)

		#Veridy Display atributes
		data = verify(data["display"]["brightness"], float)
		data = verify(data["display"]["height"], int)
		data = verify(data["display"]["width"], int)

		#Verify Auto atributes
		data = verify(data["auto"]["enabled"], bool)
		data = verify(data["auto"]["level"], int)
		data = verify(data["auto"]["duration"], int)
		data = verify(data["auto"]["timeout"], int)
		data = verify(data["auto"]["data"]["dbw_enabled"], bool)

		#Verify Show atributes
		data = verify(data["show"]["status"]["msg"], int)
		data = verify(data["show"]["status"]["enabled"], bool)
		data = verify(data["show"]["status"]["colorGrade"], bool)
		data = verify(data["show"]["status"]["clear0"], bool)
		data = verify(data["show"]["status"]["color"], str)
		data = verify(data["show"]["text"]["msg"], str)
		data = verify(data["show"]["text"]["color"], str)

		#Verify Fonts atributes
		data = verify(data["font"]["path"], str)
		data = verify(data["font"]["size"], int)
	except Exception as e:
		print(e)
		time.sleep(100)

	font = ImageFont.truetype(data["font"]["path"], data["font"]["size"])

	#Setup pixels for display
	if pixels is not None:
		del pixels
	pixels = neopixel.NeoPixel(
		board.D18,
		data["display"]["width"] * data["display"]["height"],
		brightness = data["display"]["brightness"],
		auto_write = False)

def auto():
	global displayQueue
	global currentMsg
	global count

	if not data["auto"]["enabled"]:
		return
	
	for msg in data["auto"]["data"]["msgs"]:
		if msg["level"] >= data["auto"]["level"]:
			displayQueue.append(msg)

	data["auto"]["data"]["msgs"] = []

	#if the message has been shown for the duration amount clear it
	if currentMsg is not None:
		if (int(time.time()) - currentMsg["PItime"] > data["auto"]["duration"]):
			currentMsg = None

	#if message is clear find the next one or print no errors
	if currentMsg is None:
		while displayQueue:
			currentMsg = displayQueue.pop()
			if (int(time.time()) - currentMsg["PItime"] < data["auto"]["timeout"]):
				count = 0
				currentMsg["PItime"] = int(time.time())
				data["show"]["text"]["msg"] = currentMsg["msg"]
				data["show"]["status"]["msg"] = currentMsg["level"]

				jsonObj = json.dumps(data, indent = 4)

				with open(dataFile, "w") as file:
					file.write(jsonObj)
				
				update()
				return
			else:
				currentMsg = None
		if currentMsg is None:
				if data["auto"]["data"]["dbw_enabled"]:
					data["show"]["text"]["msg"] = "Sript Running"
				else:
					data["show"]["text"]["msg"] = "Manual Mode"
				data["show"]["status"]["msg"] = "0"
	
				jsonObj = json.dumps(data, indent = 4)

				with open(dataFile, "w") as file:
					file.write(jsonObj)
				
				update()


def show():
	def getIndex(x, y):
		try:
			if x % 2 != 0:
				return (x * data["display"]["height"]) + y
			return (x * data["display"]["height"]) + (data["display"]["height"] - 1 - y)
		except Exception as e:
			logging.error("In show() getIndex(): index calculation failed. \n\tError: " + str(e))
			return None
	
	def setColor(hexColor):
		try:
			colorHex = hexColor.lstrip('#')
			return tuple(int(colorHex[i:i+2], 16) for i in (0, 2, 4))
		except Exception as e:
			logging.error("In show() setColor(): conversion of <" + str(hexColor) + "> hex to RGB failed. \n\tError: " + str(e))
			return None

	statusmsg = data["show"]["status"]["msg"]

	#set level
	try:
		level = int(statusmsg)
	except Exception as e:
		logging.error("In show(): debug level can not be converted to int. \n\tError: " + str(e))
		return 1

	#set text color
	textColor = setColor(data["show"]["text"]["color"])
	if textColor is None:
		return 1

	#set status color
	try:
		if data["show"]["status"]["colorGrade"]:
			if (level < 2):
				statusColor = statusColors["success"]
			elif (level < 4):
				statusColor = statusColors["info"]
			elif (level < 8):
				statusColor = statusColors["warning"]
			else:
				statusColor = statusColors["error"]
		else:
			statusColor = setColor(data["show"]["status"]["color"])
			if statusColor is None:
				return 1
	except Exception as e:
		logging.error("In show(): status color set failed. \n\tError: " + str(e))
		return 1
	
	#set heartbeat color
	heartbeatColor = setColor(data["settings"]["heartbeat"]["color"])
	if heartbeatColor is None:
		return 1

	#set status to empty if 0
	try:
		if data["show"]["status"]["clear0"]:
			if level == 0:
				statusmsg = ""
	except Exception as e:
		logging.error("In show(): clear status failed. \n\tError: " + str(e))
		return 1

	#enable status
	try:
		if data["show"]["status"]["enabled"]:
			statusmsg = ""
	except Exception as e:
		logging.error("In show(): enable status failed. \n\tError: " + str(e))
		return 1
	
	#get status and text pixel width
	try:
		textWidth, _ = font.getsize(data["show"]["text"]["msg"])
		statusWidth, _ = font.getsize(statusmsg)
	except Exception as e:
		logging.error("In show(): text and status pixel width failed set \n\tError: " + str(e))
		return 1

	#sets off set for scrolling text
	try:
		offset = count % (textWidth - data["display"]["width"] + statusWidth + 12)
		if (textWidth < data["display"]["width"]):
			offset = 0
		elif (offset < 6):
			offset = 0
		elif (offset > (textWidth - data["display"]["width"] + statusWidth + 6)):
			offset = (textWidth - data["display"]["width"] + statusWidth)
		else:
			offset -= 6
	except Exception as e:
		logging.error("In show(): offset failed set \n\tError: " + str(e))
		return 1

	#display text
	try:
		if statusmsg == "":
			gap = 0
		else:
			gap = 1

		textImage = Image.new('P', (textWidth + data["display"]["width"] - statusWidth - gap, data["display"]["height"]), 0)
		textdraw = ImageDraw.Draw(textImage)

		textdraw.text((0, -1), data["show"]["text"]["msg"], font=font, fill=255)
		textImage = ImageOps.flip(textImage)

		for x in range (data["display"]["width"] - statusWidth):
			for y in range (data["display"]["height"]):
				if (data["display"]["width"] - statusWidth - x == 1):
					pixels[getIndex(x, y)] = [0,0,0]
				elif (textImage.getpixel((x + offset, y)) is 255):
					pixels[getIndex(x, y)] = textColor
				else:
					pixels[getIndex(x, y)] = [0,0,0]
	except Exception as e:
		logging.error("In show(): display text failed. \n\tError: " + str(e))
		return 1

	#display status
	try:
		statusImage = Image.new('P', (statusWidth, data["display"]["height"]), 0)
		statusDraw = ImageDraw.Draw(statusImage)

		statusDraw.text((0, -1), statusmsg, font=font, fill=255)
		statusImage = ImageOps.flip(statusImage)

		loc = data["display"]["width"] - statusWidth

		for x in range (statusWidth):
			for y in range (data["display"]["height"]):
				if (statusImage.getpixel((x,y)) == 255):
					pixels[getIndex(x + loc, y)] = statusColor
				else:
					pixels[getIndex(x + loc, y)] = [0, 0, 0]
	except Exception as e:
		logging.error("In show(): display status failed. \n\tError: " + str(e))
		return 1

	#display Hearbeat
	try:
		if (data["settings"]["heartbeat"]["enabled"]):
			if (time.gmtime().tm_sec % 2):
				pixels[248] = heartbeatColor
			else:
				pixels[248] = [0, 0, 0]
	except Exception as e:
		logging.error("In show(): display Heartbeat failed. \n\tError: " + str(e))
		return 1
	
	#push all updates to display
	try:
		pixels.show()
	except Exception as e:
		logging.error("In show(): display pixels.show() failed. \n\tError: " + str(e))
		return 1
	
	return 0

update()
'''
while True:
	try:
		if not(lastUpdate == pathlib.Path(dataFile).stat().st_mtime):
			update()
		try:
			auto()
		except Exception as e:
			logging.error("audo function failed! \n\tError: " + str(e))
		print(show())

		count += 1
		if (count > 1000000):
			logging.info("count reset: " + str(count))
			count = 0
		time.sleep(data["settings"]["rate"])
	except Exception as e:
		logging.info("try&except Exit: \n\tError: " + str(e))
		pixels.fill(0)
		pixels.show()
		exit(0)
'''
