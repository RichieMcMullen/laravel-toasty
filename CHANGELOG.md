# Changelog

All notable changes to `atomcoder/laravel-toasty` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed

- Renamed the public package surface to collision-safe defaults, including the Blade directive, Blade component namespace, PHP helper, Livewire trait methods, JavaScript globals, and browser event names.
- Moved package configuration to `config/laravel_toasty.php` and added compatibility handling for older published config values during upgrades.
- Replaced Tailwind utility dependence in the default toast view with embedded package-scoped CSS so the package works without Tailwind source scanning.
- Updated installation and usage documentation to reflect the new APIs, config path, migration guidance, and compatibility behavior.

### Added

- Added a `LaravelToasty` facade alias for a namespaced Laravel integration point.
- Added optional `legacy_aliases` support for temporary frontend migration compatibility with older Blade and JavaScript aliases.
- Added this changelog to track package changes going forward.

## [1.0.0] - 2026-04-13

### Added

- Initial package release with Laravel Blade, Livewire, PHP helper, facade, and JavaScript toast APIs.
- Bundled `pines`, `toasty`, and `glass` visual presets.
- Session-backed toast flashing and browser-dispatched toast support.
