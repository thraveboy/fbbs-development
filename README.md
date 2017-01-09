## Synopsis

Fury Bulletin Board System (fbbs).

## Installation

Get sqlite3 command line:

sudo apt-get sqlite3

From base fbbs directory, opy files to /var/www/html:

cp * /var/www/html

Go to the directory to modify permissions:

cd /var/www/html

Change access to database file so apache can access them:

sudo chmod a+wr fbbs.db
sudo chmod a+wr fbbs-user.db
sudo chmod a+wr fbbs-private.db


# If want to create DBs by hand, instead of using default dbs. (must also change access with chmod as in above).


Create initial main public db:

touch fbbs.db
> 
PRAGMA journal_mode=WAL;

Create users table in fbbs.db in sqlite3:

sqite3 fbbs-user.db
> 
PRAGMA journal_mode=WAL;
CREATE TABLE users(id INTEGER PRIMARY KEY ASC, username TEXT UNIQUE NOT NULL, password TEXT NOT NULL, timestamp INTEGER NOT NULL);
CREATE INDEX username_idx ON users(username);
CREATE TABLE auth_tokens(username TEXT PRIMARY KEY NOT NULL, token TEXT NOT NULL, expire TEXT NOT NULL, timestamp INTEGER NOT NULL);
CREATE TABLE user_auth_log(id INTEGER PRIMARY KEY NOT NULL, username TEXT NOT NULL, token TEXT NOT NULL, timestamp INTEGER NOT NULL);

Create private database for modules in sqlite:

sudo touch fbbs-private.db; sudo chmod a+wr fbbs-private.db
>
CREATE TABLE table_write_auth(id INTEGER PRIMARY KEY ASC, tablename TEXTNOT NULL, username TEXT NOT NULL, timestamp INTEGER NOT NULL);
CREATE INDEX table_write_auth_idx ON table_write_auth(tablename);
 


