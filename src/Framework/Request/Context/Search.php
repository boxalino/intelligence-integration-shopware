<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\ContextAbstract;
use Boxalino\IntelligenceFramework\Framework\Request\SearchContextAbstract;
use GuzzleHttp\Client;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Search request
 *
 * A listing context can render a default Category/Search view layout:
 * facets, products, sorting, pagination and other narrative elements
 *
 * @package Boxalino\IntelligenceIntegration\Framework\Request\Context
 */
class Search extends SearchContextAbstract
{
    /**
     * @return int
     */
    public function getContextVisibility() : int
    {
        return ProductVisibilityDefinition::VISIBILITY_SEARCH;
    }

    /**
     * @param Request $request
     * @param SalesChannelContext $salesChannelContext
     * @return string
     */
    public function getContextNavigationId(Request $request, SalesChannelContext $salesChannelContext): array
    {
        return [$salesChannelContext->getSalesChannel()->getNavigationCategoryId()];
    }

    /**
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "products_group_id", "discountedPrice", "products_seo_url", "title", "products_image"];
    }

}
