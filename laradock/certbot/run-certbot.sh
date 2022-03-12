#!/bin/bash

letsencrypt certonly --webroot -w /var/www/letsencrypt -d "$CN" --agree-tos --email "$EMAIL" --non-interactive --text

cp /etc/letsencrypt/archive/"$CN"/cert1.pem /var/certs/"$CN"-cert1.pem
cp /etc/letsencrypt/archive/"$CN"/chain1.pem /var/certs/chain1.pem
cp /etc/letsencrypt/archive/"$CN"/fullchain1.pem /var/certs/fullchain1.pem
cp /etc/letsencrypt/archive/"$CN"/privkey1.pem /var/certs/"$CN"-privkey1.pem

