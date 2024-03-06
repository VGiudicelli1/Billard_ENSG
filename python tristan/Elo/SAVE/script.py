#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Dec 19 18:59:57 2023

@author: Tristan Hillairet
"""
# imports
import pandas as pd
import numpy as np
import boo

############################### OUVERTURES DE CSV #############################

# Ouverture et affichage des fichiers !!!!! CSV à remplacer par des emplacements existant !!!!
#path2filiere = '/Users/sonia.capelli/Desktop/Work in Progress/Billard/csvs/filiere.csv'
#path2elo = '/Users/sonia.capelli/Desktop/Work in Progress/Billard/csvs/elo.csv'
path2filiere = "./filiere.csv"
path2elo = "./elo.csv"
path2eloOut = "./eloOut.csv"
elo = pd.read_csv(path2elo, sep=';', encoding='utf-8')
path2matchs = "./matchTest.csv" #str(input('Lien vers les matchs: '))
matchs = pd.read_csv(path2matchs, sep=';', encoding='utf-8')
print(elo)

# contruction des joueurs et d'une liste des noms avec un dictionnaire
dictJoueurs,listNoms = {},[]
for i in range(len(elo)):
    nom = elo['Joueur'][i]
    filiere = elo['Filière'][i]
    win = elo['V'][i]
    los = elo['D'][i]
    elos = elo['Elo'][i]
    joueur = boo.Joueur(nom, filiere, win, los, elos)
    dictJoueurs[nom] = joueur
    listNoms.append(nom)


############################### SIMULATION DES MATCHS #########################

for i in range(len(matchs)):   
    # vérifie si le joueur v1 existe et demande de le rajouter sinon
    if matchs['V1'][i] in listNoms:
        v1 = dictJoueurs[matchs['V1'][i]]
    else:
        print(f"Joueur {matchs['V1'][i]} inconnu")
        ajout = str(input('Ajouter le joueur: ')).upper()
        if ajout == 'OUI':
            newf = str(input('Saisir la filière: '))
            v1 = boo.Joueur(matchs['V1'][i], newf, 0, 0, 0.0)
            dictJoueurs[matchs['V1'][i]] = v1
            listNoms.append(matchs['V1'][i])
            print('')
        else:
            continue
    # vérifie si le joueur p1 existe et demande de le rajouter sinon
    if matchs['P1'][i] in listNoms:
        p1 = dictJoueurs[matchs['P1'][i]]
    else:
        print(f"Joueur {matchs['P1'][i]} inconnu")
        ajout = str(input('Ajouter le joueur: ')).upper()
        if ajout == 'OUI':
            newf = str(input('Saisir la filière: '))
            p1 = boo.Joueur(matchs['P1'][i], newf, 0, 0, 0.0)
            dictJoueurs[matchs['P1'][i]] = p1
            listNoms.append(matchs['P1'][i])
            print('')
        else:
            continue
    # vérifie que le match est simple ou double
    if matchs.isna()['V2'][i] and matchs.isna()['P2'][i]:
        # vérifie si un match simple est un match de classement ou non
        if v1.elo == 0 or p1.elo == 0:
            match = boo.Match.ClassMatch1v1(i+1, v1, p1)
            boo.JoueMatch(match) # Joue un match de classement simple
        else:
            match = boo.Match.EloMatch1v1(i+1, v1, p1)
            boo.JoueMatch(match) # Joue un match d'elo simple
    else:
        # vérifie si le joueur v2 existe et demande de le rajouter sinon
        if matchs['V2'][i] in listNoms:
            v2 = dictJoueurs[matchs['V2'][i]]
        else:
            print(f"Joueur {matchs['V2'][i]} inconnu")
            ajout = str(input('Ajouter le joueur: ')).upper()
            if ajout == 'OUI':
                newf = str(input('Saisir la filière: '))
                v2 = boo.Joueur(matchs['V2'][i], newf, 0, 0, 0.0)
                dictJoueurs[matchs['V2'][i]] = v2
                listNoms.append(matchs['V2'][i])
                print('')
            else:
                continue
        # vérifie si le joueur p2 existe et demande de le rajouter sinon
        if matchs['P2'][i] in listNoms:
            p2 = dictJoueurs[matchs['P2'][i]]
        else:
            print(f"Joueur {matchs['P2'][i]} inconnu")
            ajout = str(input('Ajouter le joueur: ')).upper()
            if ajout == 'OUI':
                newf = str(input('Saisir la filière: '))
                p2 = boo.Joueur(matchs['P2'][i], newf, 0, 0, 0.0)
                dictJoueurs[matchs['P2'][i]] = p2
                listNoms.append(matchs['P2'][i])
                print('')
            else:
                continue
        # vérifie si un match double est un match de classement ou non
        if (v1.elo == 0 and v2.elo == 0) or (p1.elo == 0 and p2.elo == 0):
            match = boo.Match.ClassMatch2v2(i+1, v1, v2, p1, p2)
            boo.JoueMatch(match) # Joue un match de classement double
        else:
            # Calcul de l'elo moyen des vainqueurs
            if v1.elo == 0:
                velos = [v2.elo]
            elif v2.elo == 0:
                velos = [v1.elo]
            else:
                velos = [v1.elo, v2.elo]
            # Calcul de l'elo moyen des perdants
            if p1.elo == 0:
                pelos = [p2.elo]
            elif p2.elo == 0:
                pelos = [p1.elo]
            else:
                pelos = [p1.elo, p2.elo]
            match = boo.Match.EloMatch2v2(i+1, v1, v2, p1, p2, velos, pelos)
            boo.JoueMatch(match) # Joue un match d'elo double

############################### ENREGISTREMENTS ###############################

# création et remplissage des colonnes du nouveau pandas des elos
newNoms,newFilieres,newMJ,newV,newD,newRatio,newElos  = [],[],[],[],[],[],[]
for nom in listNoms:
    joueur = dictJoueurs[nom]
    newNoms.append(joueur.nom)
    newFilieres.append(joueur.filiere)
    newMJ.append(joueur.matchs)
    newV.append(joueur.win)
    newD.append(joueur.los)
    newRatio.append(joueur.ratio)
    newElos.append(np.round(joueur.elo*10)/10)
# création du nouveau pandas des elos à partir des colonnes
newElo = {'Joueur':newNoms,
          'Filière':newFilieres,
          'MJ':newMJ,
          'V':newV,
          'D':newD,
          'Ratio':newRatio,
          'Elo':newElos}
newElo = pd.DataFrame(newElo)
# affichage et enregistrements du csv de l'elo actualisé
print(newElo)
print('')
newElo.to_csv(path2eloOut,index=False,sep=';',encoding='utf-8')
# création et remplissage des colonnes du nouveau pandas des filières
filiereNoms,filiereMJ,filiereV,filiereD,filiereRatio,filiereElos = [],[],[],[],[],[]
for filiere in list(set(newFilieres)):
    filiereNoms.append(filiere)
    v,d,e = 0,0,[]
    for nom in listNoms:
        joueur = dictJoueurs[nom]
        if joueur.filiere == filiere:
            v += joueur.win
            d += joueur.los
            e.append(joueur.elo)
    mj = v + d
    r = np.round(v*100/mj)/100
    filiereMJ.append(mj)
    filiereV.append(v)
    filiereD.append(d)
    filiereRatio.append(r)
    filiereElos.append(np.round(np.mean(e)*10) / 10)
# création du nouveau pandas des filières à partir des colonnes
newFiliere = {'Filière':filiereNoms,
              'MJ':filiereMJ,
              'V':filiereV,
              'D':filiereD,
              'Ratio':filiereRatio,
              'Elo':filiereElos}
newFiliere = pd.DataFrame(newFiliere)
# affichage et enregistrements du csv de l'elo des filières actualisé
print(newFiliere)
newFiliere.to_csv(path2filiere,index=False,sep=';',encoding='utf-8')
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

