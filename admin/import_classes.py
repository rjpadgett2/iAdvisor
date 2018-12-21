import requests
import csv
import itertools
import pandas as pd
from pandas import DataFrame
from operator import itemgetter


r = requests.get(url='https://api.umd.io/v0/courses/list')
all_classes = r.json()
class_data = open('/Users/Reggie/desktop/classData.csv', 'w')

# csvwriter = csv.writer(class_data)

def createNewCSV(jsn):
    with open(jsn, 'w') as outf:
        writer = None  # will be set to a csv.DictWriter later

        for key, item in sorted(jsn.items(), key=itemgetter(0)):
            row = {}
            nested_name, nested_items = '', {}
            for k, v in item.items():
                if not isinstance(v, dict):
                    row[k] = v
                else:
                    assert not nested_items, 'Only one nested structure is supported'
                    nested_name, nested_items = k, v

            if writer is None:
                # build fields for each first key of each nested item first
                fields = sorted(row)

                # sorted keys of first item in key sorted order
                nested_keys = sorted(sorted(nested_items.items(), key=itemgetter(0))[0][1])
                fields.extend('__'.join((nested_name, k)) for k in nested_keys)

                writer = csv.DictWriter(outf, fields)
                writer.writeheader()

            for nkey, nitem in sorted(nested_items.items(), key=itemgetter(0)):
                row.update(('__'.join((nested_name, k)), v) for k, v in nitem.items())
                writer.writerow(row)





def js_r(data):
   with open(data, encoding='utf-8') as f_in:
       return(json.load(f_in))

def csvConverter1(jsn):
     r = zip(*jsn.values())
     header = jsn.keys()
     csvwriter.writerow(header)
     for d in r:
         csvwriter.writerow(d)

def csvConverter2(jsn):
    count = 0
    for emp in jsn:
      if count == 0:
             header = emp.keys()
             csvwriter.writerow(header)
             count += 1
      csvwriter.writerow(emp.values())

# csvConverter2(all_classes)


for item in all_classes:
    s = requests.get(url='https://api.umd.io/v0/courses/' + item['course_id'])
    specific_class = s.json()
    createNewCSV(specific_class)

    # print(specific_class)
    # csvConverter(specific_class)

# def createQuery(json):
#     for class in json:
#

# class_data.close()
# print(r.json())
