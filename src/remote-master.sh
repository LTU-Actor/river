CORE_IP=$( jq .settings.ros.coreIP /home/ubuntu/catkin_ws/src/river/src/data.json )
PORT=$( jq .settings.ros.port /home/ubuntu/catkin_ws/src/river/src/data.json)
RIVER_IP=$( jq .settings.ros.riverIP /home/ubuntu/catkin_ws/src/river/src/data.json)

CORE_IP="${CORE_IP%\"}"
CORE_IP="${CORE_IP#\"}"
PORT="${PORT%\"}"
PORT="${PORT#\"}"
RIVER_IP="${RIVER_IP%\"}"
RIVER_IP="${RIVER_IP#\"}"

export "ROS_MASTER_URI=http://$CORE_IP:$PORT"
export "ROS_IP=$RIVER_IP"
echo "ROS_MASTER_URI and ROS_IP updated"