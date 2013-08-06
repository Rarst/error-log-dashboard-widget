=== Error Log Dashboard Widget ===
Contributors: Rarst
Tags: dashboard,widget,admin,error,log
Requires at least: 3.2.1
Tested up to: 3.6
Stable tag: trunk

Error Log Dashboard Widget is robust zero-configuration and low-memory way to keep an eye on error log.

== Description ==

Logging errors is recommended best practice, even for production site. Checking those logs however might seem like a chore.

The widget brings latest entries from error log right to WordPress dashboard:

 - log file is detected automatically from configuration;
 - only end of file is read - no memory overflow issues, safe for large logs.

[Development repository and issue tracker](https://bitbucket.org/Rarst/error-log-dashboard-widget/).

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

= 1.0.3 =
* _(bugfix)_ fixed strict error notice for WordPress 3.6

= 1.0.2 =
* _(bugfix)_ fixed errors on invalid and empty log files

= 1.0.1 =
* _(bugfix)_ fixed memory leak in last_lines() function

= 1.0 =
* Initial release.