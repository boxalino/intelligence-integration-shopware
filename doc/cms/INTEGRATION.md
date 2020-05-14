# CMS INTEGRATION (home slider)

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to the widget configured in the CMS element from your Shopping Experience Layout.

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

## About
Similar to the _cross-selling_ integration, the CMS block slot content is being updated with the use of an observer.
A CMS element is similar to a _listing_ request/context: it can be used with facets as well.

The narrative CMS block is located in the *block category "Commerce"* , with the name *Boxalino Narrative*.
All properties are declated in the CMS block configuration from your Shopping Experience Layout.

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

the CMS definition request from step#1 must be set "setApiContextInterface"
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L22

###### 3. Add the pre-built subscriber

https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/cms.xml#L27


> The context ApiCmsLoader is being used to read the configuration of the CMS block, 
> makes the request to Boxalino API and  creates the respons

*when the tracker will be available for installation, certain classes and data-segments will be added in order to track user movement 
and what content has been displayed.
