<?php
namespace ExpandingWeb\Ai\Controller\Adminhtml\Api;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use ExpandingWeb\Ai\Helper\Data as HelperData;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class GetResponse extends Action implements HttpPostActionInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param HelperData $helperData
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->helperData = $helperData;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Get answer from AI
     *
     * @return Json
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $answer = null;
        $success = true;
        $question = $this->getQuestion();
        if ($question) {
            try {
                $answer = $this->helperData->getAnswer($question);
            } catch (\Exception $e) {
                $answer = $e->getMessage();
                $success = false;
            }
            if (!empty($answer)) {
                $answer = trim($answer);
            }
        } else {
            $success = false;
            $answer = __('Please add some context so the AI can come up with the right content.');
        }

        return $resultJson->setData([
            'answer' => $answer,
            'success' => $success
        ]);
    }

    /**
     * Get Question
     *
     * @return string|null
     */
    private function getQuestion()
    {
        $question = null;
        $name = $this->getRequest()->getParam('name');
        $fieldIndex = $this->getRequest()->getParam('field_index');
        $prefixQuestion = $this->helperData->getPrefixQuestion($fieldIndex);
        $type = $this->getRequest()->getParam('type');
        $keywordsExtra = $this->getRequest()->getParam('keywords_extra');
        if ($name && $prefixQuestion) {
            $question = __($prefixQuestion) . ' ' . __($type) . ' ' . $name;
            if ($keywordsExtra) {
                $question .=  ' ' . __('including this text') . ': ' . $keywordsExtra;
            }
        } elseif ($fieldIndex == HelperData::CONTENT) {
            if ($keywordsExtra) {
                $question = $keywordsExtra;
            } else {
                return null;
            }
        }

        return $question;
    }
}
