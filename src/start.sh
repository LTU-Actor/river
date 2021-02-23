REMOTE_MASTER=/home/ubuntu/catkin_ws/src/river/src/remote-master.sh
DATA_JSON_SRC=/home/ubuntu/catkin_ws/src/river/src/data.json
DATA_JSON_TEMP=/home/ubuntu/catkin_ws/src/river/src/dataTemp.json

if test -f "$DATA_JSON_TEMP";
then
        sudo rm -rf "$DATA_JSON_TEMP"
fi

sudo cp "$DATA_JSON_SRC" "$DATA_JSON_TEMP"

sudo chmod 777 "$DATA_JSON_TEMP"
sudo chmod 777 "$DATA_JSON_SRC"

. "$REMOTE_MASTER"