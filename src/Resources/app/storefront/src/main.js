import BoxalinoIntegrationPlugin from './boxalinointegration/boxalinointegration.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('BoxalinoIntegrationPlugin', BoxalinoIntegrationPlugin, '[data-boxalino-integration]');
