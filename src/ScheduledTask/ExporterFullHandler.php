<?php
namespace  Boxalino\IntelligenceIntegration\ScheduledTask;

use Boxalino\IntelligenceFramework\Service\Exporter\ExporterFull;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

/**
 * Class ExportFullHandler
 * @package Boxalino\IntelligenceFramework\ScheduledTask
 */
class ExporterFullHandler extends ScheduledTaskHandler
{
    /**
     * @var ExporterFull
     */
    protected $exporterFull;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $account = null;

    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        LoggerInterface $logger,
        ExporterFull $fullExporter
    ){
        parent::__construct($scheduledTaskRepository);
        $this->exporterFull = $fullExporter;
        $this->logger = $logger;
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [ExporterFull::class];
    }

    /**
     * Triggers the full data exporter for a specific account if so it is set
     *
     * @throws \Exception
     */
    public function run(): void
    {
        if(!is_null($this->account))
        {
            $this->exporterFull->setAccount($this->account);
        }
        try{
            $this->exporterFull->export();
        } catch (\Exception $exc)
        {
            $this->logger->error($exc->getMessage());
            throw $exc;
        }
    }

    /**
     * Sets an account via XML declaration
     *
     * @param string $account
     * @return $this
     */
    public function setAccount(string $account)
    {
        $this->account = $account;
        return $this;
    }

}
