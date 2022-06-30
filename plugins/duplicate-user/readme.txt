Plugin Name: Duplicate User
Plugin URI: http://www.inimist.com
Version: 0.1.0
Author: Arvind K.
Author URI: http://www.devarticles.in

===============

This plugin provides a way to delete duplicate users by ID, user_login or user_email.

An user-base cleaning script! In a case, thousands of users are entered by tempring with the basic wp_user table structure.. where ID was converted to just int field with no PRIMARY KEY or INDEX associated, date field (user_registered) was converted to decimal and user_email field was changed to accept only 20 characters (varchar 20) .. etc.. and thus thousands of records were entered through a script with duplicate email addresses, user_logins and even with duplicate IDs!

Note: At the moment, this script takes care of ID, user_login and user_email fields only. One can easily extend it to check other fields as well.

I will try to make those fields entered through a configuration script in near future.