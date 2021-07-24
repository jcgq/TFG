from __future__ import annotations
from abc import ABC, abstractmethod
from typing import List
import os
import numpy as np
from math import *
import funciones as lib
import decimal
decimal.getcontext().prec=5
import pymysql
import smtplib, ssl

class DataBase:
    def __init__(self):
        self.connection = pymysql.connect(
            host='mysql',
            user='root',
            password='tiger',
            db='docker'
        )

        self.cursor = self.connection.cursor()
        print("Se ha realizado la conexion correctamente")
    
    def meterSolucion(self, id, e1e):
        sql = 'UPDATE problemas SET solucion = \'{}\' WHERE idProblema = {}'.format(e1e, id)

        try:
            self.cursor.execute(sql)
            self.connection.commit()

        except Exception as e:
            raise

    def meterQs(self, id, qs):
        sql = 'UPDATE problemas SET qs = \'{}\' WHERE idProblema = {}'.format(qs, id)

        try:
            self.cursor.execute(sql)
            self.connection.commit()

        except Exception as e:
            raise

    def meterConsistencia(self, id, consistencia):
        sql = 'UPDATE problemas SET consistencias = \'{}\' WHERE idProblema = {}'.format(consistencia, id)
        try:
            self.cursor.execute(sql)
            self.connection.commit()

        except Exception as e:
            raise

    def mandarCorreo(self, id):
        sql='SELECT * FROM members m INNER JOIN asignarProblemaUsuario ap WHERE m.username=ap.username AND ap.idProblema={}'.format(id)

        try:
            self.cursor.execute(sql)
            results = self.cursor.fetchall()
            port = 587 
            smtp_server = "smtp.gmail.com"
            sender_email = "quesada99lolo@gmail.com"
            password = "Cumple1606"
            for row in results:
                receiver_email=row[3]
                message = """\
Subject: Problema resuelto!

Tu problema ya ha sido resuelto y puedes comprobar las soluciones."""
                context = ssl.create_default_context()
                with smtplib.SMTP(smtp_server, port) as server:
                    server.ehlo()
                    server.starttls(context=context)
                    server.ehlo()
                    server.login(sender_email, password)
                    server.sendmail(sender_email, receiver_email, message)
        
        except Exception as e:
            raise

    def problemaCreado(self, id):
        sql='SELECT * FROM members m INNER JOIN asignarProblemaUsuario ap WHERE m.username=ap.username AND ap.idProblema={}'.format(id)
        sql2='SELECT nombre FROM problemas WHERE idProblema={}'.format(id)
        try:
            self.cursor.execute(sql)
            results = self.cursor.fetchall()
            port = 587  # For starttls
            smtp_server = "smtp.gmail.com"
            sender_email = "quesada99lolo@gmail.com"
            password = "Cumple1606"
            for row in results:
                receiver_email=row[3]
                self.cursor.execute(sql2)
                results2 = self.cursor.fetchall()
                nombre = results2[0][0]
                message = """\
Subject: Problema creado!

El problema {} ha sido creado y ya puedes resolverlo.""".format(nombre)
                context = ssl.create_default_context()
                with smtplib.SMTP(smtp_server, port) as server:
                    server.ehlo()
                    server.starttls(context=context)
                    server.ehlo()
                    server.login(sender_email, password)
                    server.sendmail(sender_email, receiver_email, message)
        
        except Exception as e:
            raise

    def problemaParaResolver(self, id):
        sql='SELECT nombre FROM problemas WHERE idProblema={}'.format(id)
        try:
            self.cursor.execute(sql)
            results = self.cursor.fetchall()
            port = 587 
            smtp_server = "smtp.gmail.com"
            sender_email = "quesada99lolo@gmail.com"
            password = "Cumple1606"
            for row in results:
                receiver_email="quesada99lolo@gmail.com"
                message = """\
Subject: Problema listo para calcularse!

El problema {} ha recibido todas las opiniones y ya puede procederse a calcular los resultados.""".format(row[0])
                context = ssl.create_default_context()
                with smtplib.SMTP(smtp_server, port) as server:
                    server.ehlo()
                    server.starttls(context=context)
                    server.ehlo()
                    server.login(sender_email, password)
                    server.sendmail(sender_email, receiver_email, message)
        
        except Exception as e:
            raise


    def guardarImagen(self, id, urlImagen):
        sql = 'UPDATE problemas SET urlImagen = \'{}\' WHERE idProblema = {}'.format(urlImagen, id)
        try:
            self.cursor.execute(sql)
            self.connection.commit()

        except Exception as e:
            raise

    def desconectarBaseDeDatos(self):
        self.connection.close()

    def rehacerProblema(self, id):
        terminado=int(0)
        sql1 = 'UPDATE problemas SET terminado = \'{}\', resuelto = \'{}\' WHERE idProblema = {}'.format(terminado, terminado, id)
        sql2 = 'UPDATE asignarProblemaUsuario SET realizada = \'{}\' WHERE idProblema = {}'.format(terminado, id)
        try:
            self.cursor.execute(sql2)
            self.connection.commit()
            self.cursor.execute(sql1)
            self.connection.commit()


        except Exception as e:
            raise



    def problemaErrorCreado(self, id):
        self.rehacerProblema(id)
        sql='SELECT * FROM members m INNER JOIN asignarProblemaUsuario ap WHERE m.username=ap.username AND ap.idProblema={}'.format(id)
        try:
            self.cursor.execute(sql)
            results = self.cursor.fetchall()
            port = 587  # For starttls
            smtp_server = "smtp.gmail.com"
            sender_email = "quesada99lolo@gmail.com"
            password = "Cumple1606"
            for row in results:
                receiver_email=row[3]
                message = """\
Subject: Problema incorrecto!

El problema {} no se ha podido resolver y debe reenviar su opinion.""".format(id)
                context = ssl.create_default_context()
                with smtplib.SMTP(smtp_server, port) as server:
                    server.ehlo()
                    server.starttls(context=context)
                    server.ehlo()
                    server.login(sender_email, password)
                    server.sendmail(sender_email, receiver_email, message)
        
        except Exception as e:
            raise


