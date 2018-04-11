# -*- coding: utf-8 -*-
# @File : changemongo.py
# @Date : 2018/3/8 15:17
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

from mongoconn import *
import jieba.analyse
import re

for i in find('bookinfo',{}):
    intro = i['intro']
    if intro[:3] == '&#x':
        newintro = re.sub(';?&#x', '\u', intro)
        print newintro
        print newintro[5428:5433]
        newintro=newintro.decode('unicode_escape','ignore')
        newitem = i.copy()
        newitem['intro'] = newintro
        newitem['tfidf_tags'] = jieba.analyse.extract_tags(newintro,topK=10)
        newitem['textrank_tags'] = jieba.analyse.textrank(newintro,topK=10)
        update('bookinfo',i,newitem)
        print '1 changed'

