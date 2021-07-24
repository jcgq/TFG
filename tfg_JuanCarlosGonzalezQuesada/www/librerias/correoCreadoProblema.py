import smtplib, ssl
import sys
import dataBaseClase as dataBase

idProblema = sys.argv[1]
data=dataBase.DataBase()
data.problemaCreado(idProblema)
print("Peto")
desconectarBaseDeDatos()