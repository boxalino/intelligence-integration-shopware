<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Storefront\Controller;

use Boxalino\IntelligenceFramework\Framework\Content\Page\ApiPageLoader as AutocompletePageLoader;
use Boxalino\IntelligenceFramework\Framework\Content\Page\ApiPageLoader as SearchPageLoader;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Storefront\Page\GenericPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\SearchController as ShopwareSearchController;

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

    public function __construct(
        SearchPageLoader $searchApiPageLoader,
        AutocompletePageLoader $autocompleteApiPageLoader,
        LoggerInterface $logger
    ){
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
        } catch (MissingRequestParameterException $missingRequestParameterException) {
            return $this->forwardToRoute('frontend.home.page');
        }

        if($page->getRedirectUrl())
        {
            return $this->forwardToRoute($page->getRedirectUrl());
        }

        /**
         * the render template is a narrative page
         */
        return $this->renderStorefront('@BoxalinoIntelligenceFramework/storefront/element/cms-element-narrative-page.html.twig', ['page' => $page]);
    }

    /**
     * @HttpCache()
     * @Route("/suggest", name="frontend.search.suggest", methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function suggest(SalesChannelContext $context, Request $request): Response
    {
        $page = $this->autocompleteApiPageLoader->load($request, $context);

        /**
         * the render template is a narrative element
         */
        return $this->renderStorefront('@BoxalinoIntelligenceFramework/storefront/element/cms-element-narrative-content.html.twig', ['page' => $page]);
    }

}
