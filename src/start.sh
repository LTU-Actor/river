START_PY_FILE_BIN=/usr/local/bin/startUp.py
START_PY_FILE_SRC=/home/ubuntu/catkin_ws/src/river/src/startUp.py
REMOTE_MASTER=/home/ubuntu/catkin_ws/src/river/src/remote-master.sh

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

. "$REMOTE_MASTER"

cd "/home/ubuntu/catkin_ws" && catkin_make && source devel/setup.sh

python3 "$START_PY_FILE_BIN"