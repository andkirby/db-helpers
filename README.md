# Magento Database Helpers
## Overview
There are DB procedures/functions to help work with Magento DB.

## Composer installation
```shell
$ composer global require andkirby/mage-db-helper
```
`global` parater can be ommited if you won't use this installation globally.

## Install scripts
```shell
$ mage-db-helper install -d dbname -u dbuser -p password1
```
If parameter `--mysql-password (-p)` (MySQL password) is ommited you may set it in dialog to make this command secured.
Also you may skip asking password with parameter `--no-password (-o)`;

### More
To get full help about parameters please use standard `--help (-h)` parameter.
```shell
$ mage-db-helper install -h
```

## Usage Helpers in Database
There are available helpers:
- `DropAllTables`,
- `ResetBaseUrl`.
- `ResetAdmin`.

### `DropAllTables`
Remove all tables from database. It useful to prevent reinstalling exists procedures (like these ones).
```sql
CALL dbname.DropAllTables();
```
### `ResetBaseUrl`
Reset base URLs in `core_config` table. It useful when you migrate database.
```sql
CALL dbname.ResetBaseUrl('old-server.example.com', 'new-one.example.com');
```
### `ResetAdmin`
Reset password, username (set to `admin`) and locking of first admin user (ID #1) in `admin_user` table. It useful to reset admin user quickly.
```sql
CALL dbname.ResetAdmin('your-new-password');
```

Note: `dbname` could be omitted in examples if you run `use dbname;` before.
