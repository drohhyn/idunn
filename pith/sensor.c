/*
 * sensor.c:
 * Reads the temperature and humidity from a DHT11 sensor
 *
 * Returns 43.0:24.0 (Humidity:Temperature)
 * In case of errors the program returns "Error"
 */

#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#define MAXTIME 85
#define DHTPIN 7
int dht11_dat[5] = {0,0,0,0,0};

void read_dht11_data()
{
  uint8_t laststate = HIGH;
  uint8_t counter = 0;
  uint8_t j = 0, i;

  dht11_dat[0] = dht11_dat[1] = dht11_dat[2] = dht11_dat[3] = dht11_dat[4] = 0;

  // pin low for 18 ms
  pinMode(DHTPIN, OUTPUT);
  digitalWrite(DHTPIN, LOW);
  delay(18);
  // pin high for 40 ms
  digitalWrite(DHTPIN, HIGH);
  delayMicroseconds(40); 
  // prepare
  pinMode(DHTPIN, INPUT);

  // detect change and read data
  for ( i=0; i< MAXTIME; i++) {
    counter = 0;
    while (digitalRead(DHTPIN) == laststate) {
      counter++;
      delayMicroseconds(1);
      if (counter == 255) {
        break;
      }
    }
    laststate = digitalRead(DHTPIN);

    if (counter == 255) break;

    // ignore three results
    if ((i >= 4) && (i%2 == 0)) {
      dht11_dat[j/8] <<= 1;
      if (counter > 16)
        dht11_dat[j/8] |= 1;
      j++;
    }
  }

  // check for 40 bits (8bit x 5 ) and checksum (last byte)
  if ((j >= 40) && 
    (dht11_dat[4] == ((dht11_dat[0] + dht11_dat[1] + dht11_dat[2] + dht11_dat[3]) & 0xFF)) ) {


	float t, h;
        h = (float)dht11_dat[0] * 256 + (float)dht11_dat[1];
        h /= 10;
        t = (float)(dht11_dat[2] & 0x7F)* 256 + (float)dht11_dat[3];
        t /= 10.0;
        if ((dht11_dat[2] & 0x80) != 0)  t *= -1;


    printf("%.2f:%.2f", h, t );

    // Result (Humidity:Temperature)
    //printf("%d.%d:%d.%d", dht11_dat[0], dht11_dat[1], dht11_dat[2], dht11_dat[3]);
  }
  else
  {
    printf("Error");
    exit (0);
  }
}

int main (void)
{

  if (wiringPiSetup () == -1)
    exit (1) ;

  read_dht11_data();

  return 0 ;
}

