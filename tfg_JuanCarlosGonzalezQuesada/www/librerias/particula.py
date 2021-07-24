from __future__ import annotations
from abc import ABC, abstractmethod
from typing import List
import os
import numpy as np
from math import *
import funciones as lib
import decimal
decimal.getcontext().prec=5

class Particula:
    
    def __init__(self, numExpertos, numCriterios, tamanioOpiniones):
        tamanio = (numExpertos*numCriterios*((tamanioOpiniones*tamanioOpiniones)-tamanioOpiniones)+1)
        self.vectorPosicion = np.random.uniform(0.0, 1.0,(tamanio))
        self.vectorVelocidad = np.random.uniform(-0.1,0.1,(tamanio))
        self.vectorMejorPosicion=self.vectorPosicion
        self.mejorValor=[decimal.Decimal(0.0),decimal.Decimal(0.0),decimal.Decimal(0.0),decimal.Decimal(0.0)]