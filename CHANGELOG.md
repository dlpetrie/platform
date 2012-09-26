1.0.3 Changelog
----------

**General:**

  * Moved the Platform::is_installed() from application/start.php to application/platform/platform.php
  * Cleaned a bit the application/platform/platform.php

**Extensions Manager:**

  * Refactored the extensions manager

**Installer:**

  * Added a new dependencies manager for the installer

1.0.2 Changelog
----------

**Installer:**

  * Fixed a few bugs with the installer that were causing errors.

**Users:**

  * Fixed a bug causing user permissions to not be updated from a validation error being thrown incorrectly.
  * Fixed a bug causing the suspension feature on login that prevented it from working.

1.0.1 Changelog
----------

**General:**

  * README Updates
  * Adding in CHANGELOG.MD

**Installer:**

  * Fixed a language file inconsistancy

**Menus:**

  * Adding nullable to the uri column in menus to fix an issue some people are having with installation.

**Users:**

  * Fixing API setting call issues in the users extension preventing emails from being sent

**Settings:**

  * Adding HTML::entities processing to settings to fix a minor security issue.