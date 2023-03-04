<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Test\Unit\Observer;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use ExpandingWeb\Ai\Observer\SaveCategory;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Request\Http as HttpRequest;
use ExpandingWeb\Ai\Model\Category;
use ExpandingWeb\Ai\Helper\Data as HelperData;

/**
 * Test class for save category
 */
class SaveCategoryTest extends TestCase
{
    /**
     * @var SaveCategory
     */
    protected $observer;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->helperData = $this->createPartialMock(
            HelperData::class,
            ['renderHtmlContent']
        );

        $objectManager  = new ObjectManager($this);
        $this->observer = $objectManager->getObject(
            SaveCategory::class,
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
        $descriptionTest = 'string';
        $descriptionHtml = '<div data-content-type="row" data-appearance="contained" data-element="main">';
        $descriptionHtml .= '<div data-element="inner">';
        $descriptionHtml .= '<div data-content-type="text" data-appearance="default" data-element="main">';
        $descriptionHtml .= '<p>'.$descriptionTest.'</p>';
        $descriptionHtml .= '</div>';
        $descriptionHtml .= '</div>';
        $descriptionHtml .= '</div>';

        $request = $this->createMock(HttpRequest::class);
        $request->expects($this->any())
            ->method('getParam')
            ->with('description_from_ai')
            ->willReturn($descriptionTest);

        $category = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperData->expects($this->once())
            ->method('renderHtmlContent')
            ->with($descriptionTest)
            ->willReturn($descriptionHtml);

        $category->expects(self::once())
            ->method('setDescription')
            ->with($descriptionHtml)
            ->willReturnSelf();

        $this->observer->execute(new Observer(['category' => $category, 'request' => $request]));
    }
}
