<?php
namespace  Boxalino\IntelligenceIntegration\ScheduledTask;

use Boxalino\IntelligenceFramework\ScheduledTask\ExporterFullHandlerAbstract;

/**
 * Class ExportFullHandler
 * @package Boxalino\IntelligenceIntegration\ScheduledTask
 */
class ExporterFullHandler extends ExporterFullHandlerAbstract
{

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [ExporterFull::class];
    }

}
