# Changelog
## [8.0.2]
### Added
- db_schema_whitelist.json has been added
### Fixed
- Fixed configuration not being loaded by default due to incompatibilities with Adobe Commerce. Cron Groups config by default is overriding the 'cron' path for system config.
- Fixed the module loading the full cron collection to grab the last. This has been changed to a query that returns 1 result.
- We added an index on the 'created_at' field of cron_schedule to speed up loading the recent cron job message.

## [8.0.1]
### Added
- Possibility to disable 'Cron Not Running' admin notification. Disabling it increases general performance of the backend.

## [8.0.0]
### Added
- Support PHP 8.x
- Drop Support Magento versions < 2.4.4

## [6.0.0]
### Added
- Made it possible to limit the number of successful jobs on the timeline
- Magento 2.4 compatibility
- Dropped support magento versions < 2.3.x

### Fixes
- Empty timeline when there are no jobs in the system yet

## [5.0.0] - 2020-07-31
### Added

- Notify the admin when the Magento cron is not running
- Jobs can be removed in the Schedule List view
- Jobs are sorted alphabetically
- Made it possible to disable cron jobs
- Number of jobs  in the Timeline View can be limited to prevent out of memory errors
- Timeline view of the cron schedule in the admin panel
- Configuration view of the jobs in the admin panel
- Visualisation of the cron schedule list in the admin panel
- Made it possible to add jobs to the schedule
