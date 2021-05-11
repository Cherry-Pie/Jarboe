# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added

## [1.7.0] - 2021-05-11
### Added
- `quality` method in `Image` fields.

## [1.6.2] - 2021-05-11
### Fixed
- `isTransparentColor` defaults to `false` when no color passed in `Image` fields.

## [1.6.1] - 2020-10-25
### Fixed
- Prevent XSS vulnerability for `Markdown` and `Wysiwyg` fields.

## [1.6.0] - 2020-10-19
### Added
- Ability to set height for `Markdown` field input via `rows` method.
- Support of Laravel 8.

### Fixed
- Extra label bound to an input element for `Markdown` field.
- Review defined routes to allow `php artisan route:cache`.

## [1.5.0] - 2020-09-14
### Added
- `delimiters` method for `Tags` field.

## [1.4.1] - 2020-09-14
### Fixed
- `Markdown` field edit view.

## [1.4.0] - 2020-09-06
### Added
- Translatable support for `Markdown` field.

## [1.3.1] - 2020-08-19
### Fixed
- Use helper class instead of helper function for collecting errors in Repeater field.

## [1.3.0] - 2020-08-17
### Added
- Revisions functionality.
- Optional `getHistoryView` method for field classes, which is responsible for rendering view for version's diff value.
- Added two new permissions: `history` and `revert`.
- New migration file `**_create_versions_table.php`.
- New config file `jarboe/versionable.php` for specifying default versions model.
- Breadcrumbs classes updated to handle new "versions timeline" page.
- New translation keys in `resources/lang/en/common.php`.
- Support listing/editing exact value from array column via `Text` field. (not supported for fields within `Repeater` field, with `translatable()` enabled, for multi-level arrays)

## [1.2.0] - 2020-07-15
### Added
- New js helpers in `jarboe` object: `bigToastSuccess`, `bigToastDanger`, `bigToastWarning`, `bigToastInfo`.
- Inline editing for `Checkbox` field.
- Translation keys `jarboe::fields.checkbox.yes` and `jarboe::fields.checkbox.no` that are used in `Checkbox` field inline mode.
- `FieldsetMarkup` field for dividing form into blocks with its own `<legend>`.
- Jquery UI datepicker switches locale depending on selected locale from `list` of `jarboe/locales.php` configuration file.
- "OTP secret" field in admins table controller when `jarboe.admin_panel.two_factor_auth.enabled` config option is `true`.

### Changed
- Filter classes method `render` returns `Illuminate\View\View` instance instead of rendered html string.
- Using `put` instead of `flash` for notification messages to preserve them across several redirects.

### Fixed
- Immutable `col` value for `Hidden` field.
- `CheckboxFilter` handle `0` value properly.
- Infinite redirection if page with exception is the same "previous" page in session.

## [1.1.0] - 2020-06-16
### Added
- Added grouping by `<optgroup>` tag for `options()` method in `Select` field.
- `admin_auth()` helper.
- Relation search via ajax for `Tags` field.
- Allow to delete image if it's selected but not uploaded for `Image` field.

### Fixed
- Fixed css for pagination block for `light` theme.
- Proper check for `Image` field validation errors.
- Added missed translations for login/register form inputs.

## [1.0.0] - 2020-06-07
### Added
- Everything, initial release.


[Unreleased]: https://github.com/Cherry-Pie/Jarboe/compare/1.7.0...master
[1.7.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.6.2...1.7.0
[1.6.2]: https://github.com/Cherry-Pie/Jarboe/compare/1.6.1...1.6.2
[1.6.1]: https://github.com/Cherry-Pie/Jarboe/compare/1.6.0...1.6.1
[1.6.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.4.1...1.5.0
[1.4.1]: https://github.com/Cherry-Pie/Jarboe/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.3.1...1.4.0
[1.3.1]: https://github.com/Cherry-Pie/Jarboe/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/Cherry-Pie/Jarboe
