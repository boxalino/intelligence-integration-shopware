# AUTOCOMPLETE INTEGRATION

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget <b>autocomplete</b>

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## Steps
 ###### 1. Declare a service for the autocomplete definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/autocomplete.xml 
(widget name, context, etc)

* the autocomplete request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/AutocompleteRequestDefinitionInterface.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the autocomplete request context is done by extending the context abstract provided in the Framework 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/ContextAbstract.php
(autocomplete context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/Autocomplete.php)

> as an integrator, you can either build your own (following it`s sample) 
> or extend it (it provides generic filters) for each use-case in order to set more filters if desired or other parameters (return fields, etc) 

<i>the Autocomplete[Context] will be the one to make the request by using an entity called RequestTransformer 
[https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/RequestTransformer.php] 
and adding extra filters per context: 
https://github.com/boxalino/intelligence-framework-shopware/blob/e284694d5e8356d9e0ab4c0ca4d58e135f67cd83/src/Framework/Request/ContextAbstract.php#L98
</i>

###### 2. Declare a service for the ApiPageLoader

Shopware works with the concept of PageContentLoader, so there is one provided for the framework: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php
this content loader is generic and it has to be declared as a service on which it is declared the ApiContextInterface which is context-specific (autocomplete, navigation, etc): 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/page.xml#L8 and has been created&declared at the prior step https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/autocomplete.xml#L13

###### 3. Decorate the SearchController and use the ApiPageLoader as a dependency

Extend the controller service by making use of the Autocomplete ApiPageLoader from step#2
https://github.com/boxalino/intelligence-integration-shopware/blob/d5d8c2c2077a7d710c5f40b465149ad17c1fb064/src/Resources/config/services/api/page.xml#L27

> The context ApiPageContentLoader is being used in the controller/cms pages/etc  
  The ApiPageLoader has access to Autocomplete[Context] request definition and to the ApiCallService
> The ApiPageLoader makes the call to the Boxalino API
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php#L70

###### 4. Create the SearchController

It will be used in order to rewrite the actual suggest action.
https://github.com/boxalino/intelligence-integration-shopware/blob/d5d8c2c2077a7d710c5f40b465149ad17c1fb064/src/Storefront/Controller/SearchController.php#L74

###### 5. Adjust the templates

For the autocomplete, you can either use the response as it comes from Boxalino or define a model on the product list component and load as objects
The templates can be found here: https://github.com/boxalino/intelligence-integration-shopware/tree/master/src/Resources/views/storefront/narrative/layout/header/suggest

