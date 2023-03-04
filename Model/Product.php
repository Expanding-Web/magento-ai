<?php
namespace ExpandingWeb\Ai\Model;

use Magento\Catalog\Model\Product as ModelProduct;

class Product extends ModelProduct
{
    public const KEY_DESCRIPTION = 'description';

    public const KEY_SHORT_DESCRIPTION = 'short_description';

    /**
     * Set Product description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::KEY_DESCRIPTION, $description);
    }

    /**
     * Set Product shortDescription
     *
     * @param string $shortDescription
     *
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        return $this->setData(self::KEY_SHORT_DESCRIPTION, $shortDescription);
    }
}
