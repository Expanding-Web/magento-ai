<?php

namespace ExpandingWeb\Ai\Model;

use Magento\Catalog\Model\Category as ModelCategory;

class Category extends ModelCategory
{

    public const KEY_DESCRIPTION = 'description';

    /**
     * Set category description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::KEY_DESCRIPTION, $description);
    }
}
