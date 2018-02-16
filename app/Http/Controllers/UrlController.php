<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'regex:' . $this->urlRegex()]
        ]);
        $data['url'] = $this->normalizeUrl($data['url']);
        $url = Url::where('url', $data['url'])->first();
        if(! $url){
            $url = Url::create($data);
        }
        return $url;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function show(Url $url)
    {
        $url->visits()->create();
        return redirect()->to('http://' . $url->url);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Url  $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {
        //
    }

    protected function normalizeUrl($url)
    {
        $url = trim($url, '/');
        $url = preg_replace('/^https?:\/\//', '', $url);
        $url = preg_replace('/^www./', '', $url);
        return $url;
    }

    protected function urlRegex()
    {
        return '#^(((https?|ftp)://)?(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
    }
}
