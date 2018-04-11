# -*- coding: utf-8 -*-
# @File : book_info_spider.py
# @Date : 2018/2/28 10:43
# @Author : Changning Yang (thevile@126.com)
# @Editor : PyCharm Community Edition

import requests
from lxml import etree
from urllib2 import unquote
import re
from mongoconn import *
import time
import random
import jieba.analyse


def delparen(s):
    return s.strip().split('(')[0]


headers = {
        'User-Agent':'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
        'Cookie':'at-main=Atza|IwEBIIZQJH5Y_3_2ixYl-tfkK4CVImW8cPhpyzEwmwZD8qxpiSkCs6VDsQ0jnQ5rt7Q4qRmN9rt5Nbmhoj8blB3pNGow02S49GiqzGWu6L_X20t4U9lY7ZKjBgqTX7AiTCmU7KmpQFN90JIYs6JurnMQedLPtezR5CupJZVG85sRbuacOpWCsE16KreT0t8xKM9eOEfoc5oPJNw6jpgMci-8LKjNeY4srR5I4mClQ0YF7Bo2ziarxo3YDQbHGKMp4vKFdHbsJmtRWk_ocl1RjVjp1MjUtYCSCM5QrtuywPfLYgNMDGLUB9TK8fbfMHz1_scm-Zm0jsiBOBHe53ZIm9AHn9dzfGCsv44Ax0K4Y7DyOtYy7ZoMoEc35CGCn1LKkKv_ZON7zMM1GtxmURaf45_bMFyU; sess-at-main="sU/5pbs3v30J/1xLY8JfcVdEuP+IZ2ZEuH1hSx1qqd0="; sst-main=Sst1|PQG1CnGoYz1X5ZOYatdCzmSLCNoyAKvYElPMq9osy36G888u_67eQAW4JLu5UETHzQeMen65NAjF59Q9G5vB6XQ1gvNQIaEjEOBoZgZCr-srLGrVDJVjM2ZZK-njsXWz6ux6FjI_solR7xtIzx71jZ3gog-FlzosTg3iAsYze0IHsfoRfNwN9wunnuexigoRdmafa5yTLA0GGM2ubioId_AvZAjfCZXWxym355FvtVPtqZuhGA4AIkrqkKYaE7rcxO4A10i6dxKpvp3zQtaBkV2IqA; x-acbcn="hxK2QsTGqYKCkWrJ@mPMPioy8bF@LiymvHlcHYOmU87pWXJnujDb@PJz1hp4kmj2"; ubid-acbcn=459-0941440-8709721; session-id=458-1397528-4141426; s_nr=1519717752015-Repeat; s_vnum=1951703548617%26vn%3D3; s_dslv=1519717752018; x-wl-uid=1p4UIWkhh+vu9bVI9ut9NIOoizzeCxqQ/QkT9WHEQ8qJFL1JO83tFOoRumvVPZh1Wx72LoMAhkqa1bPID2xFlPGknY4hgUdZUbErTn1h35a12aGxe8yaxFGHqRO8WAt87QYFNZtSTj3I=; session-token="UwGXP9HKoCH7o/s/e9whq9bo2/H6GFDCxt+c6uMTqs9CSgHD7dYxf3fh7b/iTMRDJyr/p21DgUuSWDl/JhTjpPrH5YP9qFwuh5tn3jyE530RaC7Ch/g863Pd4OYNrsigw6N7HAc/ro8PishTXhm0wb1IyjJcIDwSouP0RguQV73i2D4g/RK5tix7lmQHdCMXSdVg70p3TcynnOUU6afArC6Ev8EF6O8DS09DiZQR0zRkJ9pU5kXjNpKVU2Bg3B1WFJejfOyCfEU="; csm-hit=s-N0FCZZPZYV931AS2XAMG|1520054142796; session-id-time=2082729601l',
    }

url = 'https://www.amazon.cn/gp/bestsellers/books/ref=zg_bs_nav_0'
initial_response = requests.get(url)
time.sleep(1)
et = etree.HTML(initial_response.text)
category_list = et.xpath('//*[@id="zg_browseRoot"]/ul/ul/li/a/text()')

if __name__=='__main__':
    stopwordspath = 'stopword.txt'
    url_list = et.xpath('//*[@id="zg_browseRoot"]/ul/ul/li/a/@href')
    for i in range(0,len(category_list)):
        headers['Referer'] = url_list[i] + '/ref=zg_bs_nav_b_1_b'
        for j in range(1, 11):
            node_url = url_list[i] + '/ref=zg_bs_%s_pg_%d?ie=UTF8&pg=%d&ajax=1' % (url_list[i].split('/')[-1], j, j)
            node_response = requests.get(node_url, headers=headers)
            node_et = etree.HTML(node_response.text)
            node_url_extra = node_url + '&isAboveTheFold=0'
            node_response_extra = requests.get(node_url_extra, headers=headers)
            node_et_extra = etree.HTML(node_response_extra.text)

            node_url_list_orig = node_et.xpath(
                '//*[@id="zg_critical"]/div/div[1]/div/div[2]/a/@href'
            )+node_et_extra.xpath(
                '//*[@id="zg_nonCritical"]/div/div[1]/div/div[2]/a/@href'
            )
            node_url_list = ['https://www.amazon.cn'+ele for ele in node_url_list_orig]
            print node_url_list
            for nurl in node_url_list:
                try:
                    bookinfo = {}
                    nurl_response = requests.get(nurl,headers=headers)
                    etr = etree.HTML(nurl_response.text)
                    try:
                        bookinfo['title']=delparen(etr.xpath('//*[@id="productTitle"]/text()')[0])
                    except:
                        bookinfo['title'] = delparen(etr.xpath('//*[@id="ebooksProductTitle"]/text()')[0])
                    bookinfo['author']=delparen(etr.xpath('//*[@id="byline"]/span[1]/a/text()')[0])
                    bookinfo['catagory'] = category_list[i]
                    patt = re.compile('bookDescEncodedData = "(.*)"')
                    urlcode_desc = str(re.search(patt,nurl_response.text).group(1))
                    desc = unquote(urlcode_desc).decode('utf-8')
                    bookinfo['intro']=re.sub('<.*?>','',unquote(urlcode_desc).decode('utf-8'))
                    textranktags = jieba.analyse.textrank(bookinfo['intro'],topK=10)
                    bookinfo['textrank_tags'] = textranktags
                    tfidftags = jieba.analyse.extract_tags(bookinfo['intro'],topK=10)
                    bookinfo['tfidf_tags'] = tfidftags
                    save('bookinfo',bookinfo)
                    time.sleep(random.uniform(0,0.5))
                except:
                    print traceback.format_exc()
                finally:
                    print 'page%d finished' % node_url_list.index(nurl)
            print i,'-',j
