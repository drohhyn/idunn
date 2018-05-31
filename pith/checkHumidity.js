var result = false;

var values = JSON.parse(global('HTTPD')).data;

var cnt = 0;
values.forEach(function(entry) {
    if(entry.humidity > 55) {
        cnt++;
    }
});

if(cnt >= 3) {
    result = true;
}

setGlobal("%AIROUT", result);
