# this prohect is done with a many to many relationship
### one user can have multiple meeting
### one meeting can have multiple user
## we make this by using  a pivot table
PLURASIGHT_RESTFUL_API

#### Free TEST GOOGLE LOCATION API FO TESTING
http://py4e-data.dr-chuck.net/json?address=Agrabad&key=42






##### to make  a api controller with all the crud 
##### first  add this
use App\Http\Controllers;

##### to make the controller add this if you want all the CRUD function
php artisan make:controller MeetingController --resource

##### but if you want to make a normal controller
##### for one work only then this command
php artisan make:controller MeetingController