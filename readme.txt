=== Error Log Dashboard Widget ===
Contributors: Rarst
Tags: dashboard,widget,admin,error,log
Requires at least: 3.2.1
Tested up to: 3.4.2
Stable tag: trunk

Error Log Dashboard Widget is robust zero-configuration and low-memory way to keep an eye on error log.

== Description ==

Logging errors is recommended best practice, even for production site. Checking those logs however might seem like a chore.

The widget brings latest entries from error log right to WordPress dashboard:

 - log file is detected automatically from configuration;
 - only end of file is read - no memory overflow issues, safe for large logs.

== Frequently Asked Questions ==

= Which log is monitored? =

Log file path is read from `error_log` PHP setting, which can be configured by WordPress or otherwise. Additional logs can be monitored by filtering list on `error_log_widget_logs` hook.

= Why so many/few lines? =

Filter `error_log_widget_lines` hook to control how many lines you want to see.

= Will everyone see the widget? =

Only users with `manage_options` capability (Administrators) will see the widget. You can change this by filtering `error_log_widget_capability` hook.

== Screenshots ==

1. Widget in dashboard.

== Changelog ==

= 1.0.1 =
* _(bugfix)_ fixed memory leak in last_lines() function

= 1.0 =
* Initial release.