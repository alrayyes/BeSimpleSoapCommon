<?php

/*
 * This file is part of the BeSimpleSoapBundle.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BeSimple\Tests\SoapCommon\Soap;

use BeSimple\Tests\SoapCommon\Fixtures\SoapBuilder;
use BeSimple\SoapCommon\Cache;

class AbstractSoapBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $defaultOptions = array(
        'features' => 0,
    );

    public function testContruct()
    {
        $options = $this
            ->getSoapBuilder()
            ->getOptions()
        ;

        $this->assertEquals($this->mergeOptions(array()), $options);
    }

    public function testWithWsdl()
    {
        $builder = $this->getSoapBuilder();
        $this->assertNull($builder->getWsdl());

        $builder->withWsdl('http://myWsdl/?wsdl');
        $this->assertEquals('http://myWsdl/?wsdl', $builder->getWsdl());
    }

    public function testWithSoapVersion()
    {
        $builder = $this->getSoapBuilder();

        $builder->withSoapVersion11();
        $this->assertEquals($this->mergeOptions(array('soap_version' => SOAP_1_1)), $builder->getOptions());

        $builder->withSoapVersion12();
        $this->assertEquals($this->mergeOptions(array('soap_version' => SOAP_1_2)), $builder->getOptions());
    }

    public function testWithEncoding()
    {
        $builder = $this
            ->getSoapBuilder()
            ->withEncoding('ISO 8859-15')
        ;

        $this->assertEquals($this->mergeOptions(array('encoding' => 'ISO 8859-15')), $builder->getOptions());
    }

    public function testWithWsdlCache()
    {
        $builder = $this->getSoapBuilder();

        $builder->withWsdlCacheNone();
        $this->assertEquals($this->mergeOptions(array('cache_wsdl' => Cache::TYPE_NONE)), $builder->getOptions());

        $builder->withWsdlCacheDisk();
        $this->assertEquals($this->mergeOptions(array('cache_wsdl' => Cache::TYPE_DISK)), $builder->getOptions());

        $builder->withWsdlCacheMemory();
        $this->assertEquals($this->mergeOptions(array('cache_wsdl' => Cache::TYPE_MEMORY)), $builder->getOptions());

        $builder->withWsdlCacheDiskAndMemory();
        $this->assertEquals($this->mergeOptions(array('cache_wsdl' => Cache::TYPE_DISK_MEMORY)), $builder->getOptions());
    }

    public function testWithSingleElementArrays()
    {
        $options = $this
            ->getSoapBuilder()
            ->withSingleElementArrays()
            ->getOptions()
        ;

        $this->assertEquals($this->mergeOptions(array('features' => SOAP_SINGLE_ELEMENT_ARRAYS)), $options);
    }

    public function testWithWaitOneWayCalls()
    {
        $options = $this
            ->getSoapBuilder()
            ->withWaitOneWayCalls()
            ->getOptions()
        ;

        $this->assertEquals($this->mergeOptions(array('features' => SOAP_WAIT_ONE_WAY_CALLS)), $options);
    }

    public function testWithUseXsiArrayType()
    {
        $options = $this
            ->getSoapBuilder()
            ->withUseXsiArrayType()
            ->getOptions()
        ;

        $this->assertEquals($this->mergeOptions(array('features' => SOAP_USE_XSI_ARRAY_TYPE)), $options);
    }

    public function testFeatures()
    {
        $builder  = $this->getSoapBuilder();
        $features = 0;

        $builder->withSingleElementArrays();
        $features |= SOAP_SINGLE_ELEMENT_ARRAYS;
        $this->assertEquals($this->mergeOptions(array('features' => $features)), $builder->getOptions());

        $builder->withWaitOneWayCalls();
        $features |= SOAP_WAIT_ONE_WAY_CALLS;
        $this->assertEquals($this->mergeOptions(array('features' => $features)), $builder->getOptions());

        $builder->withUseXsiArrayType();
        $features |= SOAP_USE_XSI_ARRAY_TYPE;
        $this->assertEquals($this->mergeOptions(array('features' => $features)), $builder->getOptions());
    }

    public function testCreateWithDefaults()
    {
        $builder = SoapBuilder::createWithDefaults();

        $this->assertInstanceOf('BeSimple\Tests\SoapCommon\Fixtures\SoapBuilder', $builder);

        $this->assertEquals($this->mergeOptions(array('soap_version' => SOAP_1_2, 'encoding' => 'UTF-8', 'features' => SOAP_SINGLE_ELEMENT_ARRAYS)), $builder->getOptions());
    }

    private function getSoapBuilder()
    {
        return new SoapBuilder();
    }

    private function mergeOptions(array $options)
    {
        return array_merge($this->defaultOptions, $options);
    }
}