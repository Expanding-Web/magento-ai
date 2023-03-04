<?php
namespace ExpandingWeb\Ai\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use ExpandingWeb\Ai\Helper\Data as HelperData;

class SaveCmsPage implements ObserverInterface
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
        $contentFromAi = $request->getParam(HelperData::CONTENT);
        $pageModel = $observer->getData('page');
        if ($contentFromAi) {
            $htmlData = $this->helperData->renderHtmlContent($contentFromAi);
            $pageModel->setContent($htmlData);
        }
    }
}
