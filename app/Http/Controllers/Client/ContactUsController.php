<?php

namespace App\Http\Controllers\Client;

use DataTables;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Http\Controllers\Client\BaseController;

class ContactUsController extends BaseController
{
    /** Words per line (between 5–10 as requested). */
    private const MESSAGE_WORDS_PER_LINE = 7;

    public function index()
    {
        return view('backend.contact-us.index');
    }

    public function data(Request $request)
    {
        $query = ContactUs::query()
            ->select(['id', 'name', 'email', 'phone_number', 'message', 'created_at'])
            ->orderByDesc('id');

        return DataTables::of($query)
            ->editColumn('name', function ($row) {
                return e((string) $row->name);
            })
            ->editColumn('email', function ($row) {
                return e((string) $row->email);
            })
            ->editColumn('phone_number', function ($row) {
                return e((string) ($row->phone_number ?? ''));
            })
            ->editColumn('message', function ($row) {
                return $this->formatMessageMultiline($row->message, self::MESSAGE_WORDS_PER_LINE);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
            })
            ->rawColumns(['message'])
            ->make(true);
    }

    /**
     * Break message into lines of N words so long text wraps in the table.
     */
    private function formatMessageMultiline(?string $text, int $wordsPerLine): string
    {
        $text = strip_tags((string) $text);
        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        if ($words === false || $words === []) {
            return '';
        }
        $chunks = array_chunk($words, max(1, $wordsPerLine));
        $lines = array_map(static function (array $chunk) {
            return e(implode(' ', $chunk));
        }, $chunks);

        return '<div class="contact-us-message-wrap">' . implode('<br>', $lines) . '</div>';
    }
}
