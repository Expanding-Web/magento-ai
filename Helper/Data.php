<?php
namespace ExpandingWeb\Ai\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterfaceFactory;
use ExpandingWeb\Ai\Api\ExpandingWebAiLogRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use ExpandingWeb\Ai\Service\Ai as AiService;

class Data extends AbstractHelper
{
    public const SECTION_PATH = 'expanding_web_ai';

    public const API_KEY_PATH = 'general/api_key';

    public const CONTENT = 'content_from_ai';

    public const DESCRIPTION = 'description_from_ai';

    public const META_TITLE = 'meta_title_from_ai';

    public const META_KEYWORD = 'meta_keyword_from_ai';

    public const META_DESCRIPTION = 'meta_description_from_ai';

    public const MAP_FIELDS = [
        self::DESCRIPTION => [
            'prefix_question' => 'Create description for',
            'mgt_field_id' => 'description'
        ],
        self::META_TITLE => [
            'prefix_question' => 'Create meta title for',
            'mgt_field_id' => 'meta_title'
        ],
        self::META_KEYWORD => [
            'prefix_question' => 'Create meta keywords for',
            'mgt_field_id' => 'meta_keyword'
        ],
        self::META_DESCRIPTION => [
            'prefix_question' => 'Create meta description for',
            'mgt_field_id' => 'meta_description'
        ],
    ];

    /**
     * @var AiService
     */
    private $ai;

    /**
     * @var ExpandingWebAiLogInterfaceFactory
     */
    private $aiLogFactory;

    /**
     * @var ExpandingWebAiLogRepositoryInterface
     */
    private $aiLogRepository;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Context $context
     * @param AiService $ai
     * @param ExpandingWebAiLogInterfaceFactory $aiLogFactory
     * @param ExpandingWebAiLogRepositoryInterface $aiLogRepository
     * @param Json $json
     */
    public function __construct(
        Context $context,
        AiService $ai,
        ExpandingWebAiLogInterfaceFactory $aiLogFactory,
        ExpandingWebAiLogRepositoryInterface $aiLogRepository,
        Json $json
    ) {
        $this->ai = $ai;
        $this->aiLogFactory = $aiLogFactory;
        $this->aiLogRepository = $aiLogRepository;
        $this->json = $json;
        parent::__construct($context);
    }

    /**
     * Get API Key
     *
     * @return string|null
     */
    private function getApiKey()
    {
        return $this->scopeConfig->getValue(
            self::SECTION_PATH.'/'.self::API_KEY_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Answer from AI
     *
     * @param string $question
     *
     * @return string|null
     * @throws CouldNotSaveException
     */
    public function getAnswer(string $question)
    {
        if (!empty($this->getApiKey())) {
            $params = ['prompt' => $question];

            $result = $this->ai->getResponse(
                $this->getApiKey(),
                $this->json->serialize($params)
            );

            if ($result) {
                try {
                    $resultArr = $this->json->unserialize($result);
                } catch (\InvalidArgumentException $e) {
                    if (str_contains($result, 'Unauthorized. Please make sure your API token is correct.')) {
                        return 'Valid API Token is required.';
                    }
                    $modelAiLog = $this->aiLogFactory->create();
                    $modelAiLog->setRequest($question);
                    $modelAiLog->setResponse($result);
                    return $e->getMessage();
                }
                if (isset($resultArr['message'])) {
                    $modelAiLog = $this->aiLogFactory->create();
                    $modelAiLog->setRequest($question);
                    $modelAiLog->setResponse($result);
                    $this->aiLogRepository->save($modelAiLog);

                    return $resultArr['message'];
                }
            }
        }

        return null;
    }

    /**
     * Get Prefix for Question
     *
     * @param string $fieldIndex
     *
     * @return string|null
     */
    public function getPrefixQuestion(string $fieldIndex)
    {
        if (isset(self::MAP_FIELDS[$fieldIndex])) {
            return self::MAP_FIELDS[$fieldIndex]['prefix_question'];
        }

        return null;
    }

    /**
     * Render Html content for builder cms
     *
     * @param string $content
     *
     * @return string
     */
    public function renderHtmlContent(string $content)
    {
        $htmlData = '<div data-content-type="row" data-appearance="contained" data-element="main">';
        $htmlData .= '<div data-element="inner">';
        $htmlData .= '<div data-content-type="text" data-appearance="default" data-element="main">';
        $htmlData .= '<p>'.nl2br($content).'</p>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';

        return $htmlData;
    }
}
