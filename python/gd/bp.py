# -*- coding: utf-8 -*-
# @File : bp.py
# @Date : 2018/3/8 9:25
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

from book_info_spider import category_list
from mongoconn import *
import numpy as np

for i in find('bookinfo',{}):
    print i