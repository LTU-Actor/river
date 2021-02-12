import os
import git
import json

dataFile = str(os.path.normpath(os.getcwd() + os.sep + os.pardir)) + "/src/data.json"

with open(dataFile) as file:
	data = json.load(file)

with open("remote-master.sh", "w") as file:
	file.write("export ROS_MASTER_URI=http://" + str(data["settings"]["ros"]["coreIP"]) + ":" + str(data["settings"]["ros"]["port"]))
	file.write("\nexport ROS_IP=" + str(data["settings"]["ros"]["riverIP"]))

def git_pull_change(path):
	repo = git.Repo(path)
	current = repo.head.commit

	repo.remotes.origin.pull()

	if current == repo.head.commit:
		print("Repo not changed. Sleep mode activated.")
		return False
	else:
		print("Repo changed! Activated.")
		return True

git_pull_change(str(os.path.normpath(os.getcwd() + os.sep + os.pardir)))

print("restarting")
#os.system('reboot')
