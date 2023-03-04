<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Test\Unit\Controller\Adminhtml\Api;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use ExpandingWeb\Ai\Controller\Adminhtml\Api\GetResponse;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use ExpandingWeb\Ai\Helper\Data as HelperData;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;

class GetResponseTest extends TestCase
{
    /**
     * @var GetResponse
     */
    protected $controller;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Json
     */
    protected $resultJson;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->request = $this->getMockBuilder(RequestInterface::class)
            ->getMockForAbstractClass();

        $context = $this->createMock(Context::class);
        $context->method('getRequest')
            ->willReturn($this->request);

        $this->helperData = $this->createPartialMock(
            HelperData::class,
            ['getPrefixQuestion', 'getAnswer']
        );

        $this->resultJson = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->jsonFactory = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->controller = $objectManager->getObject(
            GetResponse::class,
            [
                'context' => $context,
                'helperData' => $this->helperData,
                'jsonFactory' => $this->jsonFactory
            ]
        );
    }

    /**
     * Test Execute
     *
     * @return void
     */
    public function testExecute(): void
    {
        $name = 'Children pants C01';
        $fieldIndex = 'meta_title';
        $prefixQuestion = 'Create meta title for';
        $type = 'product';
        $keywordsExtra = 'best price';
        $question = 'Create meta title for product Children pants C01 including this text: best price';
        $answer = "Best Price on Children's Pants C01 | Quality & Comfort for Kids";
        $result = [
            'answer' => trim($answer),
            'success' => true
        ];
        $response = '{"answer":"Best Price on Children\'s Pants C01 | Quality & Comfort for Kids","success":true}';

        $this->request->method('getParam')
            ->willReturnMap(
                [
                    ['name', null, $name],
                    ['field_index', null, $fieldIndex],
                    ['type', null, $type],
                    ['keywords_extra', null, $keywordsExtra]
                ]
            );

        $this->helperData->expects($this->once())
            ->method('getPrefixQuestion')
            ->with($fieldIndex)
            ->willReturn($prefixQuestion);

        $this->helperData->expects($this->once())
            ->method('getAnswer')
            ->with($question)
            ->willReturn($answer);

        $this->jsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->resultJson);

        $this->resultJson->expects($this->once())
            ->method('setData')
            ->with($result)
            ->willReturn($response);

        self::assertEquals($response, $this->controller->execute());
    }
}
