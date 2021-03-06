<?php
namespace Payum\Core\Tests\Action;

use Payum\Core\Action\GetCurrencyAction;
use Payum\Core\Request\GetCurrency;
use Payum\Core\Tests\GenericActionTest;

class GetCurrencyActionTest extends GenericActionTest
{
    protected $requestClass = 'Payum\Core\Request\GetCurrency';

    protected $actionClass = 'Payum\Core\Action\GetCurrencyAction';

    public function provideSupportedRequests(): \Iterator
    {
        yield array(new $this->requestClass('USD'));
        yield array(new $this->requestClass('EUR'));
    }

    public function provideNotSupportedRequests(): \Iterator
    {
        yield array('foo');
        yield array(array('foo'));
        yield array(new \stdClass());
        yield array($this->getMockForAbstractClass('Payum\Core\Request\Generic', array(array())));
    }

    /**
     * @test
     */
    public function shouldSetCurrencyByAlpha3()
    {
        $action = new GetCurrencyAction();

        $action->execute($getCurrency = new GetCurrency('USD'));

        $this->assertEquals('USD', $getCurrency->alpha3);
    }

    /**
     * @test
     */
    public function shouldSetCurrencyByNumeric()
    {
        $action = new GetCurrencyAction();

        $action->execute($getCurrency = new GetCurrency($euro = 978));

        $this->assertEquals('EUR', $getCurrency->alpha3);
    }

    /**
     * @test
     */
    public function throwsIfCurrencyNotSupported()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('ISO 4217 does not contain: 000');
        $action = new GetCurrencyAction();

        $action->execute($getCurrency = new GetCurrency('000'));
    }
}
