# idunn
example code for raspberry pi

## dependencies
wiringPi: http://wiringpi.com
jQuery: https://jquery.com
jQuery UI: https://jqueryui.com
flotcharts: http://www.flotcharts.org

## compile
```
gcc -o sensor sensor.c -L/usr/local/lib -lwiringPi
```
## usage
### database (sqlite3)
```
$PITH_DIR/pith.sl3
```

### crontab
```
*/2 * * * * sudo $PITH_DIR/pith.sh >> $PITH_DIR/all.log 2>&1
```

### Tasker for Android 

Get Tasker for Android https://tasker.joaoapps.com/ for your phone and import (adb push) the profile

$PITH_DIR/pith/CheckVenting.prf.xml 
