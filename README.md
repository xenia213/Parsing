# Parser  #

## **Task**
It is necessary to write a PHP script that processes the access_log file with the output of information in the form of json.

## **Run the script**
Option 1: #!/bin/bash  
php parser.php access_log

Option 2: script.sh


## **Output**
{
  views: 16,
  urls: 5,
  traffic: 187990,
  crawlers: {
      Google: 2,
      Bing: 0,
      Baidu: 0,
      Yandex: 0
  },
  statusCodes: {
      200 : 14,
      301 : 2
  }
}# Parsing
