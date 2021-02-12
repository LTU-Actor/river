import os
import socket
import subprocess
import rosgraph

ROS_MASTER_URI = None
ROS_IP = None

def connect(hostname, port):
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	socket.setdefaulttimeout(.1)
	result = sock.connect_ex((hostname, port))
	sock.close()
	return result == 0

def checkROS():
	try:
		rosgraph.Master('/rostopic').getPid()
		return True
	except:
		return False


def getIP():
	for i in range(0,255):
		res = connect("192.168.1." + str(i), 11311)
		if res:
			with open("remote-master.sh", "w") as file:
				file.write("export ROS_MASTER_URI=http://192.168.1." + str(i) + ":11311\n")
				file.write("export ROS_IP=http://192.168.1.24")
	return ("No ROS core running")

getIP()
