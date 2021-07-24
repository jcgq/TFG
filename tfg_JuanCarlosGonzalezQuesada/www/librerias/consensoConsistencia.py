
import sys
import numpy as np
import os

from funciones import *
from claseStrategy import *
from particula import *
from enjambre import *

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
    aux.append(float(auxiliar[i]))

numExpertos=int(sys.argv[2])
numCriterios=int(sys.argv[3])
numAlternativas=int(sys.argv[4])

opiniones=obtenerOpinionesDesdeVectorPosicion(aux,numAlternativas,numExpertos,numCriterios)

arrayExpertos=[]
for e in range(0,len(opiniones)):
    experto = np.random.rand(len(opiniones[0]))
    arrayExpertos.append(experto)
    experto=[]
#arrayCriterios = np.random.uniform(1,1,(len(opiniones[0])))

enjambre = funcionOptimizacion(opiniones, arrayCriterios, alfa, beta, gama)

opAuxiliar=obtenerOpinionesDesdeVectorPosicion(enjambre.vectorMejorPosicion, opiniones[0][0].shape[0], len(opiniones), len(opiniones[0]))

cohe1 = Problema(consistenciaB())  
e1=cohe1.calcular_consistencia(opiniones, arrayCriterios)

agregacion = Problema(agregacionOWA())
e1a=agregacion.calcular_agregacion(opAuxiliar, arrayExpertos, arrayCriterios)

explotacion = Problema(explotacionOWAGDD())
e1e=explotacion.calcular_explotacion(e1a)
aux=""
for i in range(0,len(enjambre.mejorValoracionGlobal)):
    auxiliar=str(enjambre.mejorValoracionGlobal[i])
    auxiliar=auxiliar[0:5]
    aux+=auxiliar+","

print(e1e,"$", aux)


