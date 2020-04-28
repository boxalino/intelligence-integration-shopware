# SEARCH INTEGRATION

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget <b>search</b>

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## Steps
 ###### 1. Declare a service for the search definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/search.xml 
(widget name, context, etc)

* the search request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/ListingRequestDefinitionInterface.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the search request context is done by extending the context abstract provided in the Framework 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/ContextAbstract.php
(search context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/Search.php)

> as an integrator, you can either build your own (following it`s sample) 
> or extend it (it provides generic filters) for each use-case in order to set more filters if desired or other parameters (return fields, etc) 

<i>the Search[Context] will be the one to make the request by using an entity called RequestTransformer 
[https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/RequestTransformer.php] 
and adding extra filters per context: 
https://github.com/boxalino/intelligence-framework-shopware/blob/e284694d5e8356d9e0ab4c0ca4d58e135f67cd83/src/Framework/Request/ContextAbstract.php#L98
</i>

###### 2. Declare a service for the ApiPageLoader

Shopware works with the concept of PageContentLoader, so there is one provided for the framework: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php
this content loader is generic and it has to be declared as a service on which it is declared the ApiContextInterface which is context-specific (search, navigation, etc): 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/page.xml#L16 and has been created&declared at the prior step https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/search.xml#L13

###### 3. Decorate the SearchController and use the ApiPageLoader as a dependency

Extend the controller service by making use of the Search ApiPageLoader from step#2
https://github.com/boxalino/intelligence-integration-shopware/blob/d5d8c2c2077a7d710c5f40b465149ad17c1fb064/src/Resources/config/services/api/page.xml#L26

> The context ApiPageContentLoader is being used in the controller/cms pages/etc  
  The ApiPageLoader has access to Search[Context] request definition and to the ApiCallService
> The ApiPageLoader makes the call to the Boxalino API
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php#L70

###### 4. Create the SearchController

It will be used in order to rewrite the actual search action.
https://github.com/boxalino/intelligence-integration-shopware/blob/d5d8c2c2077a7d710c5f40b465149ad17c1fb064/src/Storefront/Controller/SearchController.php#L51

###### 5. Adjust the templates

For the search response we recommend to use a model load (as defined in your product list component).
This will ensure proper price and stock display.

The template samples, based on the default Shopware theme, can be found here: 
1. minimum adjustments for the cms-element-product-listing element by adding bits of header/wrapper from the search
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/element/cms-element-product-listing.html.twig
2. the listing itself has been minimized and the toolbar (pagination + sorting) have been removed (as they`re loaded as individual blocks within the loop)
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/component/product/listing.html.twig
3. the product item template itself has few adjustments (an extra div), otherwise - due to the load of the item from a collection - no other adjustment needed
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/component/product/card/box.html.twig

More templates for this use-case (sorting, toolbar, pagination, filters, etc):
https://github.com/boxalino/intelligence-integration-shopware/tree/master/src/Resources/views/storefront/narrative/component
