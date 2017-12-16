# iAdviser Developer Manual
This document provides an overview of the files used in this tool as well as important variables and pieces of code.
# Files:
config.php
Important Variables:
-$servername, $username, $password, $dbname: All of these hold the relevant information for connecting to our database.

Important Functions:
-No real functionality here, just used for storing login information.

form.php
Important Variables:
-$query: this variable holds the query that will get us the list of courses required to graduate for an InfoSci major
-$classList: this will be the array that the class names are concatenated and stored in

Important Functions:
-A function exists to put together each of the individual parts of a class to form a coherent string (department, class number, and course name)
-This page also dynamically generates a form that lists each of the classes as a form with checkboxes to see what courses the students have taken already.
-The submit button on the form will post the form response to form_check.php

form_check.php
Important Variables:
-$classesTaken: this will hold the list of classes that the user has already taken, obtained by collecting the posted information from the form in form.php
-$cores: this will hold the list of classes that are core classes (meaning everyone needs to take them) to differentiate them from the electives.
-$classesNeeded: this will hold all of the classes that a user still needs to take. Gets this list by cloning the full class list and removing the classes that the user has already taken.

Important Functions:
-isCore(): this function determines if a course is a core course or an elective
-This page generates the $classesNeeded array by individually removing the classes that the user has taken from a copy of the $classList array.

# Our Database
“School” table - This consists of the school and its abbreviation as they are listed by the registrar 
“Major” table - This consists of the name of the major and the id of the school the major belongs to.
“Class” table- This holds information for each class. It has columns for the name, the number, the number of credits and two columns to hold the prerequisites for each class. It also contains a foreign key to the school that the class belongs to.
“Major_has_class” table- Connect each major to all of the classes within the major. It holds the school ids of both major and class as well as the class and major ids. It also holds a column for determining whether each class is a “core” class or “elective” class
