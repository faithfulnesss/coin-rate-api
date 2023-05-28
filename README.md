# Bitcoin Rate Service
This project is implemented using Laravel and project implements an API service that allows users to:
- Get the current exchange rate of Bitcoin (BTC) in Ukrainian Hryvnia (UAH).
- Subscribe an email address to receive information about changes in the exchange rate.
- Send an emails to all subscribed users with the latest exchange rate.
# Installation 
1. Clone the repository:
`git clone https://github.com/faithfulnesss/coin-rate-api.git`
2. Change into the project directory:
`cd coin-rate-api`
3. Install the project dependencies using Composer:
`composer install`
# Requirements
To run the service, you will need:
- PHP (8.1 or higher)
- Composer
- Docker (optional)

It is recommended to use Docker by running `docker-compose up`. However, if you decide to use Docker, you should perform the following steps after installation:
1. Enter the Docker container:  
```docker exec -it <container_name> bash```.  
Replace `<container_name>` with the name of your Docker container.
2. Create the `subscriptions.json` file:  
```touch /var/www/coin-rate-api/storage/app/subscriptions.json```
3. Set permissions for storage directory:  
```chown -R www-data:www-data /var/www/coin-rate-api/storage```  
```chmod -R 775 /var/www/coin-rate-api/storage```
# Configuration
Before running the service, you need to configure the API key for CoinMarketCap and the SMTP server. Follow these steps:
1. Open the `.env` file in the project root.
2. Set your CoinMarketCap API key:  
```CMC_API_KEY=your-api-key```
3. Set your SMTP server configuration:  
`SMTP_HOST=your-smtp-host`  
`SMTP_PORT=your-smtp-port`  
`SMTP_USERNAME=your-smtp-username`  
`SMTP_PASSWORD=your-smtp-password`  
4. Generate the application key by running the following command:  
`php artisan key:generate`
# Usage
To start the service, run the following command:  
`docker compose up -d`
# Endpoints
- `GET /api/rate`
  Retrieves the current exchange rate of Bitcoin (BTC) in Ukrainian Hryvnia (UAH).
- `POST /api/subscribe`
  Subscribes an email address to receive information about changes in the exchange rate.  
  `Request Body: { "email" : "example@example.com" }`
- `POST /api/sendEmails`
  Sends email notifications to all subscribed users with the latest exchange rate.
# Testing
The project was tested using the Mailtrap fake SMTP server. Mailtrap allows you to test email functionality in a safe environment without sending actual emails to real recipients.
