#/bin/bash
dht11="Error"
path=$(dirname $0)

while [ "$dht11" = "Error" ]
do
        dht11=`$path/sensor`
done
 
humidInnen="`echo $dht11 | awk -F':' '{print $1}'`"
tempInnen="`echo $dht11 | awk -F':' '{print $2}'`"

time_stamp=$(date "+%s")

set -e
set -u

sqlite $path/pith.sl3 "INSERT INTO pith(datetime, temperature, humidity) VALUES ($time_stamp, $tempInnen, $humidInnen);"

