#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Dec 19 19:29:52 2023

@author: Tristan Hillairet
"""
# imports
import numpy as np

############################### CLASSE DES JOUEURS ############################

class Joueur:
    
    # constructeur
    def __init__(self, nom, filiere, v, d, elo):
        self.nom = nom
        self.filiere = filiere
        self.matchs = v + d
        self.win = v
        self.los = d
        if self.matchs > 0:
            self.ratio = np.round(v * 100 / self.matchs) / 100
        else:
            self.ratio = 0.0
        self.elo = elo
    
    # affichage (simplement le nom)
    def __str__(self):
        nom = self.nom
        return f'{nom}'
    
    # actualisation de l'attribut des matchs joués
    def setMatchs(self):
        self.matchs = self.win + self.los
    
    # actualisation de l'attribut du ratio de victoires
    def setRatio(self):
        self.ratio = np.round(self.win * 100 / self.matchs) / 100
    
    # initialise l'elo du joueur une fois qu'il a joué 3 parties
    def setClassement(self):
        if self.matchs == 3:
            if self.win == 3:
                self.elo = 500
            elif self.win == 2:
                self.elo = 480
            elif self.win == 1:
                self.elo = 460
            elif self.win == 0:
                self.elo = 440

############################### CLASSE DES MATCHS #############################

class Match:
    
    # constructeur général
    def __init__(self, index, v1, v2, p1, p2, velo, pelo):
        self.index = index
        self.v1 = v1
        self.v2 = v2
        self.p1 = p1
        self.p2 = p2
        self.velo = velo
        self.pelo = pelo
    
    # affichage complet
    def __str__(self):
        index = self.index
        v1,v2 = self.v1,self.v2
        p1,p2 = self.p1,self.p2
        delo = self.delo
        return f'''
        Match {index}
        Vainqueur(s): {v1},{v2} 
        Perdant(s): {p1},{p2}
        Gain/Perte d'elo: {delo}
        \n
        '''
    
    # constructeur d'un match simple de classement
    def ClassMatch1v1(index, v, p):
        match = Match(index, v, None, p, None, 0, 0)
        match.delo = 'Match de classement'
        return match
    
    # constructeur d'un match double de classement
    def ClassMatch2v2(index, v1, v2, p1, p2):
        match = Match(index, v1, v2, p1, p2, 0, 0)
        match.delo = 'Match de classement'
        return match
    
    # constructeur d'un match simple d'élo
    def EloMatch1v1(index, v, p):
        velo,pelo = v.elo,p.elo
        match = Match(index, v, None, p, None, velo, pelo)
        match.delo = DiffElo(match.velo, match.pelo, 20)
        return match
    
    # constructeur d'un match double d'élo
    def EloMatch2v2(index, v1, v2, p1, p2, velos, pelos):
        velo,pelo = np.mean(velos),np.mean(pelos)
        match = Match(index, v1, v2, p1, p2, velo, pelo)
        match.delo = DiffElo(match.velo, match.pelo, 10)
        return match
    
    # actualisation de l'elo des joueurs du match
    def setElo(self):
        if not self.delo == 'Match de classement':
            if not self.v1.elo == 0:
                self.v1.elo += self.delo
            else:
                self.v1.setClassement()
            if not self.p1.elo == 0:
                self.p1.elo -= self.delo
            else:
                self.p1.setClassement()
            if not self.v2 == None:
                if not self.v2.elo == 0:
                    self.v2.elo += self.delo
                else:
                    self.v2.setClassement()
            if not self.p2 == None:
                if not self.p2.elo == 0:
                    self.p2.elo -= self.delo
                else:
                    self.p2.setClassement() 
        else:
            self.v1.setClassement()
            self.p1.setClassement()
            if not self.v2 == None:
                self.v2.setClassement()
            if not self.p2 == None:
                self.p2.setClassement()
        

############################### FONCTIONS GEN #################################

# calcul du gain/perte d'elo en fonction des elos initiaux (ra et rb)
def DiffElo(ra, rb, k):
    ea = 1 / (1 + (10**((rb - ra)/400)))
    d = k*(1-ea)
    return np.round(d*10) / 10

# simule un match et actualise les attributs des joueurs
def JoueMatch(match):
    match.v1.win += 1
    match.v1.setMatchs()
    match.v1.setRatio()
    match.p1.los += 1
    match.p1.setMatchs()
    match.p1.setRatio()
    if not match.v2 == None:
        match.v2.win += 1
        match.v2.setMatchs()
        match.v2.setRatio()
    if not match.p2 == None:
        match.p2.los += 1
        match.p2.setMatchs()
        match.p2.setRatio()
    match.setElo()
    print(match)
    return


    


























