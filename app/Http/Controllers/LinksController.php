<?php

namespace App\Http\Controllers;

use App\Link;
use Validator;
use App\Transformers\LinkTransformer;
use Illuminate\Http\Request;

class LinksController extends ApiController
{
    /**
     * @var App\Transformer\LinkTransformer
     */
    protected $linkTransformer;

    public function __construct(LinkTransformer $linkTransformer)
    {
        $this->linkTransformer = $linkTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 100);
        $links = Link::paginate($limit);

        return $this->respondWithPagination($links, [
            'data' => $this->linkTransformer->transformCollection($links->all())
        ]);
    }

    /**
     * Make a new shortened url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function make(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return $this->respondUnprocessableEntity($validator->errors()); 
        }

        $url = $request->input('url');
        $code = null;

        $link = Link::where('url', $url)->first();

        if (count($link) > 0) {
            $code = $link->code;
        } else {
            $created = Link::create([
                'url' => $url,
                'code' => ''
            ]);

            if ($created) {
                $code = str_random(6);
                Link::where('id', $created->id)->update(['code' => $code]);
            }
        }

        $shortenedUrl = route('get', ['code' => $code]);

        return $this->respondOk($shortenedUrl);
    }

    /**
     * Redirects to the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function get($code)
    {
        $link = Link::where('code', $code)->first();
        if (count($link) > 0) {
            return redirect($link->url);
        }
        
        return redirect('/');
    }
}
