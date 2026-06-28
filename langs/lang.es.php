<?php
/* 
------------------
Language: Español
------------------
*/

$lang = array();

// Global

$lang['LANG_CODE'] 			= 'es'; //en,es,de,pl, etc.
$lang['LANG_LONG'] 			= 'Español';

$lang['SITE_TITLE'] 		= 'Domain Reminder';

$lang['ADMIN'] 				= 'Administrador';
$lang['SETTINGS'] 			= 'Ajustes';
$lang['SIGNOUT'] 			= 'Desconectar';

$lang['DASHBOARD'] 			= 'Cuadro de Mando ';
$lang['DOMAINS'] 			= 'Dominios';
$lang['EXPIRING_SOON'] 		= 'Caducando Pronto';
$lang['CLIENTS'] 			= 'Clientes';
$lang['ADD_CLIENT'] 		= 'Agregar Cliente';
$lang['DATABASES'] 			= 'Bases de Datos';
$lang['HELP'] 				= 'Ayuda';

$lang['JAN'] 				= 'enero';
$lang['FEB'] 				= 'febrero';
$lang['MAR'] 				= 'marzo';
$lang['APR'] 				= 'abril';
$lang['MAY'] 				= 'mayo';
$lang['JUN'] 				= 'junio';
$lang['JUL'] 				= 'julio';
$lang['AUG'] 				= 'agosto';
$lang['SEP'] 				= 'septiembre';
$lang['OCT'] 				= 'octubre';
$lang['NOV'] 				= 'noviembre';
$lang['DEC'] 				= 'diciembre';

$lang['SUN'] 				= 'domingo';
$lang['MON'] 				= 'lunes';
$lang['TUE'] 				= 'martes';
$lang['WED'] 				= 'miércoles';
$lang['THU'] 				= 'jueves';
$lang['FRI'] 				= 'viernes';
$lang['SAT'] 				= 'sábado';

// index.php (Login)

$lang['LOGIN'] 				= 'Login';
$lang['SIGNIN_TO_START'] 	= 'Iniciar Sesión';
$lang['USERNAME'] 			= 'Usuario';
$lang['PASSWORD'] 			= 'Contraseña';
$lang['REMEMBER_ME'] 		= 'Recuérdame';
$lang['SIGNIN'] 			= 'Iniciar';

$lang['ALERT'] 				= '¡Aviso!';
$lang['WRONG_LOGIN'] 		= 'Nombre de usuario y/o contraseña incorrectos.';

// Dashboard

$lang['EXPIRING_X_DAYS'] 	= 'Dominios con Vencimiento Dentro de los 45 Días';
$lang['DOMAIN_NAME'] 		= 'Nombre del Dominio';
$lang['EXPERATION_DATE'] 	= 'Fecha de Caducidad';
$lang['VIEW_MORE'] 			= 'Ver más';
$lang['DELETE'] 			= 'Eliminar';
$lang['DELETE_DOMAIN_CONFIRM'] 	= '¿Estás seguro de que quieres eliminar: ';
$lang['0_DOMAINS_EXPIRING'] = '0 dominios que caducan en los próximos 45 días.';

// Domains

$lang['SEARCH'] 			= 'Buscar';
$lang['CLIENT_NAME'] 		= 'Nombre del Cliente';
$lang['REGISTRATION_DATE'] 	= 'Fecha de Registro';
$lang['RENEWAL_DATE'] 		= 'Fecha de Renovación';
$lang['REGISTRAR'] 			= 'Registrador ';
$lang['EDIT']				= 'Editar';
$lang['RENEW'] 				= 'Renovar';
$lang['DELETE_MULTI_CONFIRM'] 	= '¿Estás seguro de que quieres eliminar estos?';
$lang['DOMAINS_DELETED'] 	= 'Dominio(s) eliminado(s).';
$lang['DATA_DELETED'] 		= 'Datos cifrados asociados eliminados.';
$lang['0_DOMAINS_IN_DB'] 	= '0 dominios encontrados en la base de datos';

// Domains-Edit

$lang['EDIT_DOMAIN'] 		= 'Editar dominio';
$lang['SELECTED_DATA_DELETED'] 	= 'Los datos seleccionados fueron eliminados.';
$lang['DATA_ADDED'] 			= '¡Datos encriptados y agregados!';
$lang['DOMAIN_SUCCESS_UPDATED'] = '¡El dominio se actualizó con éxito!';
$lang['DOMAIN_WHOIS_FAIL_REG_DATE'] = 'Dominio Whois falló para la fecha de registro.';
$lang['DELETE_SELECTED'] 	= 'Eliminar seleccionado';
$lang['PLEASE_SELECT'] 		= 'Seleccione';
$lang['OTHER'] 				= 'Otro';
$lang['GET_AUTO_WHOIS'] 	= '¿Obtener Auto-Whois fecha de vencimiento, fecha de registro, nombre del registrador?';
$lang['WHOIS_REPLY'] 		= 'Respuesta de WHOIS';
$lang['DOMAIN_DATA'] 		= 'Datos de dominio (FTP, MySQL, Webmail, etc.)';
$lang['TYPE'] 				= 'Tipo';
$lang['COMMENT'] 			= 'Comentario';
$lang['ADD_DATA'] 			= 'Agregar datos';
$lang['DATA_TYPE'] 			= 'Tipo de datos';
$lang['FTP_LOGIN'] 			= 'Login de FTP';
$lang['DATABASE_INFO'] 		= 'Info de la BBDD ';
$lang['WEBMAIL'] 			= 'Webmail';
$lang['CONTROL_PANEL'] 		= 'Panel de Control';
$lang['HOST_NAME'] 			= 'Nombre de Host';
$lang['DATABASE_NAME'] 		= 'Nombre de la BBDD';
$lang['PLEASE_NOTE'] 		= 'Tenga en cuenta';
$lang['FIELDS_ENCRYPTED_IN_DB'] = 'Los campos anteriores se cifrarán en la base de datos.';
$lang['0_RECORDS_IN_DB'] 	= '0 registros encontrados en la BBDD';

// Domains-Expiring 

$lang['NOTE'] 				= 'Nota';
$lang['RED'] 				= 'Rojo';
$lang['RED_TEXT'] 			= 'Caducidad en menos de 30 días.';
$lang['YELLOW'] 			= 'Amarillo';
$lang['YELLOW_TEXT'] 		= 'Caducidad de 30 a 60 días.';
$lang['GREEN'] 				= 'Verde';
$lang['GREEN_TEXT'] 		= 'Caducidad de 60 a 90 días.';
$lang['RENEW_TEXT'] 		= 'El botón Renovar aparecerá solo junto a los dominios con un enlace de renovación existente. Puede agregar un enlace de renovación yendo a la página de Editar Dominio.';
$lang['SEND_NOTICE'] 		= 'Enviar aviso de 45 días...';

// Email (cron.php)

$lang['DOMAIN_NOTICE']		= 'Aviso de Caducidad de Dominios.';
$lang['YOUR_REMINDER']		= 'Su recordatorio de que tiene algunos dominios que caducan pronto.';
$lang['HI_THERE']			= 'Hola, un recordatorio amistoso, no olvide que tiene dominios que caducan pronto.';
$lang['YOUR_LIST']			= 'Aquí está su lista de dominios que vencerán en los próximos';
$lang['DAYS']				= 'días';
$lang['NO_DOMAINS_EXPIRING']= 'No tiene ningún dominio que esté a punto de caducar.';
$lang['CLIENT']				= 'Cliente';
$lang['SEND']				= 'Enviar';
$lang['A_NEW_LIST']			= 'una nueva lista de dominios que vencerán en los próximos ';

// Clients

$lang['CLIENT_COMPANY']		= 'Empresa de Cliente';

$lang['CLIENTS_DELETED']	= 'Cliente(s) eliminado(s).';
$lang['0_CLIENTS_IN_DB'] 	= '0 clientes encontrados en la base de datos';

// Clients-Add


$lang['ADD_EDIT_CLIENT']	= 'Añadir/Editar Cliente';

$lang['CLIENT_SUCCESS_ADDED']	= '¡El cliente fue agregado con éxito!';
$lang['CLIENT_SUCCESS_UPDATED']	= 'El cliente fue actualizado con éxito!';
$lang['CLIENT_UPDATE_ERROR']	= 'Ups, se produjo un error al intentar actualizar el cliente.';
$lang['DOMAIN_SUCCESS_ADDED']	= '¡El dominio se agregó con éxito!';
$lang['DOMAIN_RENEWAL_DATE_FAIL']	= 'Dominio Whois falló para la fecha de renovación.';
$lang['DOMAIN_UPDATE_ERROR']	= 'Ups, ocurrió un error al intentar crear un nuevo dominio.';
$lang['DOMAIN_WHOIS_FAIL']		= 'Dominio Whois falló para la fecha de renovación.';
$lang['DOMAIN_WHOIS_SUCCESS']	= 'Dominio Whois exitoso.';
$lang['DOMAIN_WHOIS_FAIL_REG']	= 'Dominio Whois falló o dominio no registrado.';
$lang['SELECTED_DOMAINS_DELETED']	= 'Se eliminaron los dominios seleccionados.';

$lang['CONTACT_NAME']		= 'Nombre de contacto';
$lang['JOB_TITLE']			= 'Título profesional';
$lang['WEBSITE']			= 'Sitio web';
$lang['CONTACT_EMAIL']		= 'Email de contacto';
$lang['PHONE_NUMBER']		= 'Número de teléfono';
$lang['COMPANY_ADDRESS']	= 'Dirección de la empresa';
$lang['SAVE']				= 'Guardar';
$lang['ADD_DOMAIN']			= 'Agregar dominio';
$lang['RENEWAL_URL']		= 'URL de renovación';

// Backup

$lang['SYSTEM_DATABASE']	= 'Base de datos del Sistema';
$lang['DB_BACKUP']			= 'Respaldo de la BBDD';
$lang['BACKUP']				= 'Guardar';
$lang['BACKUP_TEXT']		= 'Se le pedirá que descargue el archivo. También se guardará una copia en la carpeta de "backups".';
$lang['DB_RESTORE']			= 'Restaurar BBDD';
$lang['RESTORE']			= 'Restaurar';
$lang['RESTORE_TEXT']		= 'Tenga en cuenta: esta operación es irreversible a menos que tenga una copia de seguridad anterior.';
$lang['DB_UPDATE']			= 'Actualizar BBDD';
$lang['UPDATE']				= 'Actualizar';
$lang['UPDATE_TEXT']		= 'Dependiendo de la cantidad de dominios en su base de datos, la actualización puede demorar hasta 5 minutos. El registrador y la respuesta de whois se actualizarán para TODOS los dominios. La fecha de registro y la fecha de renovación se actualizarán SOLO SI FECHAS VÁLIDAS se devolverán en Whois, de lo contrario, permanecerán igual. La operación es irreversible.';

// Help

$lang['CRON_SETUP']			= 'Configuración CRON';
$lang['CRON_NONTIFICATIONS']= 'Notificaciones CRON';

$lang['CRON_TEXT_1']		= 'Si su cuenta de alojamiento está en el sistema LINUX/UNIX, puede configurar un trabajo CRON para apuntar a esta URL:<br><code>https://www.YourDomain.com/ScriptPath/cron.php?cron=do&d=31</code><br>("31" es el número de días para notificar) <br>Estas notificaciones se enviarán al correo del administrador.';

$lang['CRON_TEXT_3']		= '¿No tiene acceso a CRON en su sistema o no sabe cómo usarlo? Probar <a href="https://cron-job.org" target="_blank">Cron-Job.org</a>. Ejecución programada GRATUITA y sencilla de scripts de sitios web.';

$lang['ADD_NEW_LANG']		= 'Agregar nuevo idioma';
$lang['NEW_LANG_TEXT_1']	= 'Para agregar un nuevo idioma, deberá hacer lo siguiente';
$lang['NEW_LANG_STEP_1']	= 'Copie y cambie el nombre del archivo "<code>./langs/lang.en.php</code>", cambiando "<code>en</code>" para reflejar su idioma.';
$lang['NEW_LANG_STEP_2']	= 'Actualizar archivo "<code>./includes/lang-menu.php</code>", para incluir su nuevo idioma. (ej.: "<code>settings.php?lang=xx">XX</code>")';
$lang['NEW_LANG_STEP_3']	= 'Actualizar archivo "<code>./includes/languages.php</code>", descomentando la sección a continuación "<code>/* Add new lang here... */</code>" y agregando su código de idioma de 2 dígitos.';

$lang['CREDITS']			= 'Créditos';
$lang['RESOURCES_USED']		= 'Resources used in program';

// Settings

$lang['LANGUAGE']			= 'Idioma';
$lang['NEW_PASSWORD']		= 'Nueva Contraseña';
$lang['CONFIRM']			= 'Confirmar';
$lang['EMAIL_FOR_CRON']		= 'Correo para CRON';
$lang['SHOW_WHOIS']			= '¿Mostrar respuesta de Whois?';
$lang['SHOW_DOMAIN_DATA']	= '¿Mostrar Datos de Dominio?';
$lang['SHOW_DOMAIN_DATA_UPDATED'] = 'Mostrar Datos de Dominio Actualizados.';
$lang['YES']				= 'Si';
$lang['NO']					= 'No';

$lang['USERNAME_UPDATED']	= '¡Nombre de usuario actualizado!';
$lang['EMAIL_UPDATED']		= '¡Correo actualizado!';
$lang['PASSWORD_UPDATED']	= '¡Contraseña actualiza!';
$lang['PASSWORD_NOT_MATCH']	= '¡Las contraseñas no coinciden!';

$lang['CRON_NOTI_LANG']		= 'Idioma de notificación CRON (Idioma en el que se enviarán los correos).';
$lang['CRON_LANG_UPDATED']	= 'Idioma actualizada';

$lang['CHOOSE_YOUR_LANG']	= 'Elige tu idioma preferido';

$lang['VERSION'] 			= 'Versión';
$lang['FOOTER_CREDITS'] 	= '<a href="https://www.wiredrabbitweb.com">Wired Rabbit Web</a>.</strong> All rights reserved.';

// Install file warnings
$lang['INSTALL_FILE_WARNING_1'] 			= 'El archivo';
$lang['INSTALL_FILE_WARNING_2'] 			= 'está presente. Se recomienda eliminarlo.';
$lang['INSTALL_FILE_DELETE_CONFIRM'] 			= '¿Está seguro de que desea eliminar este archivo?';
$lang['CANCEL'] 			= 'Cancelar';

// Email / SMTP / Cron settings
$lang['MAIL_SETTINGS_SAVED'] 			= 'Configuración de correo guardada.';
$lang['MAIL_METHOD'] 			= 'Método de correo';
$lang['MAIL_METHOD_DESC'] 			= 'PHP mail() usa su servidor. SMTP usa un servidor de correo externo.';
$lang['FROM_DETAILS'] 			= 'Datos del remitente';
$lang['FROM_NAME'] 			= 'Nombre del remitente';
$lang['FROM_EMAIL'] 			= 'Correo del remitente';
$lang['SMTP_SETTINGS'] 			= 'Configuración SMTP';
$lang['SMTP_SETTINGS_DESC'] 			= 'Solo necesario al usar el método SMTP.';
$lang['SMTP_HOST'] 			= 'Host';
$lang['SMTP_PORT'] 			= 'Puerto';
$lang['SMTP_PORT_HINT'] 			= '587 = TLS &nbsp;|&nbsp; 465 = SSL &nbsp;|&nbsp; 25 = ninguno';
$lang['SMTP_ENCRYPTION'] 			= 'Cifrado';
$lang['SMTP_USERNAME'] 			= 'Usuario';
$lang['SMTP_PASSWORD'] 			= 'Contraseña';
$lang['SMTP_PASSWORD_PLACEHOLDER'] 			= 'Dejar en blanco para mantener la actual';
$lang['SMTP_PASSWORD_HINT'] 			= 'Almacenada cifrada. Dejar en blanco para mantener la contraseña actual.';
$lang['SAVE_MAIL_SETTINGS'] 			= 'Guardar configuración de correo';
$lang['TEST_EMAIL'] 			= 'Probar correo';
$lang['TEST_EMAIL_DESC'] 			= 'Envía un correo de prueba a la dirección del Administrador.';
$lang['CRON_TOKEN'] 			= 'Token Cron';
$lang['CRON_TOKEN_HINT'] 			= 'Usado para asegurar la URL de notificación cron. Clic para seleccionar y copiar.';
$lang['REGENERATE'] 			= 'Regenerar';
$lang['REGENERATE_TOKEN_CONFIRM'] 			= '¿Regenerar token? Los enlaces cron existentes dejarán de funcionar.';
$lang['CRON_TOKEN_REGENERATED'] = 'Token cron regenerado.';

?>