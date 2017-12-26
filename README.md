# BoomPanel<sup>β</sup> <br /> *The* admin panel for sourcemod based servers
<!-- *** -->

## Overview
This admin panel is in the works for quite a while and is currently in **beta**. Is is useable nevertheless, but the project is unfinished and bugs to be expected.
*This software currently only supports CS:GO, due to it's beta stage. Other games will be included.*

### About
The main purpose of the admin panel is to provide the necessary features to control your game server, without the need of creating beeing precent ingame or opening an RCON gateway.

Features include:
* mobile access
* friendly web interface
* balists
* mute/gag
* admin activity
* *and much more...*

Not every feature is yet ready for release and has probably some bugs. Please help us find them or maybe even make some friendly suggestions in the [Discord](https://discord.gg/6x3xBjx).

### How can I help?
Help is appreciated in any way, but most appreciated in the [Discord](https://discord.gg/6x3xBjx) or through [translating](https://poeditor.com/join/project/2ND2NCRiK7).
Other ways that are super helpful are: **pull requests** and **issues** on the GitHub.

If there is a bug that needs immediate attention, or you think that we are ignoring you (we probably do not, we are sometimes really lazy in answering people), shoot me a Discord direct message at indietyp#5629 or write me an email at me@indietyp.com. A detailed description, logs and screenshots are very appreciated. We will work something out<sup>TM</sup>.

***

## Installation Guide
There are currently **two** ways to install BoomPanel:

### Normal installation
the server recommondation for this project is **nginx**

#### commands
```bash
apt-get update
apt-get install -y php7.0 php7.0-fpm php7.0-mysql php7.0-bcm php7.0-intl mysql-server mysql-client

git clone https://github.com/boomix/BoomPanel.git
cd BoomPanel
mysql --host REDACTED -u REDACTED -p < /database.sql
/etc/init.d/php7.0-fpm start
```

#### (optional) setting up nginx
```bash
apt-get install -y nginx
cp ./DOCKER/nginx_boom_panel.conf /etc/nginx/sites-available/default
./DOCKER/php.ini >> /usr/local/etc/php/php.ini

echo "cgi.fix_pathinfo: 0;" >> /etc/php/7.0/fpm/php.ini
```

#### Additional action required
* You need to enable the mysql pdo
* You need to enable the intl pdo

### Docker Image
#### Prerequisites:
  * docker
  * (docker-compose)

#### Installation
```bash
docker pull registry.indietyp.com/boom/boompanel
docker run registry.indietyp.com/boom/boompanel
```
**or**
```bash
docker-compose up -d
```


#### Environment variables used
Environment variable | Value | Example | Default
-------------------- | ------ | ------- | -------
`APIKEY` | [Get it here](http://steamcommunity.com/dev/apikey) | 74BAE66A95B0AB6E9E4443EB23596993 | **_not set_**
`OWNER` | steamid64 of root admin | 76561198056527492 | **_not set_**
`TIMEZONE` | (offset to UTC) | `+2` | `0`
`DEBUG` | Boolean, enables developer mode | `0` or `1` | `0`
`WEBROOT` | URL of the application | http://example.com/boompanel | **_not set_**
`LANG` | ISO 639-1 code for the default language | `de`, `en` | `en`
`DBHOST` | location of the database used | `localhost` | `localhost`
`DBNAME` | database name | `bp` | `boompanel`
`DBUSER` | database user | `bpu` | `root`
`DBPASS` | database user password | `123456` | **_not set_**

#### Ports exposed
* `80`

#### Additional helpful information
* there is also a docker-compose example in the git repo, that should help with the setup

More info will be added later..
