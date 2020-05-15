# CMS INTEGRATION (navigation)

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget configured in the CMS element from your Shopping Experience Layout.

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## About
Similar to the _cms-slider_ integration, the CMS block slot content is being updated with the use of the same observer.
A CMS element is similar to a _listing_ request/context: it can be used with facets as well.

The narrative CMS block is located in the *block category "Commerce"* , with the name *Boxalino Narrative*.
All properties are declared in the CMS block configuration from your Shopping Experience Layout.
The required properties are marked with a * (star).

In the case of navigation, the layout is presented with a list of facets.
There are 2 ways to integrate the CMS block:
1. with a sidebar (by enabling the _Sidebar Layout_ switch)
2. full-width (or without a sidebar)

In both cases, the Boxalino Narrative CMS block must be added in the *main content section*, not in the "sidebar".
Optionally, in the sidebar, you can add the "category navigation" element as that one is not part of the Boxalino response.

If you have already followed up the guidelines for the integration of the cms-slider, please skip steps 1-3.

## Steps
###### 1. Declare a service for the CMS definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml 

* the CMS request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/ListingRequestDefinitionInterface.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the CMS request context is done by extending the context abstract provided in the Framework 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/CmsContextAbstract.php
(CMS context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/Cms.php)

> the CmsContextAbstract, unlike other context bases, it adds some new parameters to the request - navigation id & sidebar. 
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/CmsContextAbstract.php#L25
> it also adds the filters&facets as configured in the CMS block in your layout
> this is used in the narrative structure in order to adapt the response structure to your own Shopware6 configuration

###### 2. Declare a service for the ApiCmsLoader

Shopware works with the concept of PageContentLoader, so there is one provided for the framework: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiCmsLoader.php
this content loader has to be declared as a service:
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L18

the CMS definition request from step#1 must be declared via a setter injection with "setApiContextInterface"
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L22

The ApiCmsLoader will return a _page_ per Shopware6 CMS elements standard.
It uses the ApiCmsModel https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiCmsLoader.php#L38

###### 3. Add the pre-built subscriber

In the Framework is available a subscriber which can be used as is:
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Subscriber/ApiCmsLoaderSubscriber.php

In order to integrate it, you have to declare it:
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L27

> The context ApiCmsLoader is being used to read the configuration of the CMS block, make the request to Boxalino API and structure the response
> the ApiCmsLoader has a list of additional functions meant to support the integrator in extending the functionalities of the subscriber
> for ex: https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiCmsLoader.php#L117
> the *getBlocksByPropertyValue* can be used to isolate the root blocks with certain properties (ex: sectionOrder, sectionPosition, etc)
> these are custom integrations which are not provided by Boxalino, but can be developed by the integrator

> The subscriber ApiCmsLoaderSubscriber is being used to go through the layout`s sections&blocks and identify the narrative element
> if the Boxalino Narrative CMS Block is configured to add elements to the sidebar, it will update the sidebar section
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Subscriber/ApiCmsLoaderSubscriber.php#L88
> A series of helper functions (createCmsSlotEntity, createCmsSlotCollection, createCmsBlockEntity) are provided.
> These functions can be used to further extend the layout with new sections (top, bottom) or new blocks in existing sections

###### 4. Templating

The templates used are the ones configured in the same required for the search use-case 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/doc/search/INTEGRATION.md#L102
