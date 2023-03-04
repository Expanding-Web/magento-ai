<?php
namespace ExpandingWeb\Ai\Plugin\Magento\Cms\Api;

use Magento\Cms\Api\BlockRepositoryInterface as MagentoBlockRepository;
use Magento\Cms\Api\Data\BlockInterface;
use ExpandingWeb\Ai\Helper\Data as HelperData;

class BlockRepositoryInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param HelperData $helperData
     */
    public function __construct(
        HelperData $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * Before Save Cms Block
     *
     * @param MagentoBlockRepository $subject
     * @param BlockInterface $block
     * @return BlockInterface[]
     */
    public function beforeSave(MagentoBlockRepository $subject, BlockInterface $block)
    {
        $contentFromAi = $block->getData(HelperData::CONTENT);
        if ($contentFromAi) {
            $htmlData = $this->helperData->renderHtmlContent($contentFromAi);
            $block->setContent($htmlData);
        }

        return [$block];
    }
}
