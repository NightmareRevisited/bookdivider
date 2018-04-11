# -*- coding: utf-8 -*-
# @File : mongoconn.py
# @Date : 2018/3/8 9:33
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition
from functools import wraps
from pymongo import MongoClient
import sys
import traceback

MongoDBConfig = {
    'host':'127.0.0.1',
    'port':27017,
    'db_name':'bookinfo',
    'username':None,
    'password':None
}
def Singleton(cls):
    instances = {}
    @wraps(cls)
    def getinstance(*args,**kwargs):
        if cls not in instances:
            instances[cls]=cls(*args,**kwargs)
        return instances[cls]
    return getinstance

@Singleton
class mongoConn(object):
    def __init__(self):
        try:
            self.conn = MongoClient(MongoDBConfig['host'],MongoDBConfig['port'])
            self.db = self.conn[MongoDBConfig['db_name']]
            self.username = MongoDBConfig['username']
            self.password = MongoDBConfig['password']
            if self.username and self.password:
                self.connected = self.db.authenticate(self.username,self.password)
            else:
                self.connected = True

        except:
            print traceback.format_exc()
            print 'Connect MongoDB failed!'
            sys.exit(1)


def check_connected(conn):
    if not conn.connected:
        raise NameError,'stat:connected error'

def save(table,value):
    try:
        my_conn = mongoConn()
        check_connected(my_conn)
        my_conn.db[table].save(value)
    except:
        print traceback.format_exc()

def insert(table,value):
    try:
        my_conn = mongoConn()
        check_connected(my_conn)
        my_conn.db[table].insert(value,continue_on_error=True)
    except:
        print traceback.format_exc()

def update(table,conditions,value,s_update=False,s_multi=False):
    try:
        my_conn= mongoConn()
        check_connected(my_conn)
        my_conn.db[table].update(conditions,value,upsert=s_update,multi=s_multi)
    except:
        print traceback.format_exc()

def find(table,value):
    try:
        my_conn = mongoConn()
        check_connected(my_conn)
        return my_conn.db[table].find(value)
    except:
        print traceback.format_exc()

def select_colum(table,value,colum):
    try:
        my_conn = mongoConn()
        check_connected(my_conn)
        return my_conn.db[table].find(value,{colum:1})
    except:
        print traceback.format_exc()


find('bookinfo',{'catagory':{'$ne':''}}).count()
