import os
import sys
import time
import json
import signal
import socket
import logging
import rosgraph
import subprocess

dataFile = "/home/ubuntu/catkin_ws/src/river/src/dataTemp.json"

root_logger= logging.getLogger()
root_logger.setLevel(logging.DEBUG)
handler = logging.FileHandler('/home/ubuntu/catkin_ws/src/river/src/startUp.log', 'w', 'utf-8')
handler.setFormatter(logging.Formatter("%(levelname)s %(asctime)s: %(name)s - %(message)s", "%H:%M:%S"))
root_logger.addHandler(handler)

assert os.path.exists(dataFile), 'No such file: \'dataTemp.json\''

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

def get_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        s.connect(('10.255.255.255', 1))
        IP = s.getsockname()[0]
    except Exception:
        IP = '127.0.0.1'
    finally:
        s.close()
    return IP

def isROS():
        with timeout(1):
            return rosgraph.is_master_online()

def show(text, status):
    try:
        data = None
        with open(dataFile) as file:
            data = json.load(file)
        
        data["show"]["text"]["msg"] = str(text)
        data["show"]["status"]["msg"] = str(status)

        jsonObj = json.dumps(data, indent = 4)

        with open(dataFile, "w") as file:
            file.write(jsonObj)
        return True
    except Exception as e:
        logging.error("Show() function failed: \n\tError: " + str(e))
        return None


firstrun = True
mainProcess = None

while True:
    try:
        if isROS():
           # print("Ros Running")
            if mainProcess is None:
                try:
                    show("Ready: ROS River Running", "0")
                    logging.info("ROS River Running.")
                    mainProcess = subprocess.Popen('exec rosrun river main.py', stdout = subprocess.PIPE, shell = True)
                except Exception as e:
                    logging.error("Main Process failed to initalize: \n\tError: " + str(e))
        else:
            if not mainProcess is None or firstrun:
                print("Running FirstRun")
                firstrun = False
                # show("Waiting for ROS core! Website: " + str(get_ip()), "4")
                print(str(get_ip()))
                logging.info("Waiting for ROS core.")
                try:
                    if mainProcess is not None:
                        mainProcess.kill()
                except Exception as e:
                    logging.error("failed to kill mainProcess: \n\tError: " + str(e))
                mainProcess = None
        time.sleep(.1)
    except KeyboardInterrupt:
        logging.info("KeyboardInterrupt")
        if not mainProcess is None:
            mainProcess.kill()
        sys.exit(0)
    except Exception as e:
        logging.error("ROS loop failed: \n\tError: " + str(e))
        
