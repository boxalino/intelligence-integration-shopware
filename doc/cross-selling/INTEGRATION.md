# CROSS-SELLING INTEGRATION

Please be aware that there have to be designed Layout Blocks in Boxalino Intelligence Admin
and that there has to exist a narrative attached to one of the widget parts of your cross-selling choices.

JSON samples of both elements are being provided and can be imported in your Boxalino Intelligence Admin panel.

The default cross-selling integration is done by rewriting the cross-selling structures you have configured in Shopware.
You can configure the service to set all your existing cross-selling rules as context parameters via the use of the _setConfiguredProductsAsContextParameters_ method
(https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/crossselling.xml#L17)

No template requires adjustments for this integration*

## Steps
 ###### 1. Declare a service for the cross-selling definition request 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/crossselling.xml 
(widget name, context, etc)

* the cross-selling request definition is available via an interface 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Service/Api/Request/Definition/ItemRequestDefinitionInterface.php
and has been declared as a service in BoxalinoIntelligenceFramework

* the cross-selling request context is done by extending the context abstract provided in the Framework 
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/ItemContextAbstract.php
(cross-selling context sample https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Framework/Request/Context/CrossSelling.php)

> as an integrator, you can either build your own (following the sample and interfaces requirements)  
> or extend it (it provides generic filters) for each use-case in order to set more filters if desired or other parameters (return fields, group by field, etc) 

<i>the CrossSelling[Context] will be the one to make the request by using an entity called RequestTransformer 
[https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Request/RequestTransformer.php] 
and adding extra filters per context: 
https://github.com/boxalino/intelligence-framework-shopware/blob/e284694d5e8356d9e0ab4c0ca4d58e135f67cd83/src/Framework/Request/ContextAbstract.php#L98
</i>

###### 2. Set the ApiContextInterface created at step1 as a parameter for the ApiCrossSellingLoader class

Following the sample of CrossSellingPageLoader from Shopware6, a similar construct has been design to retrieve the data from the API.
https://github.com/boxalino/intelligence-framework-shopware/blob/master/src/Framework/Content/Page/ApiCrossSellingLoader.php
this content loader requires the DI to the declared ApiContextInterface above: 
https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/crossselling.xml#L24 
and has been created&declared at the prior step https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/crossselling.xml#L8

###### 3. Add the pre-built subscriber

https://github.com/boxalino/intelligence-integration-shopware/blob/master/src/Resources/config/services/api/crossselling.xml#L30


> The context ApiCrossSellingLoader is being used to make the request to Boxalino API, creates a collection of products
>and follows the Shopware6 strategy to create CrossSellingElements

*when the tracker will be available for installation, certain classes and data-segments will be added in order to track user movement 
and what content has been displayed.
