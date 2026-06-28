<?php
/* 
------------------
Language: English
------------------
*/

$lang = array();

// Global

$lang['LANG_CODE'] 			= 'en'; //en,es,de,pl, etc.
$lang['LANG_LONG'] 			= 'English';

$lang['SITE_TITLE'] 		= 'Domain Reminder';

$lang['ADMIN'] 				= 'Admin';
$lang['SETTINGS'] 			= 'Settings';
$lang['SIGNOUT'] 			= 'Sign Out';

$lang['DASHBOARD'] 			= 'Dashboard';
$lang['DOMAINS'] 			= 'Domains';
$lang['EXPIRING_SOON'] 		= 'Expiring Soon';
$lang['CLIENTS'] 			= 'Clients';
$lang['ADD_CLIENT'] 		= 'Add Client';
$lang['DATABASES'] 			= 'Databases';
$lang['HELP'] 				= 'Help';

$lang['JAN'] 				= 'january';
$lang['FEB'] 				= 'february';
$lang['MAR'] 				= 'march';
$lang['APR'] 				= 'april';
$lang['MAY'] 				= 'may';
$lang['JUN'] 				= 'june';
$lang['JUL'] 				= 'july';
$lang['AUG'] 				= 'august';
$lang['SEP'] 				= 'september';
$lang['OCT'] 				= 'october';
$lang['NOV'] 				= 'november';
$lang['DEC'] 				= 'december';

$lang['SUN'] 				= 'sunday';
$lang['MON'] 				= 'monday';
$lang['TUE'] 				= 'tuesday';
$lang['WED'] 				= 'wednesday';
$lang['THU'] 				= 'thursday';
$lang['FRI'] 				= 'friday';
$lang['SAT'] 				= 'saturday';

// index.php (Login)

$lang['LOGIN'] 				= 'Login';
$lang['SIGNIN_TO_START'] 	= 'Sign in to start your session';
$lang['USERNAME'] 			= 'Username';
$lang['PASSWORD'] 			= 'Password';
$lang['REMEMBER_ME'] 		= 'Remember Me';
$lang['SIGNIN'] 			= 'Sign In';

$lang['ALERT'] 				= 'Alert!';
$lang['WRONG_LOGIN'] 		= 'Wrong username and/or password.';

// Dashboard

$lang['EXPIRING_X_DAYS'] 	= 'Domains Expiring Within 45 Days';
$lang['DOMAIN_NAME'] 		= 'Domain Name';
$lang['EXPERATION_DATE'] 	= 'Expiration Date';
$lang['VIEW_MORE'] 			= 'View More';
$lang['DELETE'] 			= 'Delete';
$lang['DELETE_DOMAIN_CONFIRM'] 	= 'Are you sure you want to delete';
$lang['0_DOMAINS_EXPIRING'] = '0 domains expiring in the next 45 days.';

// Domains

$lang['SEARCH'] 			= 'Search';
$lang['CLIENT_NAME'] 		= 'Client Name';
$lang['REGISTRATION_DATE'] 	= 'Registration Date';
$lang['RENEWAL_DATE'] 		= 'Renewal Date';
$lang['REGISTRAR'] 			= 'Registrar ';
$lang['EDIT']				= 'Edit';
$lang['RENEW'] 				= 'Renovar';
$lang['DELETE_MULTI_CONFIRM'] 	= 'Are you sure you want to delete these?';
$lang['DOMAINS_DELETED'] 	= 'Domain(s) deleted.';
$lang['DATA_DELETED'] 		= 'Associated encrypted data deleted.';
$lang['0_DOMAINS_IN_DB'] 	= '0 domains found in database';

// Domains-Edit

$lang['EDIT_DOMAIN'] 		= 'Edit Domain';
$lang['SELECTED_DATA_DELETED'] 	= 'Selected data was deleted.';
$lang['DATA_ADDED'] 			= 'Data encrypted & added!';
$lang['DOMAIN_SUCCESS_UPDATED'] = 'Domain was successfully updated!';
$lang['DOMAIN_WHOIS_FAIL_REG_DATE'] = 'Domain Whois failed for registration date.';
$lang['DELETE_SELECTED'] 	= 'Delete Selected';
$lang['PLEASE_SELECT'] 		= 'Please Select';
$lang['OTHER'] 				= 'Other';
$lang['GET_AUTO_WHOIS'] 	= 'Get Auto-Whois Expiration date, Registration date, Registrar name?';
$lang['WHOIS_REPLY'] 		= 'WHOIS Reply';
$lang['DOMAIN_DATA'] 		= 'Domain Data (FTP, MySQL, Webmail, etc.)';
$lang['TYPE'] 				= 'Type';
$lang['COMMENT'] 			= 'Comment';
$lang['ADD_DATA'] 			= 'Add Data';
$lang['DATA_TYPE'] 			= 'Data Type';
$lang['FTP_LOGIN'] 			= 'FTP Login';
$lang['DATABASE_INFO'] 		= 'Database Info';
$lang['WEBMAIL'] 			= 'Webmail';
$lang['CONTROL_PANEL'] 		= 'Control Panel';
$lang['HOST_NAME'] 			= 'Host Name';
$lang['DATABASE_NAME'] 		= 'Database Name';
$lang['PLEASE_NOTE'] 		= 'Please note';
$lang['FIELDS_ENCRYPTED_IN_DB'] = 'The above fields will be encrypted in the database.';
$lang['0_RECORDS_IN_DB'] 	= '0 records found in database';

// Domains-Expiring 

$lang['NOTE'] 				= 'Note';
$lang['RED'] 				= 'Red';
$lang['RED_TEXT'] 			= 'Expiring in less than 30 days.';
$lang['YELLOW'] 			= '<span style="background-color: #FFFFCC;">[Yellow]</span> - Expiring in 30 to 60 days.';
$lang['YELLOW_TEXT'] 		= '<span style="background-color: #FFFFCC;">[Yellow]</span> - Expiring in 30 to 60 days.';
$lang['GREEN'] 				= '<span style="background-color: #E6FDC6;">[Green]</span> - Expiring in 60 to 90 days.';
$lang['GREEN_TEXT'] 		= '<span style="background-color: #E6FDC6;">[Green]</span> - Expiring in 60 to 90 days.';
$lang['RENEW_TEXT'] 		= 'Renew button will appear only next to domains with existing renewal link. You can add renewal link by going to the Domain Edit page.';
$lang['SEND_NOTICE'] 		= 'Send 45 day notice...';

// Email (cron.php)

$lang['DOMAIN_NOTICE']		= 'Domains Expiration Notice.';
$lang['YOUR_REMINDER']		= 'Your reminder that you have some domains expiring soon.';
$lang['HI_THERE']			= 'Hi there, a friendly reminder, don\'t forget you have domains expiring soon.';
$lang['YOUR_LIST']			= 'Here is your list of domains which will be expiring within the next';
$lang['DAYS']				= 'days';
$lang['NO_DOMAINS_EXPIRING']= 'You have no domains currently expiring.';
$lang['CLIENT']				= 'Client';
$lang['SEND']				= 'Send';
$lang['A_NEW_LIST']			= 'a new list of domains that will be expiring within the next';

// Clients

$lang['CLIENT_COMPANY']		= 'Client Company';

$lang['CLIENTS_DELETED']	= 'Client(s) Deleted';
$lang['0_CLIENTS_IN_DB'] 	= '0 clients found in database';

// Clients-Add


$lang['ADD_EDIT_CLIENT']	= 'Add/Edit Client';

$lang['CLIENT_SUCCESS_ADDED']	= 'Client was successfully added!';
$lang['CLIENT_SUCCESS_UPDATED']	= 'Client was successfully updated!';
$lang['CLIENT_UPDATE_ERROR']	= 'Oops, an error occurred while trying to update client.';
$lang['DOMAIN_SUCCESS_ADDED']	= 'Domain was successfully added!';
$lang['DOMAIN_RENEWAL_DATE_FAIL']	= 'Domain Whois failed for renewal date.';
$lang['DOMAIN_UPDATE_ERROR']	= 'Oops, an error occurred while trying to create new domain.';
$lang['DOMAIN_WHOIS_FAIL']		= 'Domain Whois failed for renewal date.';
$lang['DOMAIN_WHOIS_SUCCESS']	= 'Domain Whois succeeded.';
$lang['DOMAIN_WHOIS_FAIL_REG']	= 'Domain Whois failed or domain not registered.';
$lang['SELECTED_DOMAINS_DELETED']	= 'Selected domains were deleted.';

$lang['CONTACT_NAME']		= 'Contact Name';
$lang['JOB_TITLE']			= 'Job Title';
$lang['WEBSITE']			= 'Website';
$lang['CONTACT_EMAIL']		= 'Contact Email';
$lang['PHONE_NUMBER']		= 'Phone Number';
$lang['COMPANY_ADDRESS']	= 'Company Address';
$lang['SAVE']				= 'Save';
$lang['ADD_DOMAIN']			= 'Add Domain';
$lang['RENEWAL_URL']		= 'Renewal URL';

// Backup

$lang['SYSTEM_DATABASE']	= 'System Database';
$lang['DB_BACKUP']			= 'DB BACKUP';
$lang['BACKUP']				= 'Backup';
$lang['BACKUP_TEXT']		= 'You will be prompted to download the file. A copy will be saved in the "backups" folder as well.';
$lang['DB_RESTORE']			= 'DB RESTORE';
$lang['RESTORE']			= 'Restore';
$lang['RESTORE_TEXT']		= 'Please note: This operation is irreversable unless you have old backup.';
$lang['DB_UPDATE']			= 'DB UPDATE';
$lang['UPDATE']				= 'Update';
$lang['UPDATE_TEXT']		= 'Depending on the amount of domains in your database update can take up to 5 minutes. Registrar and whois reply - will be updated for ALL domains. Registration date and renewal date will be updated ONLY IF VALID DATES will be returned in Whois, otherwise they will stay the same. Operation is irreversable.';

// Help

$lang['CRON_SETUP']			= 'CRON Setup';
$lang['CRON_NONTIFICATIONS']= 'CRON Notifications';

$lang['CRON_TEXT_1']		= 'If your hosting account is on LINUX/UNIX system you can setup a CRON job to point to this URL:<br><code>https://www.YourDomain.com/ScriptPath/cron.php?cron=do&d=31</code><br>("31" is the number of days which to be notified) <br>These notifications will be sent to the admin\'s email.';

$lang['CRON_TEXT_3']		= 'Dont have access to CRON on your system, or do not know how to use? Try <a href="https://cron-job.org" target="_blank">Cron-Job.org</a>. FREE and Easy scheduled execution of website scripts.';

$lang['ADD_NEW_LANG']		= 'Add New Language';
$lang['NEW_LANG_TEXT_1']	= 'To add a new language, you will need to do the following';
$lang['NEW_LANG_STEP_1']	= 'Copy and rename the file "<code>./langs/lang.en.php</code>", changing "<code>en</code>" to reflect your language.';
$lang['NEW_LANG_STEP_2']	= 'Update file "<code>./includes/lang-menu.php</code>", to include your new language. (ex.: "<code>settings.php?lang=xx">XX</code>")';
$lang['NEW_LANG_STEP_3']	= 'Update file "<code>./includes/languages.php</code>", by un-commenting the section below "<code>/* Add new lang here... */</code>" and adding your 2 digit language code.';

$lang['CREDITS']			= 'Credits';
$lang['RESOURCES_USED']		= 'Resources used in program';

// Settings

$lang['LANGUAGE']			= 'Language';
$lang['NEW_PASSWORD']		= 'New Password';
$lang['CONFIRM']			= 'Confirm';
$lang['EMAIL_FOR_CRON']		= 'Email for CRON';
$lang['SHOW_WHOIS']			= 'Show Whois reply?';
$lang['SHOW_DOMAIN_DATA']	= 'Show Domain Data?';
$lang['SHOW_DOMAIN_DATA_UPDATED'] = 'Show Domain Data Updated.';
$lang['YES']				= 'Yes';
$lang['NO']					= 'No';

$lang['USERNAME_UPDATED']	= 'Username updated!';
$lang['EMAIL_UPDATED']		= 'Email updated!';
$lang['PASSWORD_UPDATED']	= 'Password updated!';
$lang['PASSWORD_NOT_MATCH']	= 'Passwords don\'t match!';

$lang['CRON_NOTI_LANG']		= 'CRON Notification Language (Language that emails will be sent.)';
$lang['CRON_LANG_UPDATED']	= 'Language Updated';

$lang['CHOOSE_YOUR_LANG']	= 'Choose your preferred language';

$lang['VERSION'] 			= 'Version';
$lang['FOOTER_CREDITS'] 	= '<a href="https://www.wiredrabbitweb.com">Wired Rabbit Web</a>.</strong> All rights reserved.';

// Install file warnings
$lang['INSTALL_FILE_WARNING_1'] 			= 'The file';
$lang['INSTALL_FILE_WARNING_2'] 			= 'is present. It is advised to delete it.';
$lang['INSTALL_FILE_DELETE_CONFIRM'] 			= 'Are you sure you want to delete this file?';
$lang['CANCEL'] 			= 'Cancel';

?>