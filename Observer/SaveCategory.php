<?php
namespace ExpandingWeb\Ai\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use ExpandingWeb\Ai\Helper\Data as HelperData;

class SaveCategory implements ObserverInterface
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
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getData('request');
        $descriptionFromAi = $request->getParam(HelperData::DESCRIPTION);
        $mainCategory = $observer->getData('category');
        if ($descriptionFromAi) {
            $htmlData = $this->helperData->renderHtmlContent($descriptionFromAi);
            $mainCategory->setDescription($htmlData);
        }
    }
}
