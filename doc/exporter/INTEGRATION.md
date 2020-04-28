# EXPORTER INTEGRATION

The Boxalino Exporter service can be found:
    * https://github.com/boxalino/intelligence-framework-shopware/tree/master/src/Service/Exporter
    * https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Resources/config/services/exporter.xml

A command line request is also available:
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Exporter/ExporterCommand.php

The exporter is exporting to CSV (and then archives) the Shopware _components_ as configured in your plugin administration view:
1. products : with adjacent items (categories, description, price, visibility, properties, etc)
2. customers
3. transactions

##### Updating exporter segments
ANY EXPORTER ITEM CAN BE UPDATED IN YOUR INTEGRATION PLUGIN BY FOLLOWING GENERIC SYMFONY DOCUMENTATION
on how to decorate services
https://symfony.com/doc/current/service_container/service_decoration.html

##### Setting up scheduled tasks (cron jobs) for the export
There are 2 ways to do so:
1. By following the Shopware way:
    * https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services.xml#L28
    * https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services.xml#L32
    * https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/ScheduledTask
2. Via command line (which can be added to your server jobs)
``php bin/console boxalino-exporter:run full
``
