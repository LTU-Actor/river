START_PY_FILE_BIN=/usr/local/bin/startUp.py
START_PY_FILE_SRC=/home/ubuntu/catkin_ws/src/river/src/startUp.py
SHOW_SRC=/home/ubuntu/catkin_ws/src/river/src/show.py
REMOTE_MASTER=/home/ubuntu/catkin_ws/src/river/src/remote-master.sh
WEBSITE_SRC=/home/ubuntu/catkin_ws/src/river/src/www
DATA_JSON_SRC=/home/ubuntu/catkin_ws/src/river/src/data.json

while [ "$(hostname -I)" = "" ]; do
  echo -e "\e[1A\e[KNo network: $(date)"
  sleep 1
done

cd "/home/ubuntu/catkin_ws/src/river" && git pull

if test -f "$START_PY_FILE_BIN";
then
  sudo rm -rf "$START_PY_FILE"
fi

sudo cp "$START_PY_FILE_SRC" "$START_PY_FILE_BIN"

if [ -d "/var/www" ];
then
  sudo rm -rf "/var/www"
fi

sudo cp -R "$WEBSITE_SRC" "/var/www"
sudo chmod -R 775 "/var/www"
sudo chmod 777 "$DATA_JSON_SRC"

systemctl daemon-reload
sudo systemctl restart apache2

sudo apt-get install ros-kinetic-catkin

. "$REMOTE_MASTER"

cd "/home/ubuntu/catkin_ws" && catkin_make && source devel/setup.sh