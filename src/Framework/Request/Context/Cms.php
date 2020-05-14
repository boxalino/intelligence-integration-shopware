<?php declare(strict_types=1);
namespace Boxalino\IntelligenceIntegration\Framework\Request\Context;

use Boxalino\IntelligenceFramework\Framework\Request\CmsContextAbstract;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;

/**
 * CMS request
 *
 * @package Boxalino\IntelligenceIntegration\Framework\Request\Context
 */
class Cms extends CmsContextAbstract
{

    /**
     * If there are configured returnFields in the CMS element - they will be used
     *
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "products_group_id"];
    }

    /**
     * @return int
     */
    public function getContextVisibility() : int
    {
        return ProductVisibilityDefinition::VISIBILITY_SEARCH;
    }

    /**
     * Range properties
     *
     * @return array
     */
    public function getRangeProperties(): array
    {
        return [
            "products_rating_average" => ['from' => 'products_rating_average', 'to' => 0],
            "discountedPrice" => ['from' => 'min-price', 'to' => 'max-price']
        ];
    }

}
