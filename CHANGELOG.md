# Changelog

All notable changes to this project are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project uses
[semantic versioning](https://semver.org/).

## [Unreleased]

### Added
- MIT license, `LICENSE`, `composer.json` and the `config.xml` / `config_pl.xml` manifests.
- English and Polish translation catalogues (`Modules.M4ploginaccess.Admin` and `.Shop`).
- `index.php` guards in every directory.
- Default settings on install and cleanup of both settings on uninstall.

### Changed
- Back-office and front-office strings now go through `$this->trans()` instead of `$this->l()`.
- Declared PrestaShop compatibility (1.7.6 and newer).

### Removed
- Unused private method left over from an earlier version.

## [1.0.0] — 2025-07-25

### Added
- First release: hidden prices and buy button for guests, optional shop-wide login.
