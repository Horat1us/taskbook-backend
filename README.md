# TaskBook (backend)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Horat1us/taskbook-backend/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Horat1us/taskbook-backend/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Horat1us/taskbook-backend/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Horat1us/taskbook-backend/?branch=master)

Simple list of tasks.

## API
### Authorization
Provide `Authorization` header:
```
Authorization: Bearer ReceivedToken
```
### POST /authorization
Receiving token by username and password
```json
{
  "user": "YourUserName",
  "password": "SomeSecurePassword"
}
```
- Response 400 (validation errors)
```json
"Invalid username or password"
```
- Response 200
```json
{
  "token": "SomeNewTokenHere"
}
```

### GET /authorization
Receiving information about current user
 - Response 403 (wrong token or no token provided)
 - Response 200
 ```json
 {
  "name": "YourUserName"
 }
 ```

## Stack
- Doctrine
- Symfony Components
  - Console
  - Validator
  - HttpFoundation
- PHPUnit
- Scrutinizer CI

## Run

### Development
```bash
./vendor/bin/doctrine orm:schema-tool:create
php -S localhost:8090 -t /path/to/taskbook-backend/web
```

### Production
Server [web](./web) directory in your web service and proxy all requests to [index.php](./web/index.php)

## Requirements

- Every task contain:
  - Username
  - Email
  - Text
  - Image (optional), (max 320x240 with auto-resize)
   
- Index page:
  - List of tasks
    - Order
    - Pagination
  - Creating new tasks:
    - Preview
  