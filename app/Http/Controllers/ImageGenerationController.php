<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePromptRequest;
use App\Services\GeminiService;
use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageGenerationController extends Controller
{
    //
    public function __construct(private GeminiService $geminiService) {}
    // public function __construct(private OpenAiService $openAiService) {}

    public function index() {}

    public function store(GeneratePromptRequest $request)
    {
        $user = $request->user();
        $image = $request->file('image');

        $originalName = $image->getClientOriginalName();
        $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extention = $image->getClientOriginalExtension();
        $safeFilename = $sanitizedName . '_' . Str::random(10) . '.' . $extention;


        $imagePath = $image->storeAs('uploads/images', $safeFilename, 'public');


        $base64Image = base64_encode(file_get_contents($image->getPathname()));


        $generatedPrompt = $this->geminiService->generatePromptFromImage(
            file_get_contents($image->getPathname())
        );

        $imageGeneration = $user->imageGenerations()->create([
            'image_path' => $imagePath,
            'generated_promt' => $generatedPrompt,
            'original_filename' => $originalName,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType()
        ], 201);
    }
}
