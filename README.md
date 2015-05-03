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
$ mage-db-helper install -d your_database -u your_dbname
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

### `DropAllTables`
Remove all tables from database. It useful to prevent reinstalling exists procedures (like these ones).
```sql
CALL DropAllTables();
```
### `ResetBaseUrl`
Reset base URLs in `core_config` table. It useful when you migrate database.
```sql
CALL ResetBaseUrl('old-server.example.com', 'new-one.example.com');
```
