#!/usr/bin/env python3

import os
import time
import json
import board
import pathlib
import neopixel
from PIL import Image, ImageDraw, ImageFont, ImageOps

font = None
data = None
pixels = None
lastUpdate = None

displayQueue = []
currentMsg = None

dataFile = "/home/ubuntu/catkin_ws/src/river/src/data.json"

offset = 0
count = 0

assert os.geteuid() is 0, '\'show.py\' must be run as administrator!'
assert os.path.exists(dataFile), 'No such file: \'data.json\''

def update():
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

	data["display"]["brightness"] = float(data["display"]["brightness"])
	data["settings"]["rate"] = float(data["settings"]["rate"])

	data["display"]["height"] = int(data["display"]["height"])
	data["display"]["width"] = int(data["display"]["width"])

	data["auto"]["enabled"] = bool(data["auto"]["enabled"])
	data["auto"]["level"] = int(data["auto"]["level"])
	data["auto"]["duration"] = int(data["auto"]["duration"])
	data["auto"]["timeout"] = int(data["auto"]["timeout"])
	data["auto"]["data"]["dbw_enabled"] = bool(data["auto"]["data"]["dbw_enabled"])
	data["show"]["text"]["msg"] = str(data["show"]["text"]["msg"])
	data["show"]["status"]["msg"] = str(data["show"]["status"]["msg"])

	font = ImageFont.truetype(data["font"]["path"], data["font"]["size"])

	if not (proirText == data["show"]["text"]):
		offset = 0

def auto():
	global displayQueue
	global currentMsg

	if not data["auto"]["enabled"]:
		return
	
	for msg in data["auto"]["data"]["msgs"]:
		if msg["level"] >= data["auto"]["level"]:
			displayQueue.append(msg)

	data["auto"]["data"]["msgs"] = []

	#if the message has been shown for the duration amount clear it
	if currentMsg is not None:
		if (int(time.time()) - currentMsg["PItime"] < data["auto"]["duration"]):
			currentMsg = None

	print(displayQueue)
	#if message is clear find the next one or print no errors
	if currentMsg is None:
		while displayQueue:
			currentMsg = displayQueue.pop()
			if (int(time.time()) - currentMsg["PItime"] < data["auto"]["timeout"]):
				currentMsg["PItime"] = int(time.time())
				data["show"]["text"]["msg"] = currentMsg["msg"]
				data["show"]["status"]["msg"] = currentMsg["level"]

				jsonObj = json.dumps(data, indent = 4)

				with open(dataFile, "w") as file:
					file.write(jsonObj)
				
				update()
				print("______________________:", currentMsg)
				return
			else:
				currentMsg = None
		if currentMsg is None:
				data["show"]["text"]["msg"] = "No Errors"
				data["show"]["status"]["msg"] = "0"
	
				jsonObj = json.dumps(data, indent = 4)

				with open(dataFile, "w") as file:
					file.write(jsonObj)
				
				update()


def show():
	def getIndex(x, y):
		if x % 2 != 0:
			return (x * data["display"]["height"]) + y
		return (x * data["display"]["height"]) + (data["display"]["height"] - 1 - y)

	textWidth, _ = font.getsize(data["show"]["text"]["msg"])
	if (data["show"]["status"]["enabled"]):
		statusWidth, _ = font.getsize(data["show"]["status"]["msg"])

		statusImage = Image.new('P', (statusWidth, data["display"]["height"]), 0)
		statusDraw = ImageDraw.Draw(statusImage)

		statusDraw.text((0, -1), data["show"]["status"]["msg"], font=font, fill=255)
		statusImage = ImageOps.flip(statusImage)

		loc = data["display"]["width"] - statusWidth

		for x in range (statusWidth):
			for y in range (data["display"]["height"]):
				if (statusImage.getpixel((x,y)) == 255):
					colorHex = data["show"]["status"]["color"].lstrip('#')
					pixels[getIndex(x + loc, y)] = tuple(int(colorHex[i:i+2], 16) for i in (0, 2, 4))
				else:
					pixels[getIndex(x + loc, y)] = [0, 0, 0]

		if (data["settings"]["hartbeat"]["enabled"]):
			if (time.gmtime().tm_sec % 2):
				colorHex = data["settings"]["hartbeat"]["color"].lstrip('#')
				pixels[248] = tuple(int(colorHex[i:i+2], 16) for i in (0, 2, 4))
			else:
				pixels[248] = [0, 0, 0]
		gap = 1
	else:
		statusWidth = 0
		gap = 0

	offset = count % (textWidth - data["display"]["width"] + statusWidth + 12)
	if (textWidth < data["display"]["width"]):
		offset = 0
	elif (offset < 6):
		offset = 0
	elif (offset > (textWidth - data["display"]["width"] + statusWidth + 6)):
		offset = (textWidth - data["display"]["width"] + statusWidth)
	else:
		offset -= 6

	textImage = Image.new('P', (textWidth + data["display"]["width"] - statusWidth - gap, data["display"]["height"]), 0)
	textdraw = ImageDraw.Draw(textImage)

	textdraw.text((0, -1), data["show"]["text"]["msg"], font=font, fill=255)
	textImage = ImageOps.flip(textImage)


	for x in range (data["display"]["width"] - statusWidth):
		for y in range (data["display"]["height"]):
			if (data["display"]["width"] - statusWidth - x == 1):
				pixels[getIndex(x, y)] = [0,0,0]
			elif (textImage.getpixel((x + offset, y)) is 255):
				colorHex = data["show"]["text"]["color"].lstrip('#')
				pixels[getIndex(x, y)] = tuple(int(colorHex[i:i+2], 16) for i in (0, 2, 4))
			else:
				pixels[getIndex(x, y)] = [0,0,0]

	pixels.show()

update()
pixels = neopixel.NeoPixel(
	board.D18,
	data["display"]["width"] * data["display"]["height"],
	brightness = data["display"]["brightness"],
	auto_write = False)

while True:
	#try:
		if not(lastUpdate == pathlib.Path(dataFile).stat().st_mtime):
			update()
		auto()
		show()

		count += 1
		if (count > 1000000):
			count = 0
		time.sleep(data["settings"]["rate"])
	#except Exception as e:
	#	print(e)
	#	pixels.fill(0)
	#	pixels.show()
	#	exit(0)
