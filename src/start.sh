RIVER=/home/ubuntu/catkin_ws/src/river/src/

START_PY_FILE_BIN=/usr/local/bin/startUp.py
START_PY_FILE_SRC=/home/ubuntu/catkin_ws/src/river/src/startUp.py
SHOW_SRC=/home/ubuntu/catkin_ws/src/river/src/show.py
REMOTE_MASTER=/home/ubuntu/catkin_ws/src/river/src/remote-master.sh
DATA_JSON_SRC=/home/ubuntu/catkin_ws/src/river/src/data.json
DATA_JSON_TEMP=/home/ubuntu/catkin_ws/src/river/src/dataTemp.json
WEBSITE_SRC=/home/ubuntu/catkin_ws/src/river/src/www
UPDATE_SH_FILE=/home/ubuntu/catkin_ws/src/river/src/update.sh

red=$(tput setaf 1)
green=$(tput setaf 2)
reset=$(tput sgr0)

echo "copy files"
sudo cp "$START_PY_FILE_SRC" "$START_PY_FILE_BIN"

if [ -d "/var/www" ];
then
  sudo rm -rf "/var/www"
fi

sudo cp -R "$WEBSITE_SRC" "/var/www"

sudo cp "$DATA_JSON_SRC" "$DATA_JSON_TEMP"

echo "Permissions changed: /var/www"
sudo chmod -R 775 "/var/www"
echo "Permissions changed: UPDATE_SH_FILE"
sudo chmod 777 "$UPDATE_SH_FILE"
echo "Permissions changed: DATA_JSON_TEMP"
sudo chmod 777 "$DATA_JSON_TEMP"
echo "Permissions changed: DATA_JSON_SRC"
sudo chmod 777 "$DATA_JSON_SRC"

echo "REMOTE_MASTER"
. "$REMOTE_MASTER"

if [ "$1" == "boot" ]
then
    echo "$green Starting ROS River in 5 seconds...$reset"
    sleep 5
    echo "$red booting...$reset"
    sudo python3 "$SHOW_SRC" &
    python3 "$START_PY_FILE_BIN"
fi