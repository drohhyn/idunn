#! /bin/bash
gpio mode 0 out
#for i in {0..${loops}}
for((i=0;i<$1;i++))
do
	echo $i
	if (( $i % 2 == 0 ))
	then
		gpio write 0 1
	else
		gpio write 0 0
	fi
	sleep $2
done
gpio write 0 0 
