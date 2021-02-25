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
tick = 0

statusColors = {
	"success": "#4F8A10",
	"info" : "#00529B",
	"warning": "#9F6000",
	"error": "#D8000C"
}

assert os.geteuid() is 0, '\'show.py\' must be run as administrator!'
assert os.path.exists(dataFile), 'No such file: \'data.json\''

root_logger= logging.getLogger()
root_logger.setLevel(logging.DEBUG)
handler = logging.FileHandler('show.log', 'w', 'utf-8')
handler.setFormatter(logging.Formatter("%(levelname)s %(asctime)s: %(name)s - %(message)s", "%H:%M:%S"))
root_logger.addHandler(handler)

def update():
	def verify(data, excpectedType):
		try:
			if type(data) is not excpectedType:
				data = excpectedType(data)
		except Exception as e:
			logging.error("In update() verify(): " + str(data) + " could not be converted to . \n\tError: " + str(e))
			raise TypeError
		return data
			
	global font
	global data
	global tick
	global pixels
	global lastUpdate
	global offset

	if data is None:
		proirText = None
	else:
		proirText = data["show"]["text"]

	try:
		with open(dataFile) as file:
			data = json.load(file)
		lastUpdate = pathlib.Path(dataFile).stat().st_mtime
	except Exception as e:
		logging.error("In update(): Failed to open json file. \n\tError: " + str(e))
		return None 
	
	try:
		#Verify Setting atributes
		data["settings"]["rate"] = verify(data["settings"]["rate"], float)
		data["settings"]["heartbeat"]["color"] = verify(data["settings"]["heartbeat"]["color"], str)
		data["settings"]["heartbeat"]["enabled"] = verify(data["settings"]["heartbeat"]["enabled"], bool)

		#Veridy Display atributes
		data["display"]["brightness"] = verify(data["display"]["brightness"], float)
		data["display"]["height"] = verify(data["display"]["height"], int)
		data["display"]["width"] = verify(data["display"]["width"], int)

		#Verify auto atributes
		data["auto"]["enabled"] = verify(data["auto"]["enabled"], bool)
		data["auto"]["level"] = verify(data["auto"]["level"], int)
		data["auto"]["duration"] = verify(data["auto"]["duration"], int)
		data["auto"]["timeout"] = verify(data["auto"]["timeout"], int)
		data["auto"]["data"]["dbw_enabled"] = verify(data["auto"]["data"]["dbw_enabled"], bool)

		#Verify Show atributes
		data["show"]["status"]["msg"] = verify(data["show"]["status"]["msg"], int)
		data["show"]["status"]["enabled"] = verify(data["show"]["status"]["enabled"], bool)
		data["show"]["status"]["colorGrade"] = verify(data["show"]["status"]["colorGrade"], bool)
		data["show"]["status"]["clear0"] = verify(data["show"]["status"]["clear0"], bool)
		data["show"]["status"]["color"] = verify(data["show"]["status"]["color"], str)
		data["show"]["text"]["msg"] = verify(data["show"]["text"]["msg"], str)
		data["show"]["text"]["color"] = verify(data["show"]["text"]["color"], str)

		#Verify Fonts atributes
		data["font"]["path"] = verify(data["font"]["path"], str)
		data["font"]["size"] = verify(data["font"]["size"], int)
	except TypeError:
		return None
	
	if not (proirText == data["show"]["text"]):
		offset = 0
		tick = 0

	try:
		font = ImageFont.truetype(data["font"]["path"], data["font"]["size"])
	except Exception as e:
		logging.error("In update(): font file open failed. \n\tError: " + str(e))
		return None

	#Setup pixels for display
	try:
		if pixels is not None:
			del pixels
		pixels = neopixel.NeoPixel(
			board.D18,
			data["display"]["width"] * data["display"]["height"],
			brightness = data["display"]["brightness"],
			auto_write = False)
	except Exception as e:
		logging.error("In update(): Pixel initalization failed. \n\tError: " + str(e))
		return None
	
	return True

def auto():
	global displayQueue
	global currentMsg
	global tick

	try:
		if not data["auto"]["enabled"]:
			return True
		
		for msg in data["auto"]["data"]["msgs"]:
			if msg["level"] >= data["auto"]["level"]:
				displayQueue.append(msg)

		data["auto"]["data"]["msgs"] = []

		#if the message has been shown for the duration time, clear it
		if currentMsg is not None:
			if (int(time.time()) - currentMsg["PItime"] > data["auto"]["duration"]):
				currentMsg = None

		#if message is clear find the next one or print no errors
		if currentMsg is None:
			while displayQueue:
				currentMsg = displayQueue.pop()
				if (int(time.time()) - currentMsg["PItime"] < data["auto"]["timeout"]):
					tick = 0
					currentMsg["PItime"] = int(time.time())
					data["show"]["text"]["msg"] = currentMsg["msg"]
					data["show"]["status"]["msg"] = currentMsg["level"]

					jsonObj = json.dumps(data, indent = 4)

					with open(dataFile, "w") as file:
						file.write(jsonObj)
					
					if update() is None:
						raise Exception("Update returned None")
					return True
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
					
					if update() is None:
						raise Exception("Update returned None")
	except Exception as e:
		logging.error("auto function failed! \n\tError: " + str(e))
		return None
	return True

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
		return None

	#set text color
	textColor = setColor(data["show"]["text"]["color"])
	if textColor is None:
		return None

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
				return None
	except Exception as e:
		logging.error("In show(): status color set failed. \n\tError: " + str(e))
		return None
	
	#set heartbeat color
	heartbeatColor = setColor(data["settings"]["heartbeat"]["color"])
	if heartbeatColor is None:
		return None

	#set status to empty if 0
	try:
		if data["show"]["status"]["clear0"]:
			if level == 0:
				statusmsg = ""
	except Exception as e:
		logging.error("In show(): clear status failed. \n\tError: " + str(e))
		return None

	#enable status
	try:
		if data["show"]["status"]["enabled"]:
			statusmsg = ""
	except Exception as e:
		logging.error("In show(): enable status failed. \n\tError: " + str(e))
		return None
	
	#get status and text pixel width
	try:
		textWidth, _ = font.getsize(data["show"]["text"]["msg"])
		statusWidth, _ = font.getsize(statusmsg)
	except Exception as e:
		logging.error("In show(): text and status pixel width failed set \n\tError: " + str(e))
		return None

	#sets off set for scrolling text
	try:
		offset = tick % (textWidth - data["display"]["width"] + statusWidth + 12)
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
		return None

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
		return None

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
		return None

	#display Hearbeat
	try:
		if (data["settings"]["heartbeat"]["enabled"]):
			if (time.gmtime().tm_sec % 2):
				pixels[248] = heartbeatColor
			else:
				pixels[248] = [0, 0, 0]
	except Exception as e:
		logging.error("In show(): display Heartbeat failed. \n\tError: " + str(e))
		return None
	
	#push all updates to display
	try:
		pixels.show()
	except Exception as e:
		logging.error("In show(): display pixels.show() failed. \n\tError: " + str(e))
		return None
	
	return True

while True:
	try:
		if (lastUpdate != pathlib.Path(dataFile).stat().st_mtime or pixels is None):
			print("update")
			if not update():
				continue
		
		time.sleep(data["settings"]["rate"])

		if not auto():
			continue

		if not show():
			continue

		tick += 1
		if (tick > 1000000):
			tick = 0
	except Exception as e:
		logging.info("try&except Exit: \n\tError: " + str(e))
		pixels.fill(0)
		pixels.show()
		exit(0)

