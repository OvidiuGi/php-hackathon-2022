# PHP Hackathon
This document has the purpose of summarizing the main functionalities your application managed to achieve from a technical perspective. Feel free to extend this template to meet your needs and also choose any approach you want for documenting your solution.

## Problem statement
*Congratulations, you have been chosen to handle the new client that has just signed up with us.  You are part of the software engineering team that has to build a solution for the new client’s business.
Now let’s see what this business is about: the client’s idea is to build a health center platform (the building is already there) that allows the booking of sport programmes (pilates, kangoo jumps), from here referred to simply as programmes. The main difference from her competitors is that she wants to make them accessible through other applications that already have a user base, such as maybe Facebook, Strava, Suunto or any custom application that wants to encourage their users to practice sport. This means they need to be able to integrate our client’s product into their own.
The team has decided that the best solution would be a REST API that could be integrated by those other platforms and that the application does not need a dedicated frontend (no html, css, yeeey!). After an initial discussion with the client, you know that the main responsibility of the API is to allow users to register to an existing programme and allow admins to create and delete programmes.
When creating programmes, admins need to provide a time interval (starting date and time and ending date and time), a maximum number of allowed participants (users that have registered to the programme) and a room in which the programme will take place.
Programmes need to be assigned a room within the health center. Each room can facilitate one or more programme types. The list of rooms and programme types can be fixed, with no possibility to add rooms or new types in the system. The api does not need to support CRUD operations on them.
All the programmes in the health center need to fully fit inside the daily schedule. This means that the same room cannot be used at the same time for separate programmes (a.k.a two programmes cannot use the same room at the same time). Also the same user cannot register to more than one programme in the same time interval (if kangoo jumps takes place from 10 to 12, she cannot participate in pilates from 11 to 13) even if the programmes are in different rooms. You also need to make sure that a user does not register to programmes that exceed the number of allowed maximum users.
Authentication is not an issue. It’s not required for users, as they can be registered into the system only with the (valid!) CNP. A list of admins can be hardcoded in the system and each can have a random string token that they would need to send as a request header in order for the application to know that specific request was made by an admin and the api was not abused by a bad actor. (for the purpose of this exercise, we won’t focus on security, but be aware this is a bad solution, do not try in production!)
You have estimated it takes 4 weeks to build this solution. You have 3 days. Good luck!*

## Technical documentation
### Data and Domain model
In this section, please describe the main entities you managed to identify, the relationships between them and how you mapped them in the database.
----------------------------------------------------------------------------------------------------------------------------------
I identified 5 entities. Type_Of_Program, Room, Room_Type, Registration and Program.
Type_Of_Program and Room are in a many-to-many relationship and Room_Type is the table who connects them.
Registration and Program tables is a 1-many relationship.
Program and Room tables we have a 1-many relationship.
Program and Type_Of_Program we have also a 1-many relationship.
I mapped them in the database by creating a connection with the localhost server and the database was created in phpMyAdmin. Each entity has a class assigned to it.
----------------------------------------------------------------------------------------------------------------------------------
### Application architecture
In this section, please provide a brief overview of the design of your application and highlight the main components and the interaction between them.
----------------------------------------------------------------------------------------------------------------------------------
The application is divided into three parts: Config(config folder), Models(object folder) and Controllers(Program and Registration folders). In the Config part I have defined the database and the connection to it. The Models contain the classes definitions of the entities and in the Controllers the main functionalities of the application were created.
----------------------------------------------------------------------------------------------------------------------------------
###  Implementation
##### Functionalities
For each of the following functionalities, please tick the box if you implemented it and describe its input and output in your application:

[x] Brew coffee \
[x] Create programme \ We get the posted data, we validate it and then we are printing a message if the programme was created or not.
[x] Delete programme \ We get the id of the programme, and delete the product.
[x] Book a programme \ First we get the posted data from our request, validate it and then we are printing a message if the registration was executed or not.

##### Business rules
Please highlight all the validations and mechanisms you identified as necessary in order to avoid inconsistent states and apply the business logic in your application.
----------------------------------------------------------------------------------------------------------------------------------
For Creating a registration we have the following validations : Make sure the data is not empty, check if the CNP is valid (length = 13), see if the user tries to enroll into a nonexistent programme, check if the room is full and if the user has another registration at that interval or if he is already enrolled in the programme.

For Creating a programme we have the following validations: Check if an admin token is valid, make sure the data is not empty, check if the programee already exists, if the room does exist and verify if we have another programme at that hour.

For Deleting a programme we have the following validations: Check if an admin token is valid, get the product id and delete it.
----------------------------------------------------------------------------------------------------------------------------------
##### 3rd party libraries (if applicable)
Please give a brief review of the 3rd party libraries you used and how/ why you've integrated them into your project.

##### Environment
Please fill in the following table with the technologies you used in order to work at your application. Feel free to add more rows if you want us to know about anything else you used.
| Name | Choice |
| ------ | ------ |
| Operating system (OS) | Windows 10 x64 |
| Database  | 10.4.22-MariaDB|
| Web server| XAMPP v3.3.0 |
| PHP | 8.1.2 |
| IDE | Atom |

### Testing
In this section, please list the steps and/ or tools you've used in order to test the behaviour of your solution.
----------------------------------------------------------------------------------------------------------------------------------
I used Postman for testing the application in any way possible : Tested the validations, the admin tokens and the functionalities.
----------------------------------------------------------------------------------------------------------------------------------
## Feedback
In this section, please let us know what is your opinion about this experience and how we can improve it:

1. Have you ever been involved in a similar experience? If so, how was this one different?
This was the first hackathon so I can't speak from experience but it was fun.
2. Do you think this type of selection process is suitable for you?
I think it is, because
3. What's your opinion about the complexity of the requirements?
In my opinion, the complexity was manageable.
4. What did you enjoy the most?
I enjoyed the whole process, especially when I managed to finish the assignment.
5. What was the most challenging part of this anti hackathon?
Getting used to the syntax. I knew how to solve, but had troubles trying to implement.
6. Do you think the time limit was suitable for the requirements?
Yes.
7. Did you find the resources you were sent on your email useful?
Yes.
8. Is there anything you would like to improve to your current implementation?
I am sure there is room for optimizations and refactoring to meet the PHP code standards.
9. What would you change regarding this anti hackathon?
Nothing.
