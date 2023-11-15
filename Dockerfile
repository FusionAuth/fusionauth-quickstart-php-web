FROM alpine:3.18.4

WORKDIR /var/www/html

RUN apk add composer=2.6.5-r0 nginx=1.24.0-r7
# delete php82=8.2.12-r0