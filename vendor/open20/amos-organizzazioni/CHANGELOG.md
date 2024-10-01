# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Added the possibility for the user to request to join an organization or a headquarter. The request must be managed by the organization legal representative or by the organization operative referee. This configuration can be enabled in the plugin platform configuration by set to true this param: enableConfirmUsersJoinRequests.
- Added the posssibility to configure the plugin to use old style addresses in organizations and headquarters. By "old style" we mean text field for the address and select boxes for provinces and cities. To enable this feature the module property "oldStyleAddressEnabled" must be set to true in your platform.