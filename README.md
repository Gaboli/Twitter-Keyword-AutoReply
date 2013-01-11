Twitter-Keyword-AutoRepply
==========================

An automated system to re-tweet based on keyword search.
=======================================================================
    _________________________________________________________________

                                  README

	  Content:

	  1. About
	  2. Description
	  3. System requirements
	  4. Usage

	_______________________________________________________________


  (1) ABOUT

    VERSION: 1.0
    AUTHOR: Devendra Kumar <devendra.kumar@gaboli.com>
    
    
  (2) DESCRIPTION

    Twitter Auto Reply Application is a simple PHP script for better online marketing.This 
    application performs search for certain keywords which stored in the database. Then 
    automatically reply a message to the search result depending on the particular keyword.
    


  (3) SYSTEM REQUIREMENTS

    - PHP >= 5.0.0
    - MySQL >= 5.0.0


  (4) USAGE

    1. To use this application first you need to have a twitter account.
    2. Go to the twitter developer section https://dev.twitter.com/ and create an application. 
    3. Go to the settings and set the Access Read and Write in the Application Type section.
    4. Create Access Token for your application.
    5. Create the database and all the tables defined for this application.
    6. Set values of all the contants defined in Admin.php.
    7. In the main page of this application you can add, edit and delete the keywords and their repective messages for which the search will be performed. 
    8. To run this apllication automatically set the cron job for admin.php according to your requirements.