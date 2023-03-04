<?php
namespace ExpandingWeb\Ai\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use ExpandingWeb\Ai\Helper\Data as HelperData;

class SaveProduct implements ObserverInterface
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
        $productController = $observer->getData('controller');
        $data = $productController->getRequest()->getParam('product');
        $mainProduct = $observer->getData('product');
        if (isset($data[HelperData::DESCRIPTION]) && !empty($data[HelperData::DESCRIPTION])) {
            $fieldData = $data[HelperData::DESCRIPTION];
            $mainProduct->setShortDescription($fieldData);
            $htmlData = $this->helperData->renderHtmlContent($fieldData);
            $mainProduct->setDescription($htmlData);
            $mainProduct->save();
        }
    }
}
