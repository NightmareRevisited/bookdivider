# -*- coding: utf-8 -*-
# @File : book_divide.py
# @Date : 2018/3/13 16:38
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

from mongoconn import *
import numpy as np
import jieba.analyse
import sys

reload(sys)
sys.setdefaultencoding('utf-8')

catagory_list = [u'\u6587\u5b66', u'\u5c0f\u8bf4', u'\u4f20\u8bb0', u'\u52a8\u6f2b\u4e0e\u7ed8\u672c', u'\u827a\u672f', u'\u5c11\u513f', u'\u5bb6\u5ead\u6559\u80b2', u'\u5b55\u4ea7\u80b2\u513f', u'\u4eba\u6587\u793e\u79d1', u'\u54f2\u5b66', u'\u653f\u6cbb\u4e0e\u519b\u4e8b', u'\u5fc3\u7406\u5b66', u'\u5386\u53f2', u'\u6cd5\u5f8b', u'\u56fd\u5b66', u'\u7ecf\u6d4e\u7ba1\u7406', u'\u52b1\u5fd7\u4e0e\u6210\u529f', u'\u6559\u6750\u6559\u8f85\u4e0e\u53c2\u8003\u4e66', u'\u8003\u8bd5', u'\u82f1\u8bed\u4e0e\u5176\u4ed6\u5916\u8bed', u'\u4e2d\u5c0f\u5b66\u6559\u8f85', u'\u5927\u4e2d\u4e13\u6559\u6750\u6559\u8f85', u'\u8f9e\u5178\u4e0e\u5de5\u5177\u4e66', u'\u6559\u80b2', u'\u79d1\u6280', u'\u79d1\u5b66\u4e0e\u81ea\u7136', u'\u8ba1\u7b97\u673a\u4e0e\u4e92\u8054\u7f51', u'\u7535\u5b50\u4e0e\u901a\u4fe1', u'\u7535\u5de5\u6280\u672f', u'\u533b\u5b66', u'\u65c5\u6e38\u4e0e\u5730\u56fe', u'\u70f9\u996a\u7f8e\u98df\u4e0e\u9152', u'\u5a5a\u604b\u4e0e\u4e24\u6027', u'\u65f6\u5c1a', u'\u8fd0\u52a8\u5065\u8eab', u'\u5efa\u7b51', u'\u5bb6\u5c45', u'\u5a31\u4e50', u'\u517b\u751f\u4fdd\u5065', u'\u4f53\u80b2', u'\u8fdb\u53e3\u539f\u7248\u4e66', u'\u671f\u520a\u6742\u5fd7', u'\u5728\u7ebf\u8bd5\u8bfb', u'\u9884\u552e\u56fe\u4e66', u'\u7ecf\u6d4e\u56fe\u4e66']

def getTags(query={}):
    tags_list = []
    k=0
    global catagory_list
    cursor = find('bookinfo',{'catagory':{'$ne':''}})
    cursor_length = cursor.count()
    classList = np.zeros(cursor_length)
    for bookitem in cursor:
        tag = np.append(np.array(bookitem['textrank_tags']),bookitem['author'])
        classList[k] = catagory_list.index(bookitem['catagory'])
        tags_list.append(tag)
        k+=1
    return tags_list,classList

def createVocabList(tags_list):
    vocab_list = set()
    for i in tags_list:
        vocab_list = vocab_list|set(i)
    if u'' in vocab_list:
        vocab_list.remove(u'')
    return list(vocab_list)

def tags2Vec(tags,vocab_list):
    return_vec = np.zeros(len(vocab_list))
    for word in tags:
        if word in vocab_list:
            return_vec[vocab_list.index(word)] += 1
    return return_vec

def trainNBO(train_matrix,train_catagory):
    num_train_tags = len(train_matrix)
    num_words = len(train_matrix[0])
    p_abusive = 1/45.0
    p_num = np.ones([45,num_words])
    p_denom = np.ones(45,dtype=float)*2
    for i in range(num_train_tags):
        p_num[int(train_catagory[i])] += train_matrix[i]
        p_denom[int(train_catagory[i])] += sum(train_matrix[i])
    p_vect = np.zeros([45,num_words],dtype=float)
    for i in range(45):
        p_vect[i] = np.log(p_num[i]/p_denom[i])
    return p_vect,p_abusive

def classifyNB(vec2classify,p_vect):
    p_class = 1/45.0
    p = np.zeros(45,dtype=float)
    for i in range(45):
        p[i] = sum(vec2classify*p_vect[i]) + np.log(p_class)
    p[40]=-1000.0
    return np.argmax(p)

def bookDivide(intro,author):
    book_tags= jieba.analyse.textrank(intro,topK=10)
    book_tags_without_author = book_tags[:];
    book_tags.append(author)
    tags_list, class_list = getTags()
    vocab_list = createVocabList(tags_list)
    train_matrix = []
    for tags in tags_list:
        train_matrix.append(tags2Vec(tags, vocab_list))
    pv, pab = trainNBO(np.array(train_matrix), class_list)
    book_vec = np.array(tags2Vec(book_tags,vocab_list))
    divide_result = catagory_list[classifyNB(book_vec,pv)]
    return book_tags_without_author,divide_result

if __name__ == '__main__':
    intro = sys.argv[1].decode('unicode_escape',"ignore")
    author = sys.argv[2].decode('unicode_escape',"ignore")
    textrank_tags,category = bookDivide(intro,author)
    for i in textrank_tags:
        print i
    print category



