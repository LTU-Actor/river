import os
import sys
import time
import signal
import rosgraph
import subprocess

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

mainProcess = None
showProcess = subprocess.Popen('sudo python3 /home/ubuntu/catkin_ws/src/river/src/show.py', stdout = subprocess.PIPE, shell = True)
print("showProcess")

try:
    while True:
            if isROS():
                if mainProcess is None:
                    print("ROS River running.")
                    mainProcess = subprocess.Popen('exec rosrun river main.py', stdout = subprocess.PIPE, shell = True)
            else:
                if not mainProcess is None:
                    try:
                        mainProcess.kill()
                    except:
                        pass
                    mainProcess = None
                print("Waiting for ROS core.")
            time.sleep(.1)
except Exception as e:
    print("Error:", e)
except KeyboardInterrupt:
    print("keyboard Interrupt")
finally:
    if not mainProcess is None:
        mainProcess.kill()
    sys.exit(0)