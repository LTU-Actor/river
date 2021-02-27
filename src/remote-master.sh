CORE_IP= jq .settings.ros.coreIP /home/ubuntu/catkin_ws/src/river/src/data.json
PORT= jq .settings.ros.port /home/ubuntu/catkin_ws/src/river/src/data.json
RIVER_IP= jq .settings.ros.riverIP /home/ubuntu/catkin_ws/src/river/src/data.json
echo CORE_IP.PORT
#export ROS_MASTER_URI=http://192.168.1.32:11311
#export ROS_IP=192.168.1.38
echo "ROS_MASTER_URI and ROS_IP updated"