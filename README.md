Project Setup Info

Requirements: PHP 8.1 or higher

Clone the project by using the following command.

git clone https://github.com/mhq29/xm-exercise-test.git

Now run the following command in project's directory

composer install

Create a .env file with and copy the contents from .env.example file to that .env file. 
Set  DB_CONNECTION=sqlite and DB_DATABASE=:memory:  to run the test cases, as we dont have any database for this project.

Now run the following command in project's directory

php artisan key:generate

php artisan optimize:clear

php artisan serve

Go to http://localhost:8000, if your port is not 8000 use whatever port your command line is showing.

<!-- After running the project, you will see the form to get historical data as required in assignment  -->

Testing:

To run only unit tests, use the following command

php artisan test --filter FormControllerTest

php artisan test --filter CommonTest

<!-- Note:I have used the simple php mail function for the email so its not mandatory that email will be recieved on the given email address. In order to see that if its working fine just run the above test, the mail function will return true if its success. 
I used php mail function,because currently i didn't have SMTP account for the testing. Sorry for the inconvenience  -->

Thanks!