import re
import string

a = input()
if re.match(r"^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$", a):
	print("Valid")
else:
	print("INVALID!")
