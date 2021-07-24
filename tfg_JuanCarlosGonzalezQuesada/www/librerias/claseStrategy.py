from __future__ import annotations
from abc import ABC, abstractmethod
from typing import List
import os
import numpy as np
from math import *
import funciones as fun
import decimal
decimal.getcontext().prec=5





class Problema():
    #El Problema define la interfaz de interés para los clientes.

    def __init__(self, strategy: Strategy) -> None:
#Normalmente, el Contexto acepta una estrategia a través del constructor, 
#pero también proporciona un setter para cambiarla en tiempo de ejecución.

        self._strategy = strategy

    @property
    def strategy(self) -> Strategy:
    #El Problema mantiene una referencia a uno de los objetos de Estrategia. 
    #El Problema no conoce la clase concreta de una estrategia. 
    #Debe trabajar con todas las estrategias a través de la interfaz Strategy.

        return self._strategy

    @strategy.setter
    def strategy(self, strategy: Strategy) -> None:
    #Normalmente, el Contexto permite sustituir un objeto de Estrategia en tiempo de ejecución.
        self._strategy = strategy
        

#--------------------------------------------------------------------------------------------------------

    #Se añaden las distintas funciones que van a internvenir en el proceso
    
    def calcular_consistencia(self, opiniones, opinionExpertosCriterio) -> None:
    #El Contexto delega parte del trabajo al objeto Estrategia 
    #en lugar de implementar múltiples versiones del algoritmo por sí mismo.
        result = self._strategy.calculoconsistencia(opiniones, opinionExpertosCriterio)
        return result

    def calcular_consenso(self, opiniones) -> None:
    #El Contexto delega parte del trabajo al objeto Estrategia 
    #en lugar de implementar múltiples versiones del algoritmo por sí mismo.
        result = self._strategy.calculoConsenso(opiniones)
        return result
    
    
    def calcular_agregacion(self, opiniones, arrayExpertos, arrayCriterios) -> None:
    #El Contexto delega parte del trabajo al objeto Estrategia 
    #en lugar de implementar múltiples versiones del algoritmo por sí mismo.
        result = self._strategy.calculoAgregacion(opiniones, arrayExpertos, arrayCriterios)
        return result
    
    
    def calcular_explotacion(self, matrizAgregada) -> None:
    #El Contexto delega parte del trabajo al objeto Estrategia 
    #en lugar de implementar múltiples versiones del algoritmo por sí mismo.
        result = self._strategy.calculoExplotacion(matrizAgregada)
        return result

#--------------------------------------------------------------------------------------------------------
#Se crea cada clase de forma individual y luego dentro de cada una, se crean las distintas estrategias que se van a seguir
class Consistencia():
    #La interfaz de estrategia declara operaciones comunes a todas las versiones soportadas de algún algoritmo.
    #El Problema utiliza esta interfaz para llamar al algoritmo definido por concretas estrategias.

    @abstractmethod
    def calculoconsistencia(self,opiniones):
        pass

    #Las Estrategias concretas implementan el algoritmo siguiendo la interfaz básica de la Estrategia base. 
    #La interfaz las hace intercambiables en el Contexto.
    
class consistenciaA(Consistencia):
        def calculoconsistencia(self,opiniones, opinionExpertosCriterio):
            aux=fun.funcionNivelConsistencia(opiniones, opinionExpertosCriterio)
            return aux


class consistenciaB(Consistencia):
        def calculoconsistencia(self,opiniones, opinionExpertosCriterio):
            aux=fun.funcionNivelConsistencia(opiniones, opinionExpertosCriterio)
            return aux
    
#--------------------------------------------------------------------------------------------------------
class Consenso():
    #La interfaz de estrategia declara operaciones comunes a todas las versiones soportadas de algún algoritmo.
    #El Problema utiliza esta interfaz para llamar al algoritmo definido por concretas estrategias.

    @abstractmethod
    def calculoConsenso(self,opiniones):
        pass

    #Las Estrategias concretas implementan el algoritmo siguiendo la interfaz básica de la Estrategia base. 
    #La interfaz las hace intercambiables en el Contexto.
    
class consensoA(Consenso):
    def calculoConsenso(self,opiniones):
        aux=fun.funcionNivelConsenso(opiniones)
        return aux 


class consensoB(Consenso):
    def calculoConsenso(self,opiniones):
        aux=(-1)*fun.funcionNivelConsenso(opiniones)
        return aux 

#--------------------------------------------------------------------------------------------------------
class Agregacion():
    #La interfaz de estrategia declara operaciones comunes a todas las versiones soportadas de algún algoritmo.
    #El Problema utiliza esta interfaz para llamar al algoritmo definido por concretas estrategias.

    @abstractmethod
    def calculoAgregacion(self, opiniones, arrayExpertos, arrayCriterios):
        pass

    #Las Estrategias concretas implementan el algoritmo siguiendo la interfaz básica de la Estrategia base. 
    #La interfaz las hace intercambiables en el Contexto.
    
class agregacionOWA(Agregacion):
    def calculoAgregacion(self,opiniones, arrayExpertos, arrayCriterios):
        aux=fun.fun_agregacionOWA(opiniones)
        return aux 


class agregacionIOWA(Agregacion):
    def calculoAgregacion(self,opiniones, valorExpertos, valorCriterio):
        aux=fun.fun_agregacionIOWA(opiniones, valorExpertos, valorCriterio)
        return aux 
#--------------------------------------------------------------------------------------------------------

class Explotacion():
    #La interfaz de estrategia declara operaciones comunes a todas las versiones soportadas de algún algoritmo.
    #El Problema utiliza esta interfaz para llamar al algoritmo definido por concretas estrategias.

    @abstractmethod
    def calculoExplotacion(self,matrizAgregada):
        pass

    #Las Estrategias concretas implementan el algoritmo siguiendo la interfaz básica de la Estrategia base. 
    #La interfaz las hace intercambiables en el Contexto.
    
class explotacionOWAGDD(Explotacion):
    def calculoExplotacion(self,matrizAgregada):
        aux=fun.fun_explotacionOWAGDD(matrizAgregada)
        return aux 


class explotacionOWAGNDD(Explotacion):
    def calculoExplotacion(self,matrizAgregada):
        aux=fun.fun_explotacionOWAGNDD(matrizAgregada)
        return aux 
#--------------------------------------------------------------------------------------------------------

