<?php
/* 
------------------
Language: Polski
------------------
*/

$lang = array();

// Global

$lang['LANG_CODE'] 			= 'pl'; //en,es,de,pl, etc.
$lang['LANG_LONG'] 			= 'Polski';

$lang['SITE_TITLE'] 		= 'Domain Reminder';

$lang['ADMIN'] 				= 'Admin';
$lang['SETTINGS'] 			= 'Ustawienia';
$lang['SIGNOUT'] 			= 'Wyloguj';

$lang['DASHBOARD'] 			= 'Panel';
$lang['DOMAINS'] 			= 'Domeny';
$lang['EXPIRING_SOON'] 		= 'Wkrótce wygasa';
$lang['CLIENTS'] 			= 'Klienci';
$lang['ADD_CLIENT'] 		= 'Dodaj klienta';
$lang['DATABASES'] 			= 'Bazy danych';
$lang['HELP'] 				= 'Pomoc';

$lang['JAN'] 				= 'styczeń';
$lang['FEB'] 				= 'luty';
$lang['MAR'] 				= 'marzec';
$lang['APR'] 				= 'kwiecień';
$lang['MAY'] 				= 'maj';
$lang['JUN'] 				= 'czerwiec';
$lang['JUL'] 				= 'lipiec';
$lang['AUG'] 				= 'sierpień';
$lang['SEP'] 				= 'wrzesień';
$lang['OCT'] 				= 'październik';
$lang['NOV'] 				= 'listopad';
$lang['DEC'] 				= 'grudzień';

$lang['SUN'] 				= 'Niedziela';
$lang['MON'] 				= 'Poniedziałek';
$lang['TUE'] 				= 'Wtorek';
$lang['WED'] 				= 'Środa';
$lang['THU'] 				= 'Czwartek';
$lang['FRI'] 				= 'Piątek';
$lang['SAT'] 				= 'Sobota';

// index.php (Login)

$lang['LOGIN'] 				= 'Login';
$lang['SIGNIN_TO_START'] 	= 'Zaloguj się, aby rozpocząć sesję';
$lang['USERNAME'] 			= 'Nazwa użytkownika';
$lang['PASSWORD'] 			= 'Hasło';
$lang['REMEMBER_ME'] 		= 'Zapamiętaj mnie';
$lang['SIGNIN'] 			= 'Zaloguj się';

$lang['ALERT'] 				= 'Alarm!';
$lang['WRONG_LOGIN'] 		= 'Niewłaściwa nazwa użytkownika i / lub hasło.';

// Dashboard

$lang['EXPIRING_X_DAYS'] 	= 'Domeny wygasają w ciągu 45 dni';
$lang['DOMAIN_NAME'] 		= 'Nazwa domeny';
$lang['EXPERATION_DATE'] 	= 'Termin ważności';
$lang['VIEW_MORE'] 			= 'Zobacz więcej';
$lang['DELETE'] 			= 'Usuń';
$lang['DELETE_DOMAIN_CONFIRM'] 	= 'Czy na pewno chcesz usunąć?';
$lang['0_DOMAINS_EXPIRING'] = '0 domen wygasających w ciągu najbliższych 45 dni.';

// Domains

$lang['SEARCH'] 			= 'Szukaj';
$lang['CLIENT_NAME'] 		= 'Nazwa Klienta';
$lang['REGISTRATION_DATE'] 	= 'Data Rejestracji';
$lang['RENEWAL_DATE'] 		= 'Data Odnowienia';
$lang['REGISTRAR'] 			= 'Rejestrator';
$lang['EDIT']				= 'Edytuj';
$lang['RENEW'] 				= 'Odnów';
$lang['DELETE_MULTI_CONFIRM'] 	= 'Czy na pewno chcesz je usunąć?';
$lang['DOMAINS_DELETED'] 	= 'Domeny usunięte.';
$lang['DATA_DELETED'] 		= 'Powiązane zaszyfrowane dane zostały usunięte.';
$lang['0_DOMAINS_IN_DB'] 	= 'Znaleziono 0 domen w bazie danych';

// Domains-Edit

$lang['EDIT_DOMAIN'] 		= 'Edit Domain';
$lang['SELECTED_DATA_DELETED'] 	= 'Wybrane dane zostały usunięte.';
$lang['DATA_ADDED'] 			= 'Dane zaszyfrowane i dodane!';
$lang['DOMAIN_SUCCESS_UPDATED'] = 'Domena została zaktualizowana!';
$lang['DOMAIN_WHOIS_FAIL_REG_DATE'] = 'Data rejestracji domeny Whois nie powiodła się.';
$lang['DELETE_SELECTED'] 	= 'Usuń wybrane';
$lang['PLEASE_SELECT'] 		= 'Proszę wybrać';
$lang['OTHER'] 				= 'Inny';
$lang['GET_AUTO_WHOIS'] 	= 'Uzyskaj datę ważności Auto-Whois, datę rejestracji, nazwę rejestratora?';
$lang['WHOIS_REPLY'] 		= 'WHOIS Odpowiedź';
$lang['DOMAIN_DATA'] 		= 'Dane domeny (FTP, MySQL, poczta internetowa itp.)';
$lang['TYPE'] 				= 'Rodzaj';
$lang['COMMENT'] 			= 'Komentarz';
$lang['ADD_DATA'] 			= 'Dodaj dane';
$lang['DATA_TYPE'] 			= 'Typ danych';
$lang['FTP_LOGIN'] 			= 'FTP Login';
$lang['DATABASE_INFO'] 		= 'Informacje Bazy Danych';
$lang['WEBMAIL'] 			= 'Poczta internetowa';
$lang['CONTROL_PANEL'] 		= 'Panel sterowania';
$lang['HOST_NAME'] 			= 'Nazwa hosta';
$lang['DATABASE_NAME'] 		= 'Nazwa Bazy Danych';
$lang['PLEASE_NOTE'] 		= 'Proszę zanotować';
$lang['FIELDS_ENCRYPTED_IN_DB'] = 'Powyższe pola zostaną zaszyfrowane w bazie danych.';
$lang['0_RECORDS_IN_DB'] 	= '0 records found in database';

// Domains-Expiring 

$lang['NOTE'] 				= 'Notatka';
$lang['RED'] 				= 'Czerwony';
$lang['RED_TEXT'] 			= 'Wygasa za mniej niż 30 dni.';
$lang['YELLOW'] 			= '<span style="background-color: #FFFFCC;">[Yellow]</span> - Wygasa za 30 do 60 dni.';
$lang['YELLOW_TEXT'] 		= '<span style="background-color: #FFFFCC;">[Yellow]</span> - Wygasa za 30 do 60 dni.';
$lang['GREEN'] 				= '<span style="background-color: #E6FDC6;">[Green]</span> - Wygasa za 60 do 90 dni.';
$lang['GREEN_TEXT'] 		= '<span style="background-color: #E6FDC6;">[Green]</span> - Wygasa za 60 do 90 dni.';
$lang['RENEW_TEXT'] 		= 'Przycisk Odnów pojawi się tylko obok domen z istniejącym linkiem odnowienia. Możesz dodać link odnowienia, przechodząc do strony Edycja domeny.';
$lang['SEND_NOTICE'] 		= 'Wyślij powiadomienie z 45-dniowym wyprzedzeniem...';

// Email (cron.php)

$lang['DOMAIN_NOTICE']		= 'Domains Expiration Notice.';
$lang['YOUR_REMINDER']		= 'Your reminder that you have some domains expiring soon.';
$lang['HI_THERE']			= 'Hi there, a friendly reminder, don\'t forget you have domains expiring soon.';
$lang['YOUR_LIST']			= 'Here is your list of domains which will be expiring within the next';
$lang['DAYS']				= 'days';
$lang['NO_DOMAINS_EXPIRING']= 'You have no domains currently expiring.';
$lang['CLIENT']				= 'Klient';
$lang['SEND']				= 'Send';
$lang['A_NEW_LIST']			= 'a new list of domains that will be expiring within the next';

// Clients

$lang['CLIENT_COMPANY']		= 'Firma klienta';

$lang['CLIENTS_DELETED']		= 'Usunięto klienta(ów)';
$lang['0_CLIENTS_IN_DB'] 	= 'Znaleziono 0 klientów w bazie danych';

// Clients-Add


$lang['ADD_EDIT_CLIENT']	= 'Dodaj/edytuj klienta';

$lang['CLIENT_SUCCESS_ADDED']	= 'Klient został pomyślnie dodany!';
$lang['CLIENT_SUCCESS_UPDATED']	= 'Klient został pomyślnie zaktualizowany!';
$lang['CLIENT_UPDATE_ERROR']	= 'Ups, wystąpił błąd podczas próby aktualizacji klienta.';
$lang['DOMAIN_SUCCESS_ADDED']	= 'Domena została pomyślnie dodana!';
$lang['DOMAIN_RENEWAL_DATE_FAIL']	= 'Nie udało się ustalić daty odnowienia domeny Whois.';
$lang['DOMAIN_UPDATE_ERROR']	= 'Ups, wystąpił błąd podczas próby utworzenia nowej domeny.';
$lang['DOMAIN_WHOIS_FAIL']		= 'Nie udało się ustalić daty odnowienia domeny Whois.';
$lang['DOMAIN_WHOIS_SUCCESS']	= 'Domena Whois powiodła się.';
$lang['DOMAIN_WHOIS_FAIL_REG']	= 'Domena Whois nie powiodła się lub domena nie została zarejestrowana.';
$lang['SELECTED_DOMAINS_DELETED']	= 'Wybrane domeny zostały usunięte.';

$lang['CONTACT_NAME']		= 'Nazwa Kontaktu';
$lang['JOB_TITLE']			= 'Nazwa stanowiska';
$lang['WEBSITE']			= 'Strona internetowa';
$lang['CONTACT_EMAIL']		= 'Email Kontaktowy';
$lang['PHONE_NUMBER']		= 'Numer telefonu';
$lang['COMPANY_ADDRESS']	= 'Adres Firmy';
$lang['SAVE']				= 'Zapisz';
$lang['ADD_DOMAIN']			= 'Dodaj domenę';
$lang['RENEWAL_URL']		= 'URL odnowienia';

// Backup

$lang['SYSTEM_DATABASE']	= 'System Baza Danych';
$lang['DB_BACKUP']			= 'DB kopia zapasowa';
$lang['BACKUP']				= 'Kopia zapasowa';
$lang['BACKUP_TEXT']		= 'Zostaniesz poproszony o pobranie pliku. Kopia zostanie również zapisana w folderze „kopie zapasowe”.';
$lang['DB_RESTORE']			= 'DB PRZYWRACANIE';
$lang['RESTORE']			= 'Przywróć';
$lang['RESTORE_TEXT']		= 'Uwaga: tej operacji nie można cofnąć, chyba że masz starą kopię zapasową.';
$lang['DB_UPDATE']			= 'DB AKTUALIZACJA';
$lang['UPDATE']				= 'Aktualizuj';
$lang['UPDATE_TEXT']		= 'W zależności od ilości domen w Twojej bazie danych aktualizacja może zająć do 5 minut. Rejestrator i odpowiedź whois — zostaną zaktualizowane dla WSZYSTKICH domen. Data rejestracji i data odnowienia zostaną zaktualizowane TYLKO W PRZYPADKU WAŻNOŚCI DATY zostaną zwrócone w Whois, w przeciwnym razie pozostaną bez zmian. Operacja jest nieodwracalna.';

// Help

$lang['CRON_SETUP']			= 'CRON Ustawienia';
$lang['CRON_NONTIFICATIONS']= 'CRON Powiadomienia';

$lang['CRON_TEXT_1']		= 'Jeśli twoje konto hostingowe jest w systemie LINUX/UNIX, możesz skonfigurować zadanie CRON, aby wskazywało na ten adres URL:<br><code>https://www.YourDomain.com/ScriptPath/cron.php?cron=do&d=31</code><br>("31" mieści się w ciągu dni na powiadomienie) <br> Powiadomienia te zostaną wysłane na adres e-mail administratora.';

$lang['CRON_TEXT_3']		= 'Dont have access to CRON on your systme, or do not know how to use? Try <a href="https://cron-job.org" target="_blank">Cron-Job.org</a>. FREE and Easy scheduled execution of website scripts.';

$lang['ADD_NEW_LANG']		= 'Add New Language';
$lang['NEW_LANG_TEXT_1']	= 'To add a new language, you will need to do the following';
$lang['NEW_LANG_STEP_1']	= 'Copy and rename the file "<code>./langs/lang.en.php"</code>, changing "<code>en</code>" to reflect your language.';
$lang['NEW_LANG_STEP_2']	= 'Update file "<code>./includes/lang-menu.php"</code>, to include your new language. (ex.: "<code>settings.php?lang=xx">XX</code>)"';
$lang['NEW_LANG_STEP_3']	= 'Update file "<code>./includes/languages.php"</code>, by un-commenting the section below "<code>/* Add new lang here... */</code> and adding your 2 digit language code.';

$lang['CREDITS']			= 'Zasługi';
$lang['RESOURCES_USED']		= 'Resources used in program';

// Settings

$lang['LANGUAGE']			= 'Język';
$lang['NEW_PASSWORD']		= 'Nowe Hasło';
$lang['CONFIRM']			= 'Potwierdź';
$lang['EMAIL_FOR_CRON']		= 'Email dla CRON';
$lang['SHOW_WHOIS']			= 'Pokaż odpowiedź Whois?';
$lang['SHOW_DOMAIN_DATA']	= 'Show Domain Data?';
$lang['SHOW_DOMAIN_DATA_UPDATED'] = 'Show Domain Data Updated.';
$lang['YES']				= 'Tak';
$lang['NO']					= 'Nie';

$lang['USERNAME_UPDATED']	= 'nazwa użytkownika zaktualizowana!';
$lang['EMAIL_UPDATED']		= 'Email zaktualizowany!';
$lang['PASSWORD_UPDATED']	= 'Hasło zaktualizowane!';
$lang['PASSWORD_NOT_MATCH']	= 'Passwords don\'t match!';

$lang['CRON_NOTI_LANG']		= 'CRON Notification Language (Language that emails will be sent.)';
$lang['CRON_LANG_UPDATED']	= 'Language Updated)';

$lang['CHOOSE_YOUR_LANG']	= 'Wybierz preferowany język';

$lang['VERSION'] 			= 'Wersja';
$lang['FOOTER_CREDITS'] 	= '<a href="https://www.wiredrabbitweb.com">Wired Rabbit Web</a>.</strong> Wszelkie prawa zastrzeżone.';

// Install file warnings
$lang['INSTALL_FILE_WARNING_1'] 			= 'Plik';
$lang['INSTALL_FILE_WARNING_2'] 			= 'jest obecny. Zaleca się jego usunięcie.';
$lang['INSTALL_FILE_DELETE_CONFIRM'] 			= 'Czy na pewno chcesz usunąć ten plik?';
$lang['CANCEL'] 			= 'Anuluj';

// Email / SMTP / Cron settings
$lang['MAIL_SETTINGS_SAVED'] 			= 'Ustawienia poczty zapisane.';
$lang['MAIL_METHOD'] 			= 'Metoda wysyłki';
$lang['MAIL_METHOD_DESC'] 			= 'PHP mail() używa serwera. SMTP używa zewnętrznego serwera pocztowego.';
$lang['FROM_DETAILS'] 			= 'Dane nadawcy';
$lang['FROM_NAME'] 			= 'Nazwa nadawcy';
$lang['FROM_EMAIL'] 			= 'Email nadawcy';
$lang['SMTP_SETTINGS'] 			= 'Ustawienia SMTP';
$lang['SMTP_SETTINGS_DESC'] 			= 'Wymagane tylko przy metodzie SMTP.';
$lang['SMTP_HOST'] 			= 'Host';
$lang['SMTP_PORT'] 			= 'Port';
$lang['SMTP_PORT_HINT'] 			= '587 = TLS &nbsp;|&nbsp; 465 = SSL &nbsp;|&nbsp; 25 = brak';
$lang['SMTP_ENCRYPTION'] 			= 'Szyfrowanie';
$lang['SMTP_USERNAME'] 			= 'Użytkownik';
$lang['SMTP_PASSWORD'] 			= 'Hasło';
$lang['SMTP_PASSWORD_PLACEHOLDER'] 			= 'Pozostaw puste, aby zachować bieżące';
$lang['SMTP_PASSWORD_HINT'] 			= 'Przechowywane zaszyfrowane. Pozostaw puste, aby zachować hasło.';
$lang['SAVE_MAIL_SETTINGS'] 			= 'Zapisz ustawienia poczty';
$lang['TEST_EMAIL'] 			= 'Wyślij testowy email';
$lang['TEST_EMAIL_DESC'] 			= 'Wysyła testowy email na adres administratora.';
$lang['CRON_TOKEN'] 			= 'Token Cron';
$lang['CRON_TOKEN_HINT'] 			= 'Używany do zabezpieczenia URL powiadomień cron. Kliknij, aby skopiować.';
$lang['REGENERATE'] 			= 'Regeneruj';
$lang['REGENERATE_TOKEN_CONFIRM'] 			= 'Regenerować token? Istniejące linki cron przestaną działać.';
$lang['CRON_TOKEN_REGENERATED'] = 'Token cron wygenerowany.';

?>