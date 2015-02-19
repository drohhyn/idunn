#include <sqlite3.h>
#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <string.h>
#define MAX_TIME 85
#define DHT11PIN 7


int dht11_val[5]={0,0,0,0,0};

void dht11_read_val(sqlite3 *conn, sqlite3_stmt *res)
{
  uint8_t lststate=HIGH;
  uint8_t counter=0;
  uint8_t j=0,i;
  float farenheit;
  for(i=0;i<5;i++)
     dht11_val[i]=0;
  pinMode(DHT11PIN,OUTPUT);
  digitalWrite(DHT11PIN,LOW);
  delay(18);
  digitalWrite(DHT11PIN,HIGH);
  delayMicroseconds(40);
  pinMode(DHT11PIN,INPUT);
  for(i=0;i<MAX_TIME;i++)
  {
    counter=0;
    while(digitalRead(DHT11PIN)==lststate){
      counter++;
      delayMicroseconds(1);
      if(counter==255)
        break;
    }
    lststate=digitalRead(DHT11PIN);
    if(counter==255)
      break;
    // top 3 transistions are ignored
    if((i>=4)&&(i%2==0)){
      dht11_val[j/8]<<=1;
      if(counter>16)
        dht11_val[j/8]|=1;
      j++;
    }
  }
  // verify cheksum and print the verified data
  if((j>=40)&&(dht11_val[4]==((dht11_val[0]+dht11_val[1]+dht11_val[2]+dht11_val[3])& 0xFF)))
  {
    farenheit=dht11_val[2]*9./5.+32;
    char* sql_statement;
    //sprintf(sql_statement, "INSERT INTO pith(123, %d, %d);", dht11_val[0], dht11_val[2]);
    //char* sql_statement = "INSERT INTO pith(123, "+dht11_val[0]+", "+dht11_val[2]+");";
    //sqlite3_prepare(conn, sql_statement, -1, &res, NULL);
    //sqlite3_step(res);
    //sqlite3_finalize(res);
    printf("Humidity = %d.%d %% Temperature = %d.%d *C (%.1f *F)\n",dht11_val[0],dht11_val[1],dht11_val[2],dht11_val[3],farenheit);
  }
  else
    printf("Invalid Data!!\n");
}

int main(void)
{
  // setting up database
  sqlite3 *conn;
  sqlite3_stmt *res;
  int error = 0;
  int rec_count = 0;
  const char *errMsg;
  const char *tail;

  error = sqlite3_open("pith.sl3", &conn);
  if(error)
  {
    printf("Cannot open database");
    exit(0);
  }

  

  //reading sensor and saving data
  printf("Interfacing Temperature and Humidity Sensor (DHT11) With Raspberry Pi\n");
  if(wiringPiSetup()==-1)
    exit(1);
  while(1)
  {
    dht11_read_val(conn, res);
    delay(3000);
  }
  sqlite3_close(conn);
  return 0;
}
