<?php
namespace ExpandingWeb\Ai\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use ExpandingWeb\Ai\Api\Data\ExpandingWebAiLogInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @api
 */
interface ExpandingWebAiLogRepositoryInterface
{
    /**
     * Save
     *
     * @param ExpandingWebAiLogInterface $model
     *
     * @return ExpandingWebAiLogInterface
     *
     * @throws CouldNotSaveException
     */
    public function save(ExpandingWebAiLogInterface $model): ExpandingWebAiLogInterface;

    /**
     * Save
     *
     * @param int $value
     *
     * @return ExpandingWebAiLogInterface
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $value): ExpandingWebAiLogInterface;
}
