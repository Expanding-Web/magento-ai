<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Test\Unit\Observer;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use ExpandingWeb\Ai\Observer\SaveProduct;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Request\Http as HttpRequest;
use ExpandingWeb\Ai\Model\Product;
use Magento\Catalog\Controller\Adminhtml\Product\Save;
use ExpandingWeb\Ai\Helper\Data as HelperData;

/**
 * Test class for save product
 */
class SaveProductTest extends TestCase
{
    /**
     * @var SaveProduct
     */
    protected $observer;

    /**
     * Set up
     *
     * @return void
     *
     * @var HelperData
     */
    protected $helperData;

    protected function setUp(): void
    {
        $objectManager  = new ObjectManager($this);

        $this->helperData = $this->createPartialMock(
            HelperData::class,
            ['renderHtmlContent']
        );

        $this->observer = $objectManager->getObject(
            SaveProduct::class,
            [
                'helperData' => $this->helperData,
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
        $productData = [
            'description_from_ai' => 'description test'
        ];
        $descriptionHtml = '<div data-content-type="row" data-appearance="contained" data-element="main">';
        $descriptionHtml .= '<div data-element="inner">';
        $descriptionHtml .= '<div data-content-type="text" data-appearance="default" data-element="main">';
        $descriptionHtml .= '<p>description test</p>';
        $descriptionHtml .= '</div>';
        $descriptionHtml .= '</div>';
        $descriptionHtml .= '</div>';
        
        $request = $this->createMock(HttpRequest::class);
        $request->expects($this->any())
            ->method('getParam')
            ->with('product')
            ->willReturn($productData);
        $controller = $this->getMockBuilder(Save::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMockForAbstractClass();
        $controller->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $product->expects(self::once())
            ->method('setShortDescription')
            ->with($productData['description_from_ai'])
            ->willReturnSelf();

        $this->helperData->expects($this->once())
            ->method('renderHtmlContent')
            ->with($productData['description_from_ai'])
            ->willReturn($descriptionHtml);

        $product->expects(self::once())
            ->method('setDescription')
            ->with($descriptionHtml)
            ->willReturnSelf();

        $product->expects(self::once())
            ->method('save')
            ->willReturnSelf();

        $this->observer->execute(new Observer(['product' => $product, 'controller' => $controller]));
    }
}
