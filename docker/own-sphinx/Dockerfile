FROM ubuntu:18.04

RUN apt-get update \
    && apt-get install -y mysql-client

RUN apt-get install -y sphinxsearch

RUN mkdir -p /var/data \
    && mkdir -p /var/log \
    && mkdir -p /opt/data

COPY start.sh /start.sh
