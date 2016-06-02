<?php
namespace IPHP\View;

use IPHP\Http\Response as HttpResponse;

abstract class Response {
    protected $vars = [];
	protected $strategy = \IPHP\App\View\ViewStrategy::class;
	protected $compileable = true;
    
    public function __construct (array $vars = []) {
        $this->httpResponse = new HttpResponse;
		
		$this->vars = $vars;
    }
	
    public function getInjectedVars ():array {
		return $this->vars;
	}
    
    public function setVar ($key, $value) {
		$this->vars[$key] = $value;

		return $this;
	}
    
    public function getHttpResponse (): HttpResponse {
		return $this->httpResponse;
	}
	
	public function setStrategy (string $strategy) {
		$this->strategy = $strategy;
	}
        
    public function getStrategy (): string {
		return $this->strategy;
	}
	
	public function compileable ():bool {
		return $this->compileable;
	}
	
	public function setCompileable (bool $compileable) {
		$this->compileable = $compileable;
	}
}