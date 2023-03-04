<?php
declare(strict_types=1);

namespace ExpandingWeb\Ai\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ExpandingWebAiLogInterface extends ExtensibleDataInterface
{
    public const TABLE_NAME = 'expandingweb_ai_log';
    public const ID = 'id';
    public const REQUEST = 'request';
    public const RESPONSE = 'response';
    public const CREATED_AT = 'created_at';

    /**
     * Get Log Id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Get Request
     *
     * @return string
     */
    public function getRequest(): string;

    /**
     * Set Request
     *
     * @param string $value
     *
     * @return ExpandingWebAiLogInterface
     */
    public function setRequest(string $value): ExpandingWebAiLogInterface;

    /**
     * Get Response
     *
     * @return string
     */
    public function getResponse(): string;

    /**
     * Set Response
     *
     * @param string $value
     *
     * @return ExpandingWebAiLogInterface
     */
    public function setResponse(string $value): ExpandingWebAiLogInterface;

    /**
     * Get date create
     *
     * @return string
     */
    public function getCreatedAt(): string;
}
