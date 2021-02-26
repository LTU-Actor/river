RIVER=/home/ubuntu/catkin_ws/src/river/src/

START_PY_FILE_BIN=/usr/local/bin/startUp.py
START_PY_FILE_SRC=/home/ubuntu/catkin_ws/src/river/src/startUp.py
SHOW_SRC=/home/ubuntu/catkin_ws/src/river/src/show.py
REMOTE_MASTER=/home/ubuntu/catkin_ws/src/river/src/remote-master.sh
DATA_JSON_SRC=/home/ubuntu/catkin_ws/src/river/src/data.json
DATA_JSON_TEMP=/home/ubuntu/catkin_ws/src/river/src/dataTemp.json
WEBSITE_SRC=/home/ubuntu/catkin_ws/src/river/src/www
UPDATE_SH_FILE=/home/ubuntu/catkin_ws/src/river/src/update.sh

echo "copy files"
sudo cp "$START_PY_FILE_SRC" "$START_PY_FILE_BIN"

sudo cp -R "$WEBSITE_SRC" "/var/www"

sudo cp "$DATA_JSON_SRC" "$DATA_JSON_TEMP"

echo "change commands"
sudo chmod -R 775 "/var/www"
sudo chmod 777 "$START_SH_FILE"
sudo chmod 777 "$UPDATE_SH_FILE"
sudo chmod 777 "$DATA_JSON_TEMP"
sudo chmod 777 "$DATA_JSON_SRC"

. "$REMOTE_MASTER"

echo "begin boot"
if [ "$1" == "boot" ]
then
    echo "boot"
    sudo python3 "$SHOW_SRC" &
    python3 "$START_PY_FILE_BIN"
fi