from decimal import Context
from math import acos
import sys
import numpy as np
from os import *
import os.path
from pylab import * 

from funciones import *
from claseStrategy import *
from particula import *
from enjambre import *
import dataBaseClase as databaseClass

idProblema=sys.argv[9]
vectorOpinion = sys.argv[1]
aux=[]
auxiliar = vectorOpinion.split(',')
alfa=sys.argv[5]
beta=sys.argv[6]
gama=sys.argv[7]
pesosCriterios = sys.argv[8]
auxiliarPesos = pesosCriterios.split(',')
arrayCriterios=[]
for i in range(0,len(auxiliarPesos)):
    arrayCriterios.append(float(auxiliarPesos[i])) 

for i in range(0,(len(auxiliar))):
    auxValor=auxiliar[i]
    if(auxValor!=""):
        aux.append(float(auxiliar[i]))

numExpertos=int(sys.argv[2])
numCriterios=int(sys.argv[3])
numAlternativas=int(sys.argv[4])
opinionesIntermedias = copy.deepcopy(aux)

opiniones=obtenerOpinionesDesdeVectorPosicion(aux,numAlternativas,numExpertos,numCriterios)

arrayExpertos=[]
for e in range(0,len(opiniones)):
    experto = np.random.rand(len(opiniones[0]))
    arrayExpertos.append(experto)
    experto=[]


opinionesExpertosString = sys.argv[10]
opinionesExpertosArray = opinionesExpertosString.split(",")
opinionesExperto=[]
aux12=[]
contador=0
for j in range(0, numExpertos):
    for l in range(0, numCriterios):
        aux.append(float(opinionesExpertosArray[contador]))
        contador=contador+1
    opinionesExperto.append(aux12)
    aux12=[]
umbral = float(sys.argv[11])
enjambre = funcionOptimizacion(opiniones, arrayCriterios, alfa, beta, gama)
opAuxiliar=obtenerOpinionesDesdeVectorPosicion(enjambre.vectorMejorPosicion, opiniones[0][0].shape[0], len(opiniones), len(opiniones[0]))
cons1 = Problema(consensoA())  
e11=float(cons1.calcular_consenso(opAuxiliar))
alfa=float(alfa)
salir=False
while(e11<umbral and salir==False):
    if(alfa<2):
        alfa=alfa+0.2
        alfaAux=copy.deepcopy(alfa)
        enjambre = funcionOptimizacion(opiniones, arrayCriterios, alfaAux, beta, gama)
        opAuxiliar=obtenerOpinionesDesdeVectorPosicion(enjambre.vectorMejorPosicion, opiniones[0][0].shape[0], len(opiniones), len(opiniones[0]))
        e11=cons1.calcular_consenso(opAuxiliar)
    else:
        salir=True
if(salir==False):
    cohe1 = Problema(consistenciaA())
    opinionesFinal= calcularZ(opiniones, alfa, enjambre.vectorMejorPosicion)
    e1=cohe1.calcular_consistencia(opinionesFinal, arrayCriterios)
    cons1 = Problema(consensoA())  
    e11=cons1.calcular_consenso(opinionesFinal)

    agregacion = Problema(agregacionIOWA())
    e1a=agregacion.calcular_agregacion(opinionesFinal, opinionesExperto, arrayCriterios)

    explotacion = Problema(explotacionOWAGNDD())
    e1e=explotacion.calcular_explotacion(e1a)
    aux=""
    for i in range(0,len(enjambre.mejorValoracionGlobal)):
        auxiliar=str(enjambre.mejorValoracionGlobal[i])
        auxiliar=auxiliar[0:5]
        aux+=auxiliar+","
    database = databaseClass.DataBase()
    
    database.meterSolucion(idProblema, e1e)
    database.meterQs(idProblema, aux)
    aux1=""

    for i in range(0, len(e1)):
        aux1+=str(e1[i])+","
    pAux = str(e11)
    pAux = pAux[0:5]

    aux1+=str(e11)

    database.meterConsistencia(idProblema, aux1)
    database.mandarCorreo(idProblema)

    valoresq0=[]
    valoresq1=[]
    valoresq2=[]
    valoresq3=[]
    url="/imagenes/GraficaQParaProblema"+str(idProblema)+".png"
    imagen ="GraficaQParaProblema"+str(idProblema)+".png"
    ruta = os.path.abspath(os.getcwd())
    contenido = os.listdir(ruta)
    ejemplo_dir = ruta + "/imagenes"
    ret = os.access(ejemplo_dir, os.F_OK)
    contenido = os.listdir(ejemplo_dir)
    urlImagenBorrar = ejemplo_dir + "/" + imagen
    if(os.path.exists(urlImagenBorrar)):
        remove(urlImagenBorrar)
    database.guardarImagen(idProblema, url)

    for j in range(0, len(enjambre.valoresQs)):
        valoresq0.append(enjambre.valoresQs[j][0])
        valoresq1.append(enjambre.valoresQs[j][1])
        valoresq2.append(enjambre.valoresQs[j][2])
        valoresq3.append(enjambre.valoresQs[j][3])     
    plt.plot(valoresq0, "o", label="Q")
    plt.plot(valoresq1, "o", label="Q1")
    plt.plot(valoresq2, "o", label="Q2")
    plt.plot(valoresq3, "o", label="Q3")
    plt.legend()
    plt.savefig(urlImagenBorrar)
    print("Llego al final")
else:
    database = databaseClass.DataBase()
    database.problemaErrorCreado(idProblema)











