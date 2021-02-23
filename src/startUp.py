import os
import sys
import time
import json
import signal
import rosgraph
import subprocess

dataFile = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json"

class timeout:
    def __init__(self, seconds=1, error_message='Timeout'):
        self.seconds = seconds
        self.error_message = error_message
    def handle_timeout(self, signum, frame):
        raise RuntimeError(self.error_message)
    def __enter__(self):
        signal.signal(signal.SIGALRM, self.handle_timeout)
        signal.alarm(self.seconds)
    def __exit__(self, type, value, traceback):
        signal.alarm(0)

def isROS():
        with timeout(1):
            return rosgraph.is_master_online()

def show(text, status):
    data = None
    with open(dataFile) as file:
        data = json.load(file)
    
    data["show"]["text"]["msg"] = str(text)
    data["show"]["status"]["msg"] = str(status)

    jsonObj = json.dumps(data, indent = 4)

    with open(dataFile, "w") as file:
        file.write(jsonObj)

firstrun = True
mainProcess = None

try:
    while True:
            if isROS():
                if mainProcess is None:
                    show("Ready: ROS River Running", "0")
                    print("ROS River running.")
                    mainProcess = subprocess.Popen('exec rosrun river main.py', stdout = subprocess.PIPE, shell = True)
            else:
                if not mainProcess is None or firstrun:
                    firstrun = False
                    show("Waiting for ROS core", "4")
                    print("Waiting for ROS core.")
                    try:
                        mainProcess.kill()
                    except:
                        pass
                    mainProcess = None
            time.sleep(.1)
except Exception as e:
    print("Error:", e)
except KeyboardInterrupt:
    print("keyboard Interrupt")
finally:
    if not mainProcess is None:
        mainProcess.kill()
    sys.exit(0)