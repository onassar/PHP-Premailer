#!/usr/bin/ruby
require 'rubygems'
begin
  gem 'premailer'
  require 'premailer'
  require 'getopt/long'

  opt = Getopt::Long.getopts(
    ['--markup', Getopt::REQUIRED],
    ['--css_to_attributes', Getopt::BOOLEAN],
    ['--include_link_tags', Getopt::BOOLEAN],
    ['--include_style_tags', Getopt::BOOLEAN],
    ['--input_encoding', Getopt::OPTIONAL],
    ['--preserve_reset', Getopt::BOOLEAN],
    ['--preserve_styles', Getopt::BOOLEAN],
    ['--remove_classes', Getopt::BOOLEAN],
    ['--remove_comments', Getopt::BOOLEAN],
    ['--remove_ids', Getopt::BOOLEAN],
    ['--remove_scripts', Getopt::BOOLEAN],
    ['--replace_html_entities', Getopt::BOOLEAN],
    ['--with_html_string', Getopt::BOOLEAN]
  )
  premailer = Premailer.new(
      opt['markup'].dup,
      :css_to_attributes => opt['css_to_attributes'],
      :include_link_tags => opt['include_link_tags'],
      :include_style_tags => opt['include_style_tags'],
      :input_encoding => opt['input_encoding'],
      :preserve_reset => opt['preserve_reset'],
      :preserve_styles => opt['preserve_styles'],
      :remove_classes => opt['remove_classes'],
      :remove_comments => opt['remove_comments'],
      :remove_ids => opt['remove_ids'],
      :remove_scripts => opt['remove_scripts'],
      :replace_html_entities => opt['replace_html_entities'],
      :with_html_string => opt['with_html_string']
  )
  puts premailer.to_inline_css
rescue Gem::LoadError
  raise 'Premailer not loaded'
end
