<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\AutocompleteContextAbstract;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Autocomplete request
 *
 * The autocomplete request can have facets&filters set; predefined order, etc
 * These are used align response elements (of different types or under different facets)
 *
 * By default, in Shopware6, the autocomplete response is from
 * the route("/suggest", name="frontend.search.suggest", methods={"GET"}, defaults={"XmlHttpRequest"=true})
 *
 * Can be customized to also have facets set/pre-defined. Please consult with Boxalino on more advanced scenarios.
 *
 * @package Boxalino\IntelligenceIntegration\Service\Api
 */
class Autocomplete extends AutocompleteContextAbstract
{

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
     * @return int
     */
    public function getContextVisibility() : int
    {
        return ProductVisibilityDefinition::VISIBILITY_SEARCH;
    }

    /**
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "discountedPrice", "products_seo_url", "title", "products_image"];
    }

}
