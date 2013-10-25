A new Sample has been added to Symfony2 / AdvancedBundles
=========================================================
The UserBundle there has the ability to store both roles and access controls in the database and alter the symfony
security options by adding these roles and access controls to the security layer before the framework is fully loaded.
These roles and access controls override those set in security.yml

Please do not use this bundle as is since it requires the other Bundles which are part of a full CMS framework skeleton for Symfony2.
When all components are completed, I may release the full source to all settings and bundles.
For now, however, these can serve as sample codes for viewing.

If you want to know how to do dynamic roles and access control, look into:
CoreSysUsersEvents.php

Events\*

DependencyInjection\CoreSysUserExtension

DependencyInjection\Compiler\*

Models\*

Events\*

Entity\*

EventListener\*

The bundle is still under development. Currently in the process of adding in hooks to add blocks of twig into specified templates.

