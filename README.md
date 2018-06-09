# iAdviser Developer Manual
This document provides an overview of the files used in this tool as well as important variables and pieces of code.

# Final Report
# The Problem
The undergraduate program in the School of Information Science (iSchool) has grown rapidly since its inception a few years ago. The advisors in the iSchool are overwhelmed and unable to keep up with the speed in which it is growing. This problem has caused mandatory advising sections to go from being one on one to a group setting. Group advising sessions can be as large as ten students to one advisor. The size of these groups has caused for lower satisfaction in the advising process for both the student and the advisor. This problem is made even worse when advisors have to spend the little bit of time that they do have with each student fixing trivial errors on students four year plans. What could be a simple error when filling out an academic four year plan could turn into a big problem. An incorrect four year plan could lead to a student missing required prerequisites for classes and ultimately cause for a student to not graduate on time.

# Our Solution
iAdvisor is a system that is designed to make the process of creating an academic four year plan painless and simple. The main idea behind iAdvisor is to walk the user through the process of creating their four year plan in an environment that limits possible errors. By eliminating clerical and trivial errors and forcing users to use the same format advisors will not have to spend their valuable time fixing these errors. This method also limits the possibility of a student making a schedule mistake that could have serious consequences.
# How It Works
When a user opens the iAdvisor web application for the first time they are brought to a login page that utilizes the Google authenticator API. The reason for using the Google Authenticator API is because the University of Maryland uses Gmail for its email service. A user is then brought to a homepage where they then can chose to create a four year page. Selecting this option then brings the user to a webpage which asks the user to select their year in college, and classes they have already taken. Once the user hits submit a four year plan is generated that allows them to drag and drop courses into the order that works best for them. The order the classes are first in are based off of a recommended template, but can be customized to fit the users needs. iAdvisor also takes advantage of the umd.io API that allows us to access a large amount of data on courses and details about them like who the teacher is and when they are. This API allows iAdvisor to easily be expanded to include further functionality, such as allowing students to schedule classes alongside their four year plan.

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
