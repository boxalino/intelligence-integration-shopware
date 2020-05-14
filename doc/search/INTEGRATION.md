# SEARCH INTEGRATION

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget <b>search</b>

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## Steps
 ###### 1. Create and declare a service for the search definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/search.xml 
(widget name, context, etc)

* the search request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/SearchRequestDefinition.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the search request context is done by extending the search context abstract provided in the Framework and declaring the abstract functions
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/SearchContextAbstract.php
(search context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/Search.php)

The SearchContextAbstract class is implementing 2 API-specific interfaces: 
1. The _SearchContextInterface_ : allows to manage the sub-phrases scenario with parameters https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Context/SearchContextInterface.php 
these parameters can be declared when you configure the service in the search.xml
2. The _ListingContextInterface_ : forces the integrator to *addFacets* based on the request. 

> as an integrator, you can either build your own (following it`s sample) 
> or extend it (it provides generic filters) for each use-case in order to set more filters if desired or other parameters (return fields, etc) 

<i>the Search[Context] will be the one to make the request by using an entity called RequestTransformer 
[https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/RequestTransformer.php] 
and adding extra filters per context: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/ContextAbstract.php#L88
</i>

###### 2. Declare a service for the ApiPageLoader

Shopware6 works with the concept of PageContentLoader, so there is one provided for the framework: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php
this content loader is _generic_ and it has to be declared as a service on which it is declared the ApiContextInterface which is context-specific (search, navigation, etc): 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/page.xml#L16 
and has been created&declared at the prior step 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/search.xml#L13

###### 3. Decorate the SearchController and use the ApiPageLoader as a dependency

Extend the controller service by making use of the Search ApiPageLoader from step#2
https://github.com/boxalino/intelligence-integration-shopware/src/Resources/config/services/api/page.xml#L27

> The context ApiPageContentLoader is being used in the controller/cms pages/etc  
  The ApiPageLoader has access to Search[Context] request definition and to the ApiCallService
> The ApiPageLoader makes the call to the Boxalino API
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiPageLoader.php#L70

###### 4. Create the SearchController

It will be used in order to rewrite the actual search action.
https://github.com/boxalino/intelligence-integration-shopware/src/Storefront/Controller/SearchController.php#L51

2 functions have to be extended:
1. search (does the initial search)
2. pagelet (does the ajax filtering)

###### 5. Updating the sorting (optional)

To comply with Shopware6 way for adding sorting, we have designed a sorting handling class that has the map between a Boxalino field
and a Shopware6/store fields
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Resources/config/services/api/page.xml#L35

In case your setup handles more/other sorting options:
1. Follow the Shopware6 documentation on how to declare new sorting fields
2. Declare new fields to the collection by using the service

###### 6. Facets

- Shopware6 facets are ajax-based. The controller action that handles the facets is the *pagelet* function.
- By _default_, Shopware6 does not update facets&search page title on pagelet, only the content within cmsProductListingSelector
(as seen in the vendor/shopware/platform/src/Storefront/Resources/app/storefront/src/plugin/listing/listing.plugin.js, renderResponse action)

In the template samples within this repository, the integrator is presented with a strategy to also re-render the facets with each ajax call.
This will provide the user with the facet options available based on prior selected filters.

####### 6.1 Update facets based on prior selected options
In order to integrate this functionality:
1. use the narrative-ajax.json for the narrative structure: it is composed of a single block wrapping the title, facets, products
2. add the JavaScript class https://github.com/boxalino/intelligence-integration-shopware/tree/master/src/Resources/app/storefront/src/boxalinointegration/boxalinointegration.plugin.js
> the JS contains more information on what it represents
3. check out the filter-panel template for more insight/differences: https://github.com/boxalino/intelligence-integration-shopware/tree/master/src/Resources/views/storefront/narrative/component/listing/filter-panel.html.twig

####### 6.2 Shopware6 default
In order to integrate this strategy:
1. use the narrative.json for the narrative structure: the page title, facets & listing are 3 individual blocks;
2. update the filter-panel template by removing the block *boxalino_filter_panel_container_active* and removing the inline style from the default block


###### 7. Create templates

For the search response we recommend to use a model load (as defined in your product list component).
(sample service: https://github.com/boxalino/intelligence-framework-shopware/blob/ea7ad9b71eff66bebdd988e098d332494b299fbe/src/Resources/config/services/api/page.xml#L22)

This will ensure proper price and stock display. It is a mock for the Shopware6 SearchEntityResult response type, with all the aggregations provided by Shopware6.

_In this repository are integrated sample templates based on the default Shopware6 templates.
These are to be styled and adapted per your project requirements. Boxalino is not providing front-end/template customizations._

1. minimum adjustments for the cms-element-product-listing element by adding bits of header/wrapper from the search
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/element/cms-element-product-listing.html.twig
2. the listing itself has been minimized and the toolbar (pagination + sorting) have been removed (as they`re loaded as individual blocks within the loop)
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/component/product/listing.html.twig
3. the product item template itself has few adjustments (an extra div), otherwise - due to the load of the item from a collection - no other adjustment needed
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/component/product/card/box.html.twig
4. the filter-panel items have been updated to use the response facetValue; check out the filter-panel.hmtl.twig for the logic on what template is rendered for the items;
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/component/listing/filter

More templates for this use-case (sorting, toolbar, pagination, filters, etc):
https://github.com/boxalino/intelligence-integration-shopware/tree/master/src/Resources/views/storefront/narrative/component


