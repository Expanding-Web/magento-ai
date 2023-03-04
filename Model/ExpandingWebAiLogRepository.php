<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Model;

use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterface;
use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterfaceFactory;
use ExpandingWeb\Ai\Api\ExpandingWebAiLogRepositoryInterface;
use ExpandingWeb\Ai\Model\ResourceModel\ExpandingWebAiLog as Resource;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class ExpandingWebAiLogRepository implements ExpandingWebAiLogRepositoryInterface
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var ExpandingWebAiLogInterfaceFactory
     */
    private $aiLogFactory;

    /**
     * @param Resource $resource
     * @param ExpandingWebAiLogInterfaceFactory $aiLogFactory
     */
    public function __construct(
        Resource $resource,
        ExpandingWebAiLogInterfaceFactory $aiLogFactory
    ) {
        $this->resource = $resource;
        $this->aiLogFactory = $aiLogFactory;
    }

    /**
     * @inheritDoc
     */
    public function save(ExpandingWebAiLogInterface $model): ExpandingWebAiLogInterface
    {
        try {
            $this->resource->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $value): ExpandingWebAiLogInterface
    {
        $model = $this->aiLogFactory->create();
        $this->resource->load($model, $value);
        if (!$model->getId()) {
            throw new NoSuchEntityException(
                __('The record with the "%1" ID doesn\'t exist.', $value)
            );
        }

        return $model;
    }
}
