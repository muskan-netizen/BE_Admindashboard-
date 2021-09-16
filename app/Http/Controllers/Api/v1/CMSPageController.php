<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class CMSPageController extends Controller
{

    use ApiResponser;

    public function getPageList(Request $request)
    {
        // $pages = Page::select('id', 'slug')->with(array('primary' => function($query) {
        //             $query->where('is_published', 1);
        //             }))->latest('id')->get();
        // foreach ($pages as $page) {
        //     $page->title = $page->primary->title;
        //     unset($page->primary);
        // }
        // return $this->successResponse($pages, '', 201);

        $locallanguage = ($request->hasHeader('language')) ? $request->header('language') : 1;
        $pages = Page::leftJoin('page_translations', function ($join) {
            $join->on('pages.id', '=', 'page_translations.page_id');
        })
            ->where(['page_translations.language_id' => $locallanguage, 'page_translations.is_published' => 1])
            ->orderBy('pages.id', 'Desc')
            ->get([
                'page_translations.id',
                'pages.slug',
                'page_translations.title',
            ]);
        return $this->successResponse($pages, '', 201);
    }

    public function getPageDetail(Request $request)
    {
        $page_id = $request->page_id ? $request->page_id : 3;
        // $page = Page::select('id', 'slug')->with(['primary' => function($query) {
        //             $query->where('is_published', 1);
        //         }])->where('id', $page_id)->firstOrFail();
        // $page->title = $page->primary->title;
        // $page->description = $page->primary->description;
        // unset($page->primary);
        $page = PageTranslation::leftJoin('pages', function ($join) {
            $join->on('pages.id', '=', 'page_translations.page_id');
        })
            ->where(['page_translations.id' => $page_id, 'page_translations.is_published' => 1])
            ->first([
                'page_translations.id',
                'pages.slug',
                'page_translations.title',
                'page_translations.description',
            ]);
        return $this->successResponse($page, '', 201);
    }
}
