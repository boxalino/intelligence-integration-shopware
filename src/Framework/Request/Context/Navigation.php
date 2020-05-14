<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\ContextAbstract;
use Boxalino\IntelligenceFramework\Framework\Request\ListingContextAbstract;
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
class Navigation extends ListingContextAbstract
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
        return ["id", "products_group_id","discountedPrice", "products_seo_url", "title", "products_image"];
    }

    /**
     * Set the range properties following the presented structure
     *
     * @return array
     */
    public function getRangeProperties() : array
    {
        return [
            "products_rating_average" => ['from' => 'products_rating_average', 'to' => 0],
            "discountedPrice" => ['from' => 'min-price', 'to' => 'max-price']
        ];
    }

}
