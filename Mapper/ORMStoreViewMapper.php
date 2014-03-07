<?php

namespace Pim\Bundle\MagentoConnectorBundle\Mapper;

use Pim\Bundle\MagentoConnectorBundle\Manager\SimpleMappingManager;
use Pim\Bundle\MagentoConnectorBundle\Manager\LocaleManager;
use Pim\Bundle\MagentoConnectorBundle\Validator\Constraints\HasValidCredentialsValidator;

/**
 * Magento storeview mapper
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ORMStoreViewMapper extends ORMMapper
{
    /**
     * @var LocaleManager
     */
    protected $localeManager;

    /**
     * @param HasValidCredentialsValidator $hasValidCredentialsValidator
     * @param boolean                      $allowAddition
     * @param SimpleMappingManager         $simpleMappingManager
     * @param string                       $rootIdentifier
     * @param LocaleManager                $localeManager
     */
    public function __construct(
        HasValidCredentialsValidator $hasValidCredentialsValidator,
        $allowAddition,
        SimpleMappingManager $simpleMappingManager,
        $rootIdentifier,
        LocaleManager $localeManager
    ) {
        parent::__construct($hasValidCredentialsValidator, $allowAddition, $simpleMappingManager, $rootIdentifier);

        $this->localeManager = $localeManager;
    }

    /**
     * Get all sources
     * @return array
     */
    public function getAllSources()
    {
        $sources = array();

        if ($this->isValid()) {
            $codes = $this->localeManager->getActiveCodes();

            foreach ($codes as $code) {
                $sources[] = array('id' => $code, 'text' => $code);
            }
        }

        return array('sources' => $sources);
    }
}
