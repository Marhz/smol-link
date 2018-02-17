<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use App\Http\Requests\UrlRequest;
use Illuminate\Support\Facades\Redirect;

class UrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('update');
    }
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
    public function store(UrlRequest $request)
    {
        $data = $request->only('url');
        $data['url'] = $this->normalizeUrl($data['url']);
        if(auth()->guest()) {
            $url = Url::where('url', $data['url'])->first();
            if(!$url) {
                $url = Url::create($data);
            }
        } else {
            $data['user_id'] = auth()->id();
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

    public function update(UrlRequest $request, Url $url)
    {
        $this->authorize('update', $url);
        $data = $request->only(['url', 'label', 'slug']);
        // if($request->has('slug'))
            // dd($data);
        $url->update($data);
        return $url;
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
}
