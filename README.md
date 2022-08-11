# laravel-loan-REST-APIs
It is REST APIs for the loan application, app allows authenticated users to go through a loan application.

## Endpoints

**User Register** `POST /api/auth/register`

**User Login** `POST /api/auth/login`

**Create a loan request** `POST /api/loans`

**Loan approval** `POST /api//loan-status`

**Get user's loan details** `GET /api/loans`

**Add repayment of loan** `POST /api/repayment`

### Project Setup

```
$ git clone https://github.com/wtsjignesh/laravel-loan-REST-APIs.git
$ composer install
Rename the .env.example to .env
php artisan migrate
php artisan key:generate
php artisan serve
```

### Postman Notes

<p><em><strong>Token: </strong>Need to configure authorization 'Headers' with 'Bearer authorized-token'</em></p>
<p><em><strong>Admin: </strong>Admin user you have to set 'is_admin' to 1 in users table</em></p>

