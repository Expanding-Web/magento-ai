<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Model;

use Magento\Framework\Model\AbstractModel;
use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterface;
use ExpandingWeb\Ai\Model\ResourceModel\ExpandingWebAiLog as ResourceModel;

class ExpandingWebAiLog extends AbstractModel implements ExpandingWebAiLogInterface
{
    /**
     * Initialize magento model.
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return (int)$this->_getData(ExpandingWebAiLogInterface::ID);
    }

    /**
     * @inheritdoc
     */
    public function getRequest(): string
    {
        return $this->_getData(ExpandingWebAiLogInterface::REQUEST);
    }

    /**
     * @inheritdoc
     */
    public function setRequest(string $value): ExpandingWebAiLogInterface
    {
        $this->setData(ExpandingWebAiLogInterface::REQUEST, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResponse(): string
    {
        return $this->_getData(ExpandingWebAiLogInterface::RESPONSE);
    }

    /**
     * @inheritdoc
     */
    public function setResponse(string $value): ExpandingWebAiLogInterface
    {
        $this->setData(ExpandingWebAiLogInterface::RESPONSE, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): string
    {
        return $this->_getData(ExpandingWebAiLogInterface::CREATED_AT);
    }
}
