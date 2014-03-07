<?php

namespace Pim\Bundle\MagentoConnectorBundle\Mapper;

use Pim\Bundle\MagentoConnectorBundle\Guesser\WebserviceGuesser;
use Pim\Bundle\MagentoConnectorBundle\Validator\Constraints\HasValidCredentialsValidator;

/**
 * Magento attribute mapper
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagentoAttributeMapper extends Mapper
{
    /**
     * @var WebserviceGuesser
     */
    protected $webserviceGuesser;

    /**
     * @param HasValidCredentialsValidator $hasValidCredentialsValidator
     * @param boolean                      $allowAddition
     * @param WebserviceGuesser            $webserviceGuesser
     */
    public function __construct(
        HasValidCredentialsValidator $hasValidCredentialsValidator,
        $allowAddition,
        WebserviceGuesser $webserviceGuesser
    ) {
        parent::__construct($hasValidCredentialsValidator, $allowAddition);

        $this->webserviceGuesser = $webserviceGuesser;
    }

    /**
     * Get mapping
     * @return array
     */
    public function getMapping()
    {
        if (!$this->isValid()) {
            return new MappingCollection();
        } else {
            $attributes = $this->webserviceGuesser->getWebservice($this->clientParameters)->getAllAttributes();

            $mapping = new MappingCollection();
            foreach (array_keys($attributes) as $attributeCode) {
                if (in_array($attributeCode, $this->mandatoryAttributes())) {
                    $mapping->add(
                        array(
                            'source'    => $attributeCode,
                            'target'    => $attributeCode,
                            'deletable' => false
                        )
                    );
                }
            }

            return $mapping;
        }
    }

    /**
     * Get all targets
     * @return array
     */
    public function getAllTargets()
    {
        $targets = array();

        if ($this->isValid()) {
            $attributeCodes = array_keys(
                $this->webserviceGuesser->getWebservice($this->clientParameters)->getAllAttributes()
            );

            foreach ($attributeCodes as $attributeCode) {
                $targets[] = array('id' => $attributeCode, 'text' => $attributeCode);
            }
        }

        return array('targets' => $targets, 'allowAddition' => $this->allowAddition);
    }

    /**
     * Get mapper identifier
     * @param string $rootIdentifier
     *
     * @return string
     */
    public function getIdentifier($rootIdentifier = 'attribute')
    {
        return parent::getIdentifier($rootIdentifier);
    }

    /**
     * Get mandatory attributes
     * @return array
     */
    protected function mandatoryAttributes()
    {
        return array(
            'name',
            'price',
            'description',
            'short_description',
            'tax_class_id',
        );
    }
}
