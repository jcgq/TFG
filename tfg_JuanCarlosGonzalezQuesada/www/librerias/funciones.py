import numpy as np
import math
from math import *
from claseStrategy import *
import decimal
import os
import particula as par
import enjambre as enj
decimal.getcontext().prec=5
import copy
import random



#Funcion que calcula la coherencia de una matriz dada
def nivelConsistenciaMatriz(m):
    m_aux=np.zeros((m.shape[0], m.shape[0]))
    for x in range(m.shape[0]):
        for z in range(m.shape[0]):
            #Inicializamos las variables que van a intervenir en el proceso
            cpj1=0.0
            cpj2=0.0
            cpj3=0.0
            cp=0.0
            epsip=0.0
            cl=0.0
            #Obtenemos la parte 3, 4 y 5
            for i in range(m.shape[0]):
                for j in range(m.shape[0]):
                    for k in range(m.shape[0]):
                        if i != k and i!=j and k!= j and i==x and k==z:
                            cpj1+=(m[i,j])+(m[j,k])-(0.5)
                            cpj2+=(m[j,k])-(m[j,i])+(0.5)
                            cpj3+=(m[i,j])-(m[k,j])+(0.5)
            #Parte 6      
            if(m.shape[0]!=2):
                cp=((cpj1+cpj2+cpj3)/(3*(m.shape[0]-2)))
            else:
                cp=((cpj1+cpj2+cpj3)/(3))
            
            #Parte 7
            epsip=(2/3)*(abs(cp-(m[x,z])))
            #Parte 8
            cl=(1-epsip)
            if x==z:
                m_aux[x, z]=0.0
            else:
                m_aux[x, z]=cl

    array_aux=np.zeros(m_aux.shape[0])
    suma=0.0

    #Parte 9
    for i in range(m_aux.shape[0]):
        suma=decimal.Decimal(0.0)
        for k in range(m_aux.shape[0]):
            suma+=decimal.Decimal(m_aux[i, k]+m_aux[k,i])
        array_aux[i] = decimal.Decimal(suma/decimal.Decimal((2*(m_aux.shape[0]-1))))

    #Parte 10
    suma=decimal.Decimal(0.0)
    for i in range(array_aux.size):
        suma+=decimal.Decimal(array_aux[i])
    CL = decimal.Decimal(suma/array_aux.size)
    return CL



def obtenerConsistenciaExperto(arrayExperto, pesos):
    suma=decimal.Decimal(0.0)
    numFilas=arrayExperto[0].shape[0]
    aux=0
    numMatrices=len(arrayExperto)
    sum_Matrices=[]
    matriz_final=np.zeros((numFilas, numFilas))
    #Recorremos los distintos criterios
    #print("El numero de criterios es ", numMatrices, " el numero de filas es ", numFilas)
    if(numMatrices!=1):
        aux=arrayExperto[0]*pesos[0]
        for i in range(1, numMatrices):
            aux+=(arrayExperto[i]*pesos[i])
        for i in range(0, numFilas):
            for j in range(0, numFilas):
                if(i!=j):
                    aux[i][j]=(aux[i][j]/numMatrices)
    else:
        aux=arrayExperto[0]
    matrizAux=np.zeros((numFilas, numFilas))
    for i in range(0, numFilas):
        for j in range(0, numFilas):
            if(i!=j):
                matrizAux[i][j]=aux[i][j]
    #print(matrizAux)
    suma=nivelConsistenciaMatriz(matrizAux)
    #print("La suma es ", suma)
    return suma


#Obtenemos el nivel de consistencia dado un conjunto de opiniones
def funcionNivelConsistencia(opiniones, opinionExpertosCriterio):
    aux=0
    auxindi=""
    arrayconsistencia=[]
    for i in range(0,len(opiniones)):
        auxindi=obtenerConsistenciaExperto(opiniones[i], opinionExpertosCriterio)
        #print(auxindi, i)
        aux+=auxindi
        arrayconsistencia.append(auxindi)
        #print(arrayconsistencia)
    consistencia=(aux/len(opiniones))
    arrayconsistencia.append(consistencia)
    return arrayconsistencia


#Función que dadas todas las matrices de los expertos y todos los criterios, calcula el consenso Global
def funcionNivelConsenso(opiniones):
    suma=0.0
    for i in range(0,len(opiniones)):
        suma+=nivelConsensoExperto(opiniones[i], len(opiniones[i]))
    clFinal=0.0
    clFinal=suma/len(opiniones)
    return clFinal

#Funcion que calcula la consistencia de un experto dado
def nivelConsensoExperto(opinionesExperto, num_expertos):
#Parte 9
    array_aux=calculoMatricesParte9(opinionesExperto)
#-----------------------------------------------------------------------------
#Parte 10
    m_final = sumaMatricesyNormalizacionParte10(array_aux, num_expertos)       
    suma=0.0
#--------------------------------------------------------------------------------    
#Parte 11 y parte 12
    array_aux=obtenerVectorPesosParte11y12(m_final)
#-----------------------------------------------------------------
#Parte 13
    ckr = obtenerConsensoFinal(array_aux)
    return ckr

def calculoMatricesParte9(arraymatrices):
    array_aux=[]
    aux=arraymatrices[0]
    m_aux=np.zeros((aux.shape[0], aux.shape[0]))
    #Parte 9
    #Obtengo todas las matrices de similaridad
    if(len(arraymatrices)==1):
        array_aux.append(arraymatrices[0])
    else:
        for i in range(0,len(arraymatrices)):
            m1=arraymatrices[i]
            for j in range(i,len(arraymatrices)):
                m2=arraymatrices[j]
                if i!=j:
                    for x in range(m1.shape[0]):
                        for y in range(m2.shape[0]):
                            if x!=y:
                                m_aux[x,y]=(1-abs(m1[x,y]-m2[x,y]))
                    array_aux.append(m_aux)
                    m_aux=np.zeros((aux.shape[0], aux.shape[0]))
    return array_aux

def sumaMatricesyNormalizacionParte10(array_aux, num_expertos):
    #Obtención de la matriz final, y normalización
    aux=array_aux[0]
    mFinal=aux
    m_aux=np.zeros((aux.shape[0], aux.shape[0]))
    if(len(array_aux)==1):
        for i in range(0,mFinal.shape[0]):
            for j in range(0,mFinal.shape[0]):
                if i!=j:
                    if(num_expertos==1):
                        m_aux[i][j]=(array_aux[0][i][j])/2
                    else:
                        m_aux[i][j]=((array_aux[0][i][j])/((num_expertos-1)*(num_expertos)*0.5))
        return m_aux
    else:
        for i in range(1,len(array_aux)):
            mFinal+=array_aux[i]

        for i in range(0,mFinal.shape[0]):
            for j in range(0,mFinal.shape[0]):
                if i!=j:
                    if(num_expertos==1):
                        m_aux[i][j]=(mFinal[i][j])/2
                    else:
                        m_aux[i][j]=((mFinal[i][j])/((num_expertos-1)*(num_expertos)*0.5))
    return m_aux

def obtenerVectorPesosParte11y12(mFinal):
    arrayaux=np.zeros(mFinal.shape[0])
    for i in range(mFinal.shape[0]):
        suma=0.0
        for k in range(mFinal.shape[0]):
            if i != k:
                suma+=(mFinal[i, k]+mFinal[k,i])
        arrayaux[i]+=suma/(2*(mFinal.shape[0]-1))
    suma=0.0
    return arrayaux

def obtenerConsensoFinal(array_aux):
    suma=0.0
    for i in range(len(array_aux)):
        suma+=array_aux[i]
    CRk = suma/len(array_aux)
    return CRk
    




#Esta función sirve para obtener de los txts la matriz de matrices de las opiniones
def obtenerTodasOpiniones(id_problema):
    totalMatrices=[]
    contenido = os.listdir("problemas/"+id_problema)
    for i in range(len(contenido)):
        archivo = open("problemas/"+id_problema+"/"+contenido[i], "r") 
        experto=[]
        for linea in archivo.readlines():
            aux=linea.rstrip('\n')
            aux=aux.replace(',', ' ')
            v_aux=aux.split()
            for j in range(len(v_aux)):
                v_aux[j]=float(v_aux[j])
            data = np.array(v_aux)
            shape = (int(math.sqrt(len(v_aux))), int(math.sqrt(len(v_aux))))
            data = data.reshape( shape )
            experto.append(data)
        totalMatrices.append(experto)
    return totalMatrices


#Función cque calcula la medida OWA para un criterio dados X expertos
def calcularMediaOWA(criterio):
    #Se inicializan todas las variables
    matriz_final=np.zeros((len(criterio[0]), len(criterio[0])))
    aux=0
    tamanio=len(criterio[0])
    arrayAux=[]
    
    #Primero se obtienen todos los valores de cada fila
    for i in range(0,tamanio):
        for j in range(0,tamanio):
            if(i!=j):
                for k in range(0,len(criterio)):
                    #Los extraemos y los metemos en un array auxiliar
                    criAux=criterio[k]
                    arrayAux = np.append(arrayAux,criAux[i][j])
                #Los ordenamos de forma decreciente, para poder multiplicar por los pesos correspondientes    
                arrayAux = sorted(arrayAux, reverse=True)
                #Realizo la multiplicación
                for k in range(0,len(criterio)):
                    aux+=(arrayAux[k])
                aux=(aux/len(arrayAux))    
            arrayAux=[]
            matriz_final[i][j]=aux
            aux=0
    return matriz_final


#Con esta función calculamos los pesos por los que se va a multiplicar cada valor de la matriz
def calcularPeso(tamanio):
    array_pesos=[]
    aux=0
    #Utilizamos la función majority
    for i in range(1,tamanio+1):
        aux=sqrt(i/tamanio)-sqrt((i-1)/tamanio)
        array_pesos = np.append(array_pesos, aux)
    return array_pesos


#Función que calcula la agregación dado un conjunto de opiniones
def fun_agregacionOWA(opiniones):
    array_aux=[]
    criteriosExpertos=[]
    #Calculamos de forma individual la agregación para cada criterio, con sus pesos correspondientes
    for i in range(0,len(opiniones[0])):
        for j in range(0,len(opiniones)):
            criteriosExpertos.append(opiniones[j][i])
        aux=calcularMediaOWA(criteriosExpertos)
        array_aux.append(aux)
        criteriosExpertos=[]
    array_Final=[]
    #Si solo hay un criterio, el resultado de la aterior agregación, será el definitivo
    if(len(array_aux)!=1):
        #Se vuelve a realizar la agregación de las matices resultantes
        aux=calcularMediaOWA(array_aux)
        array_Final.append(aux)
    else:
        array_Final=array_aux
        
    return array_Final

#Con esta función calculamos los pesos por los que se va a multiplicar cada valor de la matriz
def calcularMediaIOWA(criterio):
    matriz_final=np.zeros((len(criterio[0]), len(criterio[0])))
    aux=0
    tamanio=len(criterio[0])
    arrayPesos = calcularPeso(len(criterio))
    arrayAux=[]
    
    for i in range(0,tamanio):
        for j in range(0,tamanio):
            if(i!=j):
                for k in range(0,len(criterio)):
                    criAux=criterio[k]
                    arrayAux = np.append(arrayAux,criAux[i][j])
                for k in range(0,len(criterio)):
                    aux+=(arrayAux[k]*arrayPesos[k])

            arrayAux=[]
            matriz_final[i][j]=aux
            aux=0   
    return matriz_final

#Función para calcular la agregación IOWA
def fun_agregacionIOWA(opiniones, arrayimportanciaExpertos, arrayimportanciaCriterios):
    #Se ordenan los criterios por nivel de importancia
    opinionesAux=ordenarOpinionesImportancia(opiniones,arrayimportanciaExpertos)
    array_aux=[]
    criteriosExpertos=[]
    for i in range(0,len(opinionesAux[0])):
        for j in range(0,len(opinionesAux)):
            criteriosExpertos.append(opinionesAux[j][i])
        aux=calcularMediaIOWA(criteriosExpertos)
        array_aux.append(aux)
        criteriosExpertos=[]
    
    #Se ordena por la importancia de los expertos en el problema
    arrayFinal=[]
    if(len(array_aux)!=1):
        arrayPlus=ordenarOpinionesImportancia(array_aux, arrayimportanciaCriterios)
        arrayFinal.append(np.array(calcularMediaIOWA(arrayPlus)))
    else:
        arrayFinal=(array_aux)

    return arrayFinal



#Función auxiliar que sirve para ordenar un array con respecto a otro valor
def ordenarOpinionesImportancia(opiniones, arrayImportanciaExpertos):
    aux=np.argsort(arrayImportanciaExpertos)
    mat = []
    for i in range(0,len(opiniones)):
        mat.append([aux[i],opiniones[i]])
    mat.sort(key=lambda mat: mat[0], reverse=True)
    auxOpiniones=[]
    for i in range(0,len(mat)):
        auxOpiniones.append(mat[i][1])
    return auxOpiniones






def fun_explotacionOWAGDD(criterio):
    aux=0
    arrayFinalExplotacion=[]
    tamanio=len(criterio[0][0])
    for i in range(0,tamanio):
        arrayPesos = calcularPeso(tamanio-1)
        arrayAux=sorted(criterio[0][i], reverse=True)
        for k in range(0,len(arrayPesos)):
            suma=(arrayAux[k]*arrayPesos[k])
            aux+=suma
        arrayFinalExplotacion.append(aux)
        aux=0
    return arrayFinalExplotacion



def fun_explotacionOWAGNDD(criterio):
    aux=0
    arrayFinalExplotacion=[]
    tamanio=len(criterio[0])
    matriz_auxiliar=np.zeros((tamanio, tamanio))
    for i in range(0,tamanio):
        for j in range(0,tamanio):
            if(i!=j):
                resta=(criterio[0][j][i]-criterio[0][i][j])
                if(resta>0):
                    matriz_auxiliar[j][i]=resta
                else:
                    matriz_auxiliar[j][i]=0
    for i in range(tamanio):
        for j in range(tamanio):
            if(i!=j):
                matriz_auxiliar[i][j]=1-matriz_auxiliar[i][j]
    aux=0
    arrayFinalExplotacion=[]
    arrayAux=[]
    arrayPesos = calcularPeso(tamanio-1)
    for i in range(0,tamanio):
        for j in range(0,tamanio):
            if(i!=j):
                criAux=matriz_auxiliar[i][j]
                arrayAux = np.append(arrayAux,criAux)   

        arrayAux = sorted(arrayAux, reverse=True)
        for k in range(0,len(arrayPesos)):
            aux+=(arrayAux[k]*arrayPesos[k])

        arrayAux=[]
        arrayFinalExplotacion.append(aux)
        aux=0

    return arrayFinalExplotacion


def calcularZ(opiniones, alpha, vectorPosicion):
    a=0
    b=0
    z=0
    contador=0
    opinionesAuxiliares=copy.deepcopy(opiniones)
    for m in range(len(opinionesAuxiliares)):
        for k in range(len(opinionesAuxiliares[m])):
            auxiliar = np.zeros((len(opinionesAuxiliares[m][k][0]), len(opinionesAuxiliares[m][k][0])))
            for i in range(len(opinionesAuxiliares[m][k])):
                for j in range(len(opinionesAuxiliares[m][k])):
                    if i!=j:
                        aux=opinionesAuxiliares[m][k][i][j]-(alpha/2)
                        if(aux>0):
                            a=aux
                        else:
                            a=0

                        aux=opinionesAuxiliares[m][k][i][j]+(alpha/2)
                        if(1>aux):
                            b=aux
                        else:
                            b=1
                            
                        z=a+((b-a)*vectorPosicion[contador])
                        contador=contador+1
                        opinionesAuxiliares[m][k][i][j]=z
    return opinionesAuxiliares



def obtenerArrayPesos(opiniones):
    opinionExpertosCriterio=[]
    auxOpiniones=[]
        
    for j in range(0, len(opiniones)):
        for l in range(0, len(opiniones[0])):
            s_nor=np.random.rand(opiniones[0][0].shape[0])
            aux=0
            for m in range(0, len(s_nor)):
                aux+=s_nor[m]
            for m in range(0, len(s_nor)):
                s_nor[m]=s_nor[m]/aux
            aux=0
            auxOpiniones.append(s_nor)
                
        opinionExpertosCriterio.append(auxOpiniones)
        auxOpiniones=[]
    return opinionExpertosCriterio

def calculoQ3(opiniones, vectorOpinion):
 
    #print("Las opiniones son ", opiniones)
    aux_diferencia=copy.deepcopy(diferencia(vectorOpinion, opiniones))

    arrayExpertosFinal=[]
    if(len(aux_diferencia[0])!=1):
        for i in range(0, len(aux_diferencia)):
            aux=aux_diferencia[i]
            for j in range(1, len(aux_diferencia[i])):
                aux+=aux_diferencia[i][j]
            arrayExpertosFinal.append(aux)
            aux=0
    else:
        arrayExpertosFinal.append(copy.deepcopy(aux_diferencia[0]))

    for i in range(0, len(arrayExpertosFinal)):
            for j in range(0, len(arrayExpertosFinal[i])):
                arrayExpertosFinal[i][j]=(arrayExpertosFinal[i][j]/len(aux_diferencia[0]))
    

    #print(arrayExpertosFinal, len(arrayExpertosFinal))
    vectorMatriz=[]
    aux=0
    #print("arrayExpertosFinal",arrayExpertosFinal)
    for i in range(0, len(arrayExpertosFinal)): 
        for j in range(0, len(arrayExpertosFinal[i])):   
            aux+=arrayExpertosFinal[i][j]
        aux=(aux/len(arrayExpertosFinal[i]))
        #print(aux)
        vectorMatriz.append(copy.deepcopy(aux))
        aux=0
    #print(vectorMatriz)
    matriz=vectorMatriz[0]
    for i in range(1, len(vectorMatriz)):
        matriz+=vectorMatriz[i]
    matriz=(matriz/len(vectorMatriz))

    #print(matriz)

    vectorFinal=[]
    aux=0
    for i in range(0, matriz.shape[0]):
        for j in range(0, matriz.shape[0]):
            aux+=matriz[i][j]
        aux=(aux/(matriz.shape[0]-1))
        vectorFinal.append(copy.deepcopy(aux))
        aux=0

    aux=0
    for i in range(0, len(vectorFinal)):
        aux+=vectorFinal[i]
    q3aux=(aux/len(vectorFinal))
    Q3=1-q3aux
    #print("Q3 vale ",Q3)
    return Q3


    
def diferencia(opiniones, opinionEnjambre):
    aux_auxiliarExperto=[]
    auxiliar=[]
    
    for i in range(0, len(opiniones)):
        for j in range(0, len(opiniones[i])):
            aux=abs(opiniones[i][j]-opinionEnjambre[i][j])
            auxiliar.append(aux)
        aux_auxiliarExperto.append(auxiliar)
        auxiliar=[]
        
    return aux_auxiliarExperto



def calcularQ(opinionesIniciales, arrayPesos, vectorPosicion, _alfa, _beta, _gama):
    alpha=float(_alfa)
    auxiliar=calcularZ(opinionesIniciales, alpha, vectorPosicion)
    Q1Aux=funcionNivelConsistencia(auxiliar, arrayPesos)
    Q1=Q1Aux[len(opinionesIniciales)-1]
    Q2=funcionNivelConsenso(auxiliar)
    Q3=calculoQ3(opinionesIniciales, auxiliar)

    Q_aux=0.0
    
    gamma=decimal.Decimal(_gama)
    
    Q_aux=gamma*(decimal.Decimal(Q1))+(1-gamma)*(decimal.Decimal(Q2))

    Q_aux=decimal.Decimal(Q_aux)  
    betta=decimal.Decimal(_beta)
    
    Q=decimal.Decimal(Q_aux)*betta+decimal.Decimal(Q3)*decimal.Decimal((1-betta))

    aux=[]
    aux.append([Q, Q1, decimal.Decimal(Q2), decimal.Decimal(Q3)])
    return aux



def obtenerVectorPosicionDesdeOpiniones(numExpertos, numCriterios, tamanioMatriz, opiniones):
    aux=[]
    for i in range(0, len(opiniones)):
        for j in range(numCriterios):
            for x in range(opiniones[i][j].shape[0]):
                for y in range(opiniones[i][j].shape[0]):
                    if(opiniones[i][j][x][y]!=0):
                        aux.append(opiniones[i][j][x][y])
    return aux

def obtenerOpinionesDesdeVectorPosicion(array, tamanio, numExpertos, numCriterios):
    #print("El tamanio del array es ", len(array))
    identidad = invertmatrix(np.identity(tamanio))
    arraicito=copy.deepcopy(array)
    aux=[]
    opiniones=[]
    for x in range(0,numExpertos):
        #print(x)
        for y in range(0, numCriterios):
            #print(y)
            for i in range(0,len(identidad)):
                for j in range(0,len(identidad[i])):
                    if(identidad[i][j] == 1):
                        identidad[i][j] = arraicito[0]
                        arraicito=np.delete(arraicito,0)
                        #print("no peto", i, j)
            aux.append(identidad)
            identidad = invertmatrix(np.identity(tamanio))
        opiniones.append(aux)
        aux=[]
        #print("Salgo")
    return opiniones


def invertmatrix(matrix):
    for i in range(0,len(matrix)):
        for j in range(0,len(matrix[i])):
            if(matrix[i][j] == 0):
                matrix[i][j] = 1
            else:
                matrix[i][j] = 0
    return matrix



def funcionOptimizacion(opiniones, arrayCriterios, alfa, beta, gama):
    particulas=[]
    tamanio=len(opiniones)*len(opiniones[0])*((opiniones[0][0].shape[0]*opiniones[0][0].shape[0])-opiniones[0][0].shape[0])
    numExpertos=len(opiniones)
    numCriterios=len(opiniones[0])
    tamanioMatriz=opiniones[0][0].shape[0]
    numIteraciones=0
    wp=decimal.Decimal(2.0)
    wy=decimal.Decimal(2.0)
    numMasIteraciones=300
    
    for i in range(0,100):
        x=par.Particula(len(opiniones), len(opiniones[0]), opiniones[0][0].shape[0])
        particulas.append(x)

    enjambre=enj.Enjambre(particulas)
    
    for i in range(0, len(particulas)):
        q=calcularQ(copy.deepcopy(opiniones), arrayCriterios, copy.deepcopy(particulas[i].vectorPosicion), alfa, beta, gama)
        if(q[0][0]>particulas[i].mejorValor[0]):
            for k in range(0,len(particulas[i].mejorValor)):
                particulas[i].mejorValor[k]=copy.deepcopy(q[0][k])

            particulas[i].vectorMejorPosicion=copy.deepcopy(particulas[i].vectorPosicion)
           
            if(q[0][0]>enjambre.mejorValoracionGlobal[0]):
                for k in range(0,len(enjambre.mejorValoracionGlobal)):
                    enjambre.mejorValoracionGlobal[k]=copy.deepcopy(q[0][k])
                enjambre.vectorMejorPosicion=copy.deepcopy(particulas[i].vectorMejorPosicion)
                
    enjambre.valoresQs.append(copy.deepcopy(enjambre.mejorValoracionGlobal))
    
    while(numIteraciones<numMasIteraciones):
        w=decimal.Decimal(((0.5)*((numMasIteraciones-numIteraciones)/numMasIteraciones))+0.4)
        for i in range(0,len(particulas)):
            for d in range(0,tamanio):
                rp=decimal.Decimal(np.random.uniform(0,1))
                rg=decimal.Decimal(np.random.uniform(0,1))

                particulas[i].vectorVelocidad[d]=copy.deepcopy(w*(decimal.Decimal(particulas[i].vectorVelocidad[d]))+wp*rp*(decimal.Decimal(particulas[i].vectorMejorPosicion[d]-particulas[i].vectorPosicion[d]))+wy*rg*(decimal.Decimal(decimal.Decimal(enjambre.vectorMejorPosicion[d])-decimal.Decimal(particulas[i].vectorPosicion[d]))))
                
                if(particulas[i].vectorVelocidad[d]>0.1):
                    particulas[i].vectorVelocidad[d]=0.1
                if(particulas[i].vectorVelocidad[d]<-0.1):
                    particulas[i].vectorVelocidad[d]=-0.1

                particulas[i].vectorPosicion[d]=copy.deepcopy(particulas[i].vectorPosicion[d]+particulas[i].vectorVelocidad[d])  

                if(particulas[i].vectorPosicion[d]<0):
                    particulas[i].vectorPosicion[d]=decimal.Decimal(0.001)
                if(particulas[i].vectorPosicion[d]>1):
                    particulas[i].vectorPosicion[d]=decimal.Decimal(1.0)

            q=calcularQ(copy.deepcopy(opiniones), arrayCriterios, copy.deepcopy(particulas[i].vectorPosicion), alfa, beta, gama)
            
            if(q[0][0]>particulas[i].mejorValor[0]):
                for k in range(0,len(particulas[i].mejorValor)):
                    particulas[i].mejorValor[k]=copy.deepcopy(q[0][k])

                particulas[i].vectorMejorPosicion=copy.deepcopy(particulas[i].vectorPosicion)
            
                if(q[0][0]>enjambre.mejorValoracionGlobal[0]):
                    for k in range(0,len(enjambre.mejorValoracionGlobal)):
                        enjambre.mejorValoracionGlobal[k]=copy.deepcopy(q[0][k])
                    enjambre.vectorMejorPosicion=copy.deepcopy(particulas[i].vectorMejorPosicion)
                
        enjambre.valoresQs.append(copy.deepcopy(enjambre.mejorValoracionGlobal))
        numIteraciones=numIteraciones+1
    return enjambre

        
