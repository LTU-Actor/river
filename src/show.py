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

	font = ImageFont.truetype(data["font"]["path"], data["font"]["size"])

	if not (proirText == data["show"]["text"]):
		offset = 0

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
					pixels[getIndex(x + loc, y)] = data["show"]["status"]["color"]
				else:
					pixels[getIndex(x + loc, y)] = [0, 0, 0]

		if (data["settings"]["hartbeat"]["enabled"]):
			if (time.gmtime().tm_sec % 2):
				pixels[248] = data["settings"]["hartbeat"]["color"]
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

	for x in range (data["display"]["width"] - statusWidth - gap):
		for y in range (data["display"]["height"]):
			if (textImage.getpixel((x + offset, y)) is 255):
				pixels[getIndex(x, y)] = data["show"]["text"]["color"]
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
	try:
		if not(lastUpdate == pathlib.Path(dataFile).stat().st_mtime):
			print("Update")
			update()
		show()

		count += 1
		time.sleep(data["settings"]["rate"])
	except:
		pixels.fill(0)
		pixels.show()
		exit(0)
