import smtplib, ssl
import sys

import dataBaseClase as dataB



database=dataB.DataBase()

idProblema = sys.argv[1]

database.problemaErrorCreado(idProblema)
database.desconectarBaseDeDatos()