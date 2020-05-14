<?php
namespace  Boxalino\IntelligenceIntegration\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

/**
 * Class ExportFull
 * Set the interval to trigger the full export (recommended - 1 day)
 *
 * @package Boxalino\IntelligenceFramework\ScheduledTask
 */
class ExporterFull extends ScheduledTask
{

    public static function getTaskName(): string
    {
        return 'boxalino.export.full';
    }

    /**
     * The full data synchronization is triggered once per day
     *
     * @return int
     */
    public static function getDefaultInterval(): int
    {
        return 200; // 1day
    }

}
