# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added

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


[Unreleased]: https://github.com/Cherry-Pie/Jarboe/compare/1.1.0...master
[1.2.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/Cherry-Pie/Jarboe/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/Cherry-Pie/Jarboe
