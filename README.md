# BoomPanel BETA - Admin panel for sourcemod
This is admin panel that I have been working on for quite a while, and it is currently in BETA version, but is already usable. Has been tested only on CS:GO, so other game support right now isn't available.
***

## About BoomPanel
The idea of panel is to provide you features to control your server without beeing ingame or even from mobile, starting from banlist, mute/gags, admin activity and so on. Right now this is in BETA version, so you can expect some bugs
or have some features that are not finished yet, but I do believe some of you could already start using it.

## Installation Guide

There are currently two ways to install BoomPanel:

### Normal installation
the server recommondation for this project is **nginx**

#### Commands
```bash
apt-get update
apt-get install -y php7.0 php7.0-fpm php7.0-mysql php7.0-bcm mysql-server mysql-client

git clone https://github.com/boomix/BoomPanel.git
cd BoomPanel
mysql --host REDACTED -u REDACTED -p < /database.sql
/etc/init.d/php7.0-fpm start
```

#### including nginx
```bash
apt-get install -y nginx
cp ./DOCKER/nginx_boom_panel.conf /etc/nginx/sites-available/default
./DOCKER/php.ini >> /usr/local/etc/php/php.ini

echo "cgi.fix_pathinfo: 0;" >> /etc/php/7.0/fpm/php.ini
```

#### Additional action required
* You need to enable the mysql pdo
* You need to enable the intl pdo
* Be happy! You most probably did it! ^-^

### Docker image (**WIP**)
#### Prerequisites:
  * docker
  * (docker-compose)

#### Installation
```bash
docker pull registry.indietyp.com/BoomPanel
docker run BoomPanel
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
