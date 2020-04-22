<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\ContextAbstract;
use GuzzleHttp\Client;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Navigation request
 *
 * A listing context can render a default Category/Search view layout:
 * facets, products, sorting, pagination and other narrative elements
 *
 * @package Boxalino\IntelligenceIntegration\Framework\Request\Context
 */
class Navigation extends ContextAbstract
{
    /**
     * @return int
     */
    public function getContextVisibility() : int
    {
        return ProductVisibilityDefinition::VISIBILITY_ALL;
    }

    /**
     * @param Request $request
     * @param SalesChannelContext $salesChannelContext
     * @return string
     */
    public function getContextNavigationId(Request $request, SalesChannelContext $salesChannelContext): array
    {
        $params = $request->attributes->get('_route_params');
        if ($params && isset($params['navigationId']))
        {
            return [$params['navigationId']];
        }

        return [$salesChannelContext->getSalesChannel()->getNavigationCategoryId()];
    }

    /**
     * Set the return fields desired for navigation
     *
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "discountedPrice", "products_seo_url", "title", "products_image"];
    }

}
