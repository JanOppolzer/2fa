# 2FA-Laravel

2FA (short for Two Factor Authentication) is [Laravel](https://laravel.com)-based web application to set up 2FA or MFA (Multi Factor Authentication) for Shibboleth IdP.

[![Actions Status](https://github.com/JanOppolzer/2fa-laravel/workflows/Laravel/badge.svg)](https://github.com/JanOppolzer/2fa-laravel/actions)

## Requirements

This application is written in Laravel 10 and uses PHP version 8.1.0 or newer.

Authentication is expected to be managed by locally running Shibboleth Service Provider, so Apache web server is highly recommended as there is an official Shibboleth module for it. There is also an unofficial Shibboleth SP module for nginx web server, however, it has not been tested and so it is not recommended.

- PHP 8.1.0+
- Shibboleth SP 3.x
- Apache 2.4
- Supervisor 4.1

The above mentioned requirements can be easily achieved by using Ubuntu 22.04 LTS (Jammy Jellyfish). For those running older Ubuntu or Debian, [Ondřej Surý's PPA repository](https://launchpad.net/~ondrej/+archive/ubuntu/php/) might be very appreciated.

## Installation

The easiest way to install 2FA is to use [Envoy](https://laravel.com/docs/10.x/envoy) script in [2fa-envoy](https://github.com/JanOppolzer/2fa-envoy) repository. The repository also contains configuration snippets for Apache, Shibboleth SP and Supervisor daemons.
