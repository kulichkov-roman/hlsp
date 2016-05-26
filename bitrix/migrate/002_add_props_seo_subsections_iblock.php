<?php
/**
 * Миграция добавляет свойства к инфоблоку «Обратная связь»
 */
ignore_user_abort(true);
set_time_limit(0);

define('BX_BUFFER_USED', true);
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);

if (!defined('SITE_ID')) {
    define('SITE_ID', 's1');
}

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['HTTP_HOST'] = 'dev.hollyshop.ru';
    $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../');
}

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set('display_errors', 1);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

if (!CModule::IncludeModule('iblock')) {
    echo 'Unable to include iblock module';
    exit;
}

use Your\Tools\Data\Migration\Bitrix\AbstractIBlockPropertyMigration;
use Your\Environment\EnvironmentManager;

/**
 * Class AddPropertiesToSeoSubsectionsIBlockMigration
 */
class AddPropertiesToSeoSubsectionsIBlockMigration extends AbstractIBlockPropertyMigration
{
    /**
     * @var array
     */
    protected $properties;

    public function __construct()
    {

        $environment = \Your\Environment\EnvironmentManager::getInstance();

        $iBlockId = $environment->get('seoSubsectionsIBlock');

        parent::__construct($iBlockId);

        $this->properties = array(
            'LEVEL' => 'Уровень вложенности'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $logger = new \Your\Tools\Logger\EchoLogger();
        try {
            $this->createLinkProperty(
                'Привязка к разделам каталога',
                'LINK_SECTION_CAT',
                array(
                    'PROPERTY_TYPE' => 'G'
                ),
                11
            );

            $this->createLinkProperty(
                'Привязка к элементам каталога',
                'LINK_ELEMENTS_CAT',
                array(
                    'PROPERTY_TYPE' => 'E',
                    'MULTIPLE' => 'Y'
                ),
                11
            );

            $this->createCheckboxProperty(
                'Основной URL',
                'GEN_URL'
            );

            foreach ($this->properties as $code => $name) {
                $arAdditionalFields = array(
                    'ACTIVE' => 'Y',
                    'SEARCHABLE' => 'N',
                    'FILTRABLE' => 'N',
                    'MULTIPLE' => 'N',
                    'IBLOCK' => 11
                );

                $arValues = array(
                    Array(
                        "VALUE" => "1",
                        "SORT" => "100",
                    ),
                    Array(
                        "VALUE" => "2",
                        "SORT" => "200",
                    ),
                );

                $this->createSelectProperty(
                    $name,
                    $code,
                    $arAdditionalFields,
                    $arValues
                );

                echo sprintf('Property "%s" has been added', $code) . PHP_EOL;
            }

            $logger->log('Properties have been created successfully');
        } catch (\Your\Exception\Data\Migration\MigrationException $exception) {
            $logger->log(sprintf('ERROR: %s', $exception->getMessage()));
        }
    }

    /**
     * @throws \Your\Exception\Common\NotImplementedException
     */
    public function down()
    {
        throw new \Your\Exception\Common\NotImplementedException('Method down was not implemented');
    }
}

$iBlockMigrations = new AddPropertiesToSeoSubsectionsIBlockMigration(
    $environment->get('seoSubsectionsIBlock')
);

$iBlockMigrations->up();
