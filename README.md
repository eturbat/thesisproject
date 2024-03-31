# Chronicle - Personal Blog Management System

At The College of Wooster, scheduling oral defenses has traditionally been a challenge, managed manually or via Excel spreadsheets. This project introduces WoOral, a web application designed to streamline this process by employing modern user interface design principles and full-stack development technologies. Tailored for administrators, professors, and students, the system identifies common available timeslots by integrating rooms, professors, and student availabilities. WoOral was developed using the XAMPP stack, which includes Apache (web server), PHP (server-side processing), and MariaDB with phpMyAdmin (database management), along with HTML, CSS, jQuery, and Bootstrap for client-side development. 

## Prerequisites

What things you need to install the software and how to install them:

- PHP 7.x or higher.
- MySQL.
- Apache Web Server (LAMP/WAMP/XAMPP/MAMP setup recommended).

## Installing

Follow these steps to get your development environment running:

1. **Clone the repository**:
   Clone the project repository to your local machine using the following command in your terminal or command prompt:
   \```bash
   git clone https://github.com/eturbat/wooral.git
   \```

2. **Navigate to the WoOral directory**:
   Change into the project directory:
   \```bash
   cd wooral
   \```

3. **Import the database schema**:
   Import the `bookingcalendar.sql` file into your MySQL database. This step assumes you have MySQL installed and running on your machine. You can use phpMyAdmin or the MySQL command line tool:
   \```bash
   mysql -u username -p bookingcalendar < path/to/bookingcalendar.sql
   \```
   Replace `username` with your MySQL username and adjust the path to where your `bookingcalendar.sql` file is located.

4. **Start the Apache and MySQL services**:
   Ensure your Apache web server and MySQL services are running. If you're using a solution stack like XAMPP, WAMP, or MAMP, start these services through the control panel.

5. **Access the WoOral**:
   Open a web browser and navigate to `http://localhost/wooral` to access the project. Adjust the URL based on your local development environment's configuration and the folder name where you placed the project.

## Built With

- [PHP](https://www.php.net/) - The server-side scripting language used.
- [MySQL](https://www.mysql.com/) - The database management system used.
- [Apache](https://httpd.apache.org/) - The web server used.
- [XAMPP](https://www.apachefriends.org/) - The web stack package.


## Application Features & Using the Application

### Admin Panel
#### Setting up the date range (Manage Dates)
Once the admin visits the `https://localhost/wooral/admin_panel.php` URL, they are taken to their respective panel. Here, admins start by defining the timeframe for oral defenses through the `Manage Date` feature. Use the datepicker to pick a start and end date for the defense period. Once dates are set, confirm by clicking the "Set Dates" button, and view the selected range displayed under "Current Defense Date Range" for verification.

#### Adding defense rooms (Manage Rooms)
Once date range for defense has been set, admin should go to the next step: `Manage Rooms` feature. On the left, easily add new rooms by entering a name and clicking the plus icon. Right beside, set the specific availability for each room using a detailed weekly calendar view.

#### Adding professors (Manage Professors)
Once room coordination has been set, add professors who will be attending the oral sessions. Simply type in a professor's name and use the plus icon to add it to the system. A clear list of added professors, each with a deletion option, simplifies managing the faculty involved in orals.

#### Setting up access (Manage Passwords)
After all the necessary data has been entered from the admin end, the system is ready for indicate availabilities by the professors. Here, the admin will need to set up individualized access for professors and students to secure the system.

### Professors Panel
#### Professor availability form
Once professors visits the `https://localhost/wooral/` URL, they are taken to the landing page of WoOral app. Upon clicking the "Professor Panel" button, professors are directed to a authentication page. After authentication (password matches with admin's), professors can specify their availability through a "Professor Availability Form" by choosing their name from dropdown menu.


### Students Panel 
#### Student information form
Using the same authentication mechanism as the professors', students access the 'Students Panel' to begin the process. They start by completing the 'Student Information Form'. Dropdown menus for selecting readers are populated exclusively with professors who have already submitted their availability data. 

#### Personalized scheduling calendar
Once students have completed the form, the system directs them to a personalized calendar that displays available defense dates. These dates are derived from the intersection of the selected professors' availabilities.

#### Booking and confirmation
After selecting a date, students are presented with available times and locations for their defense. Choosing a slot opens a confirmation modal, offering a final review before booking. A confirmation page then summarizes the scheduled defense details and displays them to the student.

