<?php

namespace App\Support;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class VercelBlobUploader
{
    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        $token = config('services.vercel_blob.token');

        if (! $token) {
            return $file->store($directory, 'public');
        }

        $pathname = $this->pathname($file, $directory);

        $response = (new Client([
            'base_uri' => rtrim(config('services.vercel_blob.api_url', 'https://vercel.com/api/blob'), '/').'/',
            'timeout' => 30,
        ]))->put('', [
            'query' => ['pathname' => $pathname],
            'headers' => [
                'authorization' => 'Bearer '.$token,
                'x-api-version' => '12',
                'x-vercel-blob-access' => 'public',
                'x-add-random-suffix' => '1',
                'x-content-type' => $file->getMimeType() ?: 'application/octet-stream',
                'x-content-length' => (string) $file->getSize(),
            ],
            'body' => fopen($file->getRealPath(), 'rb'),
        ]);

        $payload = json_decode((string) $response->getBody(), true);

        if (! is_array($payload) || empty($payload['url'])) {
            throw new RuntimeException('Vercel Blob upload did not return a public URL.');
        }

        return $payload['url'];
    }

    private function pathname(UploadedFile $file, string $directory): string
    {
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'asset';

        return trim($directory, '/').'/'.$filename.'.'.$extension;
    }
}
