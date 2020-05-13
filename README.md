## About EasyPay

### Installation
Run `composer install`

Create `.env` from `.env.example`

Run `php artisan key:generate`

Fill Database info:
```php
DB_DATABASE=easypay
DB_USERNAME=root
DB_PASSWORD=
```
Run `php artisan migrate --seed`

It will import countries, currencies, rates.

For the project realization has been used following:
1. Registration and Login of Users.
2. Adding rates by the admin ( currently there is no any roles)
3. User can create wallets for the currencies which has been filled into the platform ( currently by seeder)
4. If user trying to add wallet for the same currency, his data just will be updated. 
5. If the rate for today already provided and user trying to add it again, it will be just updated.
6. When user send money to another user, I am converting his currency to USD then converting USD to the currency of receiver and sum the amount in receivers wallet. 
7. From senders wallet we decreasing that amount.
8. In Transactions we are showing all the transactions made by user, from creating wallet, filling wallet balance to sending and receiving money. 
9. In Transactions section you can see data filtered by date range. 
10. You can download your filtered results in CSV format.
11. In the bottom of transactions we can see users each wallet currency spents and receivings in USD.


