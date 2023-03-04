<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Test\Unit\Observer;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use ExpandingWeb\Ai\Observer\SaveCmsPage;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Cms\Model\Page;
use ExpandingWeb\Ai\Helper\Data as HelperData;

/**
 * Test class for save category
 */
class SaveCmsPageTest extends TestCase
{
    /**
     * @var SaveCmsPage
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
            SaveCmsPage::class,
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
        $request = $this->createMock(HttpRequest::class);

        $contentFromAi = "content test";
        $htmlData = '<div data-content-type="row" data-appearance="contained" data-element="main">';
        $htmlData .= '<div data-element="inner">';
        $htmlData .= '<div data-content-type="text" data-appearance="default" data-element="main">';
        $htmlData .= '<p>'.nl2br($contentFromAi).'</p>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';
        $htmlData .= '</div>';

        $request->expects($this->any())
            ->method('getParam')
            ->with('content_from_ai')
            ->willReturn($contentFromAi);

        $page = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperData
            ->expects($this->once())
            ->method('renderHtmlContent')
            ->with($contentFromAi)
            ->willReturn($htmlData);

        $page->expects(self::once())
            ->method('setContent')
            ->with($htmlData)
            ->willReturnSelf();

        $this->observer->execute(new Observer(['page' => $page, 'request' => $request]));
    }
}
