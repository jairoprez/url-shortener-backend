# URL Shortener Backend

## Installation

1. Clone or download the project from github onto your local computer.
2. cd into the project root.
3. Install Composer Dependencies using the command **composer install**
4. Create a copy of the **.env** file 
5. Generate an app encryption key using the command **php artisan key:generate**
6. Create an empty database for our application.
7. In the .env file, add database information to allow Laravel to connect to the database.
8. Migrate the database using the command **php artisan migrate**

## Testing your installation

If the backend runs under `http://example.com/url-shortener-backend/`, calling the URL `http://example.com/url-shortener-backend/api/v1/links` should return a JSON structure, e.g.:

```json
{"data":[],"paginator":{"total_count":0,"total_pages":0,"current_page":1,"limit":100}}
```
