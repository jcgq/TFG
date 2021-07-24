import pymysql
class DataBase:
	def __init__(self):
		self.connection = pymysql.connect(
			host='localhost',
