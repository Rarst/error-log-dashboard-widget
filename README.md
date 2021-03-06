# Error Log Dashboard Widget
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rarst/error-log-dashboard-widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rarst/error-log-dashboard-widget/?branch=master)

Error Log Dashboard Widget is robust zero–configuration and low–memory WordPress plugin to keep an eye on error log.

[Logging errors](https://codex.wordpress.org/Editing_wp-config.php#Configure_Error_Logging) is recommended best practice, even for production site. Checking those logs however might seem like a chore.

The widget brings latest entries from error log right to WordPress dashboard:

 - log file is detected automatically from configuration;
 - only end of file is read — no memory overflow issues, safe for large logs.

## Installation

[Download](https://github.com/Rarst/error-log-dashboard-widget/archive/master.zip) and unpack into plugins directory or require with Composer:

```bash
composer require rarst/error-log-dashboard-widget
```

## Frequently Asked Questions

### Which log is monitored?

Log file path is read from `error_log` PHP setting, which can be configured by WordPress or otherwise. Additional logs can be monitored by filtering list on `error_log_widget_logs` hook.

### Why so many/few lines?

Filter `error_log_widget_lines` hook to control how many lines you want to see.

### Will everyone see the widget?

Only users with `manage_options` capability (Administrators) will see the widget. You can change this by filtering `error_log_widget_capability` hook.

## License

GPLv2+