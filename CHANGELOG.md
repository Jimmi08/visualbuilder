# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]

- overriding used elements by theme

## [1.1.0] - 2019-05-07
- added: image change / upload functionality
- fixed: go back path
- fixed: correct name for image media input field, it has to be array, otherwise e107 upload doesn't work
- fixed: removed null from text field in sql file
- removed: option to media manager
- added: possibility to override new skin by custom plugin

## [1.0.0] - 2019-05-06

### Added
- support for Qoob libraries (e107 libraries are something else) - see qoob_load_libraries_data
- load page functionality - see qoob_load_page_data
- display page content from html saved file on frontend
- save page functionality to db and html file - qoob_save_page_data
- support for extending by other plugins and active theme
- skin support
- template support - see qoob_save_page_template + qoob_load_page_templates
- frontend preview support - see frontendPageUrl
- using anywhere by using shortcode {VISUALBUILDER: id=X}


