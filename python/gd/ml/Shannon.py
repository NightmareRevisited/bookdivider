# -*- coding: utf-8 -*-
# @File : Shannon.py
# @Date : 2018/3/5 15:36
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

from math import log

def calcShannonEnt(dataset):
    numentries = len(dataset)
    labelCounts = {}
    #为所有可能分类创建字典
    for featVec in dataset:
        currentLabel = featVec[-1]
        labelCounts[currentLabel] = labelCounts.get(currentLabel,0)+1

    shannonEnt = 0.0
    for key in labelCounts:
        prob = float(labelCounts[key])/numentries
        shannonEnt -= prob*log(prob,2)
    return shannonEnt
