###################
Platform Used
###################

- Php 7.0.3.0
- Laravel 5.6
- Apache 2.4.23

*******************
Release Information
*******************

- Clone the repo
- Open the console and cd to the project root directory.
- Run composer update 
- Run php artisan serve --port=8080
- Using postman or lunch in browser
	- * `GET http://localhost:8080/vehicles/2015/Audi/A3`
	- * `GET http://localhost:8080/vehicles/2015/Toyota/Yaris`
	- * `GET http://localhost:8080/vehicles/2015/Ford/Crown Victoria`
	- * `GET http://localhost:8080/vehicles/undefined/Ford/Fusion`
	- POST http://localhost:8080/vehicles
		- {
		- 	"modelYear": 2015,
		-	"manufacturer": "Audi",
		-	"model": "A3"
		- }
		- {
		-	"modelYear": 2015,
		-	"manufacturer": "Toyota",
		-	"model": "Yaris"
		- }
