# CMS INTEGRATION (home slider)

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget configured in the CMS element from your Shopping Experience Layout.

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## About
Similar to the _cross-selling_ integration, the CMS block slot content is being updated with the use of an observer.
A CMS element is similar to a _listing_ request/context: it can be used with facets as well.

The narrative CMS block is located in the *block category "Commerce"* , with the name *Boxalino Narrative*.
All properties are declared in the CMS block configuration from your Shopping Experience Layout.
The required properties are marked with a * (star).

## Steps
 ###### 1. Declare a service for the CMS definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml 

* the CMS request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/ListingRequestDefinitionInterface.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the CMS request context is done by extending the context abstract provided in the Framework 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/CmsContextAbstract.php
(CMS context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/Cms.php)

###### 2. Declare a service for the ApiCmsLoader

Shopware works with the concept of PageContentLoader, so there is one provided for the framework: 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiCmsLoader.php
this content loader has to be declared as a service:
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L18

the CMS definition request from step#1 must be declared via a setter injection with "setApiContextInterface"
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L22

The ApiCmsLoader will return a _page_ per Shopware6 CMS elements standard.
It uses the ApiCmsModel https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Listing/ApiCmsModel.php

###### 3. Add the pre-built subscriber

In the Framework is available a subscriber which can be used as is:
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Subscriber/ApiCmsLoaderSubscriber.php

In order to integrate it, you have to declare it:
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L27

> The context ApiCmsLoader is being used to read the configuration of the CMS block, make the request to Boxalino API and structure the response

> The subscriber ApiCmsLoaderSubscriber is being used to go through the layout`s sections&blocks and identify the narrative element, 
> https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Subscriber/ApiCmsLoaderSubscriber.php#L81

###### 4. Templating

The templates used are the ones configured in the sample layout block _product_slider_ and _product_
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/views/storefront/narrative/element/cms-element-product-slider.html.twig

There has been applied minimum updates which can be checked by mapping it with the default Shopware6 template.
