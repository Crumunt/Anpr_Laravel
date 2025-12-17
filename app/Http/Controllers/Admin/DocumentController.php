<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    //
    public function view(Documents $document)
    {
        if (!Storage::exists($document->file_path)) {
            abort(404, "Document not found");
        }

        $path = Storage::path($document->file_path);
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $document_name =
            Str::of($document->type)->headline()->value() . "." . $extension;

        // Use stored mime type or detect it
        $mimeType =
            $document->mime_type ?? Storage::mimeType($document->file_path);

        return response()->file($path, [
            "Content-Type" => $mimeType,
            "Content-Disposition" =>
                'inline; filename="' . $document_name . '"',
        ]);
    }
}
