<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\SearchContextAbstract;
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
     * Other fields can be: products_seo_url, products_image, discountedPrice, etc
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "products_group_id", "title"];
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
