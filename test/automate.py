# Title: Automate Form Filling
# Author: Robin Gautam
# Email: robin.gautam341@gmail.com

import re
import random

autoFeed = open("C:\\Users\\imblu\\OneDrive\\Documents\\iMacros\\auto.iim", "w")
content = ''
temp = open("template.rob", "r")

lines = temp.readlines()

for line in lines:
	match = re.search(r'TXT', line)
	if match:
		pass
	else:
		newLine = re.sub('TAG POS=.', 'TAG POS=' + str(random.randint(1,5)), line)
		content = content + newLine

autoFeed.writelines(content)