# -*- coding: utf-8 -*-
# @File : treePlotter.py
# @Date : 2018/3/6 17:49
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

import matplotlib.pyplot as plt

decision_node = {'boxstyle':'sawtooth','fc':'0.8'}
leaf_node = {'boxstyle':'round4','fc':'0.8'}
arrow_args = {'arrowstyle':'<-'}

def plotNode(nodeText,centerPt,parentPt,nodeType):
    createPlot.ax1.annotate(nodeText,xy=parentPt,xycoords='axes fraction',xytext=centerPt,textcoords='axes fraction',va='center',ha='center',bbox=nodeType,arrowprops=arrow_args)

def createPlot():
    fig = plt.figure(1,facecolor='white')
    fig.clf()
    createPlot.ax1 = plt.subplot(111,frameon=False)
    plotNode(u'decision node',(0.5,0.1),(0.1,0.5),decision_node)
    plotNode(u'leaf node',(0.8,0.1),(0.3,0.8),leaf_node)
    plt.show()
