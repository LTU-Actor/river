import os
import git
import json

dataFile = "/home/ubuntu/catkin_ws/src/river/src/data.json"

with open(dataFile) as file:
	data = json.load(file)

print(data)

with open("remote-master.sh", "w") as file:
	file.write("export ROS_MASTER_URI=http://" + str(data["settings"]["ros"]["coreIP"]) + ":" + str(data["settings"]["ros"]["port"]))
	file.write("\nexport ROS_IP=" + str(data["settings"]["ros"]["riverIP"]))

#def get_git_root(path):

#        git_repo = git.Repo(path, search_parent_directories=True)
#        git_root = git_repo.git.rev_parse("--show-toplevel")
#        print (git_root)

#get_git_root("/home/ubuntu/catkin_ws/src/river/src/data.json")

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

print(git_pull_change("/home/ubuntu/catkin_ws/src/river"))
#print(o.pull())
