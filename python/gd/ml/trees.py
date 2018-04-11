# -*- coding: utf-8 -*-
# @File : trees.py
# @Date : 2018/3/5 16:08
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

import numpy as np
import operator
import matplotlib.pyplot as plt
from Shannon import calcShannonEnt
import treePlotter

def createDataset():
    dataset = [
        [1,1,'maybe'],
        [ 1,1,'yes'],
        [1,1,'yes'],
        [1,0,'no'],
        [0,1,'no'],
        [0,1,'no']
    ]

    labels = ['no surfacing','flippers']
    return dataset,labels

def splitDataSet(dataset,axis,value):
    retDataset = []
    for featVec in dataset:
        if featVec[axis] == value:
            reducedFeatVec = featVec[:axis]
            reducedFeatVec.extend(featVec[axis+1:])
            retDataset.append(reducedFeatVec)
    return retDataset

def chooseBestFeatureToSplit(dataset):
    numFeatures = len(dataset[0])-1
    baseEntrophy = calcShannonEnt(dataset)
    bestInfoGain = 0.0
    bestFeature = -1
    for i in range(numFeatures):
        featureList = [example[i] for example in dataset]
        uniqueVals = set(featureList)
        newEntrophy = 0.0
        for value in uniqueVals:
            subDataSet = splitDataSet(dataset,i,value)
            prob = len(subDataSet)/float(len(dataset))
            newEntrophy += prob*calcShannonEnt(subDataSet)
        infoGain = baseEntrophy - newEntrophy
        if (infoGain>bestInfoGain):
            bestInfoGain = infoGain
            bestFeature = i
    return bestFeature

def createTree(dataset,labels):
    classList = [example[-1] for example in dataset]
    if classList.count(classList[0]) == len(classList):
        return classList[0]
    if len(dataset[0]) == 1:
        return majorityCnt(classList)
    bestFeat = chooseBestFeatureToSplit(dataset)
    bestFeatLabel = labels[bestFeat]
    myTree = {bestFeatLabel:{}}
    del(labels[bestFeat])
    featValues = [example[bestFeat] for example in dataset]
    uniqueVals = set(featValues)
    for value in uniqueVals:
        subLabels = labels[:]
        myTree[bestFeatLabel][value]=createTree(splitDataSet(dataset,bestFeat,value),subLabels)
    return myTree


def majorityCnt(classList):
    classCount = {}
    for vote in classList:
        classCount[vote] = classCount.get(vote,0)+1
    sortedClassCount = sorted(classCount.iteritems(),key=operator.itemgetter(1),reverse=True)
    return sortedClassCount[0][0]

if __name__ == '__main__':
    myDat,labels = createDataset()
    myTree = createTree(myDat,labels)
    treePlotter.createPlot()
