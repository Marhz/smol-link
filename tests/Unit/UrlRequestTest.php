<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\UrlRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->request = new UrlRequest();
    }
    
    protected function getRulesForMethod($method)
    {
        $this->request->setMethod($method);
        return $this->request->rules();
    }
    /**
     * @test
     */
    function it_accepts_multiple_valid_url_formats()
    {
        $formats = [
            'google.com',
            'http://google.com',
            'https://google.com',
            'http://www.google.com',
            'https://www.google.com',
            'www.google.com',
            'ftp://google.com',
            'ftps://google.com',
            "google.com?param=value",
            "google.com#div",
        ];
        $rules = $this->getRulesForMethod('post');
        foreach($formats as $format) {
            $validator = Validator::make(['url' => $format], $rules);
            $this->assertTrue($validator->passes(), $format);
        }
    }
    
    /**
     * @test
     */
    function it_refuses_non_valid_url_formats()
    {
        $formats = [
            'google',
        ];
        $rules = $this->getRulesForMethod('post');
        foreach($formats as $format) {
            $validator = Validator::make(['url' => $format], $rules);
            $this->assertTrue($validator->fails(), $format);
        }
    }
}
