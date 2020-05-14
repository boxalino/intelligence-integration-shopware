<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Storefront\Controller;

use Boxalino\IntelligenceFramework\Framework\Content\Page\ApiPageLoader as AutocompletePageLoader;
use Boxalino\IntelligenceFramework\Framework\Content\Page\ApiPageLoader as SearchPageLoader;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Storefront\Controller\SearchController as ShopwareSearchController;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class SearchController extends ShopwareSearchController
{
    /**
     * @var SearchPageLoader
     */
    private $searchApiPageLoader;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AutocompletePageLoader
     */
    private $autocompleteApiPageLoader;

    /**
     * @var ShopwareSearchController
     */
    private $decorated;

    public function __construct(
        ShopwareSearchController $decorated,
        SearchPageLoader $searchApiPageLoader,
        AutocompletePageLoader $autocompleteApiPageLoader,
        LoggerInterface $logger
    ){
        $this->decorated = $decorated;
        $this->searchApiPageLoader = $searchApiPageLoader;
        $this->autocompleteApiPageLoader = $autocompleteApiPageLoader;
        $this->logger = $logger;
    }

    /**
     * @HttpCache()
     * @Route("/search", name="frontend.search.page", methods={"GET"})
     */
    public function search(SalesChannelContext $context, Request $request): Response
    {
        try {
            $page = $this->searchApiPageLoader->load($request, $context);
            if($page->getRedirectUrl())
            {
                return $this->forwardToRoute($page->getRedirectUrl());
            }

            /**
             * the render template is a narrative page
             * div-less narrative template: @BoxalinoIntelligenceFramework/storefront/element/cms-element-narrative-page.html.twig
             */
            return $this->renderStorefront('@BoxalinoIntelligenceIntegration/storefront/narrative/page/search/index.html.twig', ['page' => $page]);
        } catch (\Throwable $exception) {
            /**
             * Fallback
             */
            $this->logger->alert("BoxalinoAPI: There was an issue with your request." . $exception->getMessage());
            return $this->decorated->search($context, $request);
        }

    }

    /**
     * @HttpCache()
     * @Route("/suggest", name="frontend.search.suggest", methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function suggest(SalesChannelContext $context, Request $request): Response
    {
        try{
            $page = $this->autocompleteApiPageLoader->load($request, $context);
            /**
             * the render template is a narrative element
             */
            return $this->renderStorefront('@BoxalinoIntelligenceFramework/storefront/element/cms-element-narrative-content.html.twig', ['page' => $page]);
        } catch (\Throwable $exception)
        {
            /**
             * Fallback
             */
            $this->logger->warning("BoxalinoAPI: There was an issue with the autocomplete request " . $exception->getMessage());
            return $this->decorated->suggest($context, $request);
        }
    }

    /**
     * @HttpCache()
     * @RouteScope(scopes={"storefront"})
     * @Route("/widgets/search/{search}", name="widgets.search.pagelet", methods={"GET", "POST"}, defaults={"XmlHttpRequest"=true})
     *
     * @throws MissingRequestParameterException
     */
    public function pagelet(Request $request, SalesChannelContext $context): Response
    {
        try{
            $page = $this->searchApiPageLoader->load($request, $context);

            /**
             * by DEFAULT, Shopware6 does not update facets&search page title on pagelet, only the content within cmsProductListingSelector
             * (as seen in the vendor/shopware/platform/src/Storefront/Resources/app/storefront/src/plugin/listing/listing.plugin.js, renderResponse action)
             */
            return $this->renderStorefront('@BoxalinoIntelligenceFramework/storefront/element/cms-element-narrative-content.html.twig', ['page' => $page]);
        } catch (\Throwable $exception)
        {
            /**
             * Fallback
             */
            $this->logger->warning("BoxalinoAPI: There was an issue with the pagelet request " . $exception->getMessage());
            return $this->decorated->pagelet($request, $context);
        }

    }

}
