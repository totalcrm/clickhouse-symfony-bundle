# Sample usage clickhouse with symfony
### Quick start
1. Run `composer install`
2. Change clichhouse connection parameters if need it `config/packages/doctrine.yaml`.
3. Run `cd docker && docker-compose up` or you can have clichhouse on local machine
4. Usage:
    - `php bin/console app:create-table`
    - `php bin/console app:create-entry`
    - `php bin/console app:get-entry`
 