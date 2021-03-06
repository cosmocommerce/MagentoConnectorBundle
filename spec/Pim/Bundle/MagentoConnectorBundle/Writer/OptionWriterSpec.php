<?php

namespace spec\Pim\Bundle\MagentoConnectorBundle\Writer;

use Pim\Bundle\MagentoConnectorBundle\Guesser\WebserviceGuesser;
use Pim\Bundle\MagentoConnectorBundle\Webservice\Webservice;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionWriterSpec extends ObjectBehavior
{
    function let(
        WebserviceGuesser $webserviceGuesser,
        Webservice $webservice,
        StepExecution $stepExecution
    ) {
        $webserviceGuesser->getWebservice(Argument::any())->willReturn($webservice);

        $this->beConstructedWith($webserviceGuesser);
        $this->setStepExecution($stepExecution);
    }

    function it_calls_soap_client_to_create_options($webservice)
    {
        $webservice->createOption(array('foo'))->shouldBeCalled();
        $webservice->createOption(array('bar'))->shouldBeCalled();

        $this->write(array(array(array('foo'), array('bar'))));
    }
}
