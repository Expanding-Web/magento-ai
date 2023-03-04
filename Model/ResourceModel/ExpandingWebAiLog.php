<?php
namespace ExpandingWeb\Ai\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterface;

class ExpandingWebAiLog extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'expandingweb_ai_log_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(ExpandingWebAiLogInterface::TABLE_NAME, ExpandingWebAiLogInterface::ID);
        $this->_useIsObjectNew = true;
    }
}
