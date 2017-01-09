## Steps for PDF Export Dependencies

`sudo apt-get install libxrender1`

`composer update`

Follow instructions for [Snappy Laravel Installation](https://github.com/barryvdh/laravel-snappy), ignore `wkhtmltoimage`-related stuff

### Snappy Steps:
- For Vagrant: Move wkhtml stuff to `/usr/local/bin`

`cp vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64 /usr/local/bin/`

- Make executable `chmod +x /usr/local/bin/wkhtmltoimage-amd64`


Yehey :)) 

