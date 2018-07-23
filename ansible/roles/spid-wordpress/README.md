spid-wordpress
==============

Test WordPress site with authorization using an Italian System for Digital Identity (SPID) Service Provider (SP) as SSO.

Requirements
------------

Only tested with Debian 9.4 (stretch) amd64 hosts.

Role Variables
--------------

There are no variables ATM.

Dependencies
------------

There are no role dependencies ATM.

Example Playbook
----------------

How to use the role:

    - hosts: wp
      remote_user: root
      roles:
        - spid-wordpress

License
-------

GNU AFFERO GENERAL PUBLIC LICENSE Version 3

Author Information
------------------

Copyright (C) Paolo Greppi simevo s.r.l. 2018
