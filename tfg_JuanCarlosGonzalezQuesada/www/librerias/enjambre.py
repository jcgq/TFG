from __future__ import annotations
from abc import ABC, abstractmethod
from typing import List
import os
import numpy as np
from math import *
import funciones as lib
import decimal
decimal.getcontext().prec=5

class Enjambre:
    
    def __init__(self, particulas):
        self.vectorParticulas = particulas
        self.vectorMejorPosicion= particulas[0].vectorPosicion
        self.mejorValoracionGlobal=[0,0,0,0]
        self.valoresQs=[]