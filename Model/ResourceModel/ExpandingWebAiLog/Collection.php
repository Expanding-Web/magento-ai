<?php
namespace ExpandingWeb\Ai\Model\ResourceModel\ExpandingWebAiLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ExpandingWeb\Ai\Model\ResourceModel\ExpandingWebAiLog as ResourceModel;
use ExpandingWeb\Ai\Model\ExpandingWebAiLog as Model;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'expandingweb_ai_log_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
