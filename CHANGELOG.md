# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased](https://github.com/figuren-theater/label-printing/compare/0.3.4...HEAD)

## [0.3.4](https://github.com/figuren-theater/label-printing/compare/0.3.3...0.3.4) - 2024-05-29

### 🚀 Added

- Update Changelog section with the relevant links ([#54](https://github.com/figuren-theater/label-printing/pull/54))
- Enhance GitHub actions to run not that often ([#53](https://github.com/figuren-theater/label-printing/pull/53))

## [0.3.3](https://github.com/figuren-theater/label-printing/compare/0.3.2...0.3.3) - 2024-05-29

### :bug: Fixes

- Do not exclude README & use a valid SPDX license ([#52](https://github.com/figuren-theater/label-printing/pull/52))
- Rename README to align with wp.org ([#51](https://github.com/figuren-theater/label-printing/pull/51))

## [0.3.2](https://github.com/figuren-theater/label-printing/compare/0.3.1...0.3.2) - 2024-05-29

### 🚀 Added

- Update version to 0.3.2 (where GH action does not) ([#50](https://github.com/figuren-theater/label-printing/pull/50))

## [0.3.1](https://github.com/figuren-theater/label-printing/compare/0.3.0...0.3.1) - 2024-05-29

### 🚀 Added

- Use wp dist-archive to create a release-asset zip ([#49](https://github.com/figuren-theater/label-printing/pull/49))

## [0.3.0](https://github.com/figuren-theater/label-printing/compare/0.2.0...0.3.0) - 2024-05-29

### 🚀 Added

- New 'Deploy' action to WordPress.org Repository ([#48](https://github.com/figuren-theater/label-printing/pull/48))
- NEW .org/plugin-review action ([#47](https://github.com/figuren-theater/label-printing/pull/47))
- Develop ([#41](https://github.com/figuren-theater/label-printing/pull/41))
  - Disable WordPress' root padding [cff7e92](https://github.com/figuren-theater/label-printing/pull/41/commits/cff7e92a8a0d683debd1af92c166812f66aa10c8)
  - Add 10% inner saftey distance to all labels [779f2bf](https://github.com/figuren-theater/label-printing/pull/41/commits/779f2bf4b5c4c065c213995ee0184bbe539c4c06)
  - Make template strings translateable [1ddbc50](https://github.com/figuren-theater/label-printing/pull/41/commits/1ddbc50b67e3e5eb92f7da296a0ae39c6180f453)
  - Add body-class when block is in use [293ca9a](https://github.com/figuren-theater/label-printing/pull/41/commits/293ca9a467f95e0947cde1b33471e9f17164c192)
  - Recognise paper orientation in browser print dialog [4942323](https://github.com/figuren-theater/label-printing/pull/41/commits/49423234f3fb377ca9880b96044b8c73cebfab85)
  - NEW FAQ 'How does the block work?' [a607b2d](https://github.com/figuren-theater/label-printing/pull/41/commits/a607b2d0690d6cbb807c00b1609702d0f3f48314)
  - Try to better align with plugin-readme guidelines [45a21b7](https://github.com/figuren-theater/label-printing/pull/41/commits/45a21b741abf87f59159df6f2fa025f904442379)
  - Increase minimum required PHP to 8.1 )
  

### 🐛 Fixed

- Develop ([#41](https://github.com/figuren-theater/label-printing/pull/41))

## [0.2.0](https://github.com/figuren-theater/label-printing/compare/0.1.0...0.2.0) - 2023-10-13

### 🚀 Added

- Write a nice README ([#29](https://github.com/figuren-theater/label-printing/pull/29))
- Add DE screenshots ([#28](https://github.com/figuren-theater/label-printing/pull/28))
- Make translation ready and add de_DE & de_DE_formal translations ([#24](https://github.com/figuren-theater/label-printing/pull/24))
- Allow to set custom Font sizes on Group-, Heading- and Paragraph-Blocks ([#23](https://github.com/figuren-theater/label-printing/pull/23))
- Switch editor layout to show label-selector first, and label template… ([#22](https://github.com/figuren-theater/label-printing/pull/22))
- Introduce block (and everything related) to print labels ([#16](https://github.com/figuren-theater/label-printing/pull/16))

### 🐛 Fixed

- Small fixes for more consistency between screen and print ([#21](https://github.com/figuren-theater/label-printing/pull/21))

### Dependency Updates & Maintenance

- Locked @wordpress/scripts to version 26.13.0 (see #25)
