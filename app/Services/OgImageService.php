<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OgImageService
{
    private int $width = 1200;
    private int $height = 630;
    private string $fontPath;

    public function __construct()
    {
        // Use system font or provide custom font path
        $this->fontPath = storage_path('fonts/arial.ttf');
        
        // Fallback to system font if custom font doesn't exist
        if (!file_exists($this->fontPath)) {
            $this->fontPath = $this->getSystemFont();
        }
    }

    /**
     * Generate OG image for post
     */
    public function generateImage(string $title, string $slug): string
    {
        // Check if image already exists
        $filename = $slug . '.jpg';
        $path = 'og-images/' . $filename;
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        // Create image
        $image = \imagecreatetruecolor($this->width, $this->height);
        
        // Create gradient background (blue gradient)
        $this->createGradientBackground($image);
        
        // Add logo/branding
        $this->addBranding($image);
        
        // Add title text
        $this->addTitle($image, $title);
        
        // Save image
        $tempPath = storage_path('app/temp/' . $filename);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        \imagejpeg($image, $tempPath, 90);
        \imagedestroy($image);
        
        // Move to public storage
        Storage::disk('public')->put($path, file_get_contents($tempPath));
        unlink($tempPath);
        
        return Storage::disk('public')->url($path);
    }

    /**
     * Create gradient background
     */
    private function createGradientBackground($image): void
    {
        // Blue gradient from #1e3a8a to #3b82f6
        $startColor = ['r' => 30, 'g' => 58, 'b' => 138];
        $endColor = ['r' => 59, 'g' => 130, 'b' => 246];
        
        for ($y = 0; $y < $this->height; $y++) {
            $ratio = $y / $this->height;
            
            $r = $startColor['r'] + ($endColor['r'] - $startColor['r']) * $ratio;
            $g = $startColor['g'] + ($endColor['g'] - $startColor['g']) * $ratio;
            $b = $startColor['b'] + ($endColor['b'] - $startColor['b']) * $ratio;
            
            $color = \imagecolorallocate($image, (int)$r, (int)$g, (int)$b);
            \imagefilledrectangle($image, 0, $y, $this->width, $y + 1, $color);
        }
    }

    /**
     * Add branding/logo
     */
    private function addBranding($image): void
    {
        $white = \imagecolorallocate($image, 255, 255, 255);
        
        // Add "JobOne.in" branding at top
        $brandText = 'JobOne.in';
        $fontSize = 32;
        
        if (function_exists('imagettftext') && file_exists($this->fontPath)) {
            $bbox = \imagettfbbox($fontSize, 0, $this->fontPath, $brandText);
            $textWidth = abs($bbox[4] - $bbox[0]);
            $x = ($this->width - $textWidth) / 2;
            $y = 80;
            
            \imagettftext($image, $fontSize, 0, (int)$x, (int)$y, $white, $this->fontPath, $brandText);
        } else {
            // Fallback to built-in font
            $x = ($this->width - (strlen($brandText) * 10)) / 2;
            \imagestring($image, 5, (int)$x, 50, $brandText, $white);
        }
        
        // Add tagline
        $tagline = 'Latest Government Jobs 2026';
        $taglineFontSize = 20;
        
        if (function_exists('imagettftext') && file_exists($this->fontPath)) {
            $bbox = \imagettfbbox($taglineFontSize, 0, $this->fontPath, $tagline);
            $textWidth = abs($bbox[4] - $bbox[0]);
            $x = ($this->width - $textWidth) / 2;
            $y = 120;
            
            \imagettftext($image, $taglineFontSize, 0, (int)$x, (int)$y, $white, $this->fontPath, $tagline);
        } else {
            $x = ($this->width - (strlen($tagline) * 8)) / 2;
            \imagestring($image, 4, (int)$x, 90, $tagline, $white);
        }
    }

    /**
     * Add title text
     */
    private function addTitle($image, string $title): void
    {
        $white = \imagecolorallocate($image, 255, 255, 255);
        
        // Wrap text to fit width
        $maxWidth = $this->width - 120; // 60px padding on each side
        $fontSize = 48;
        $lines = $this->wrapText($title, $maxWidth, $fontSize);
        
        // Limit to 4 lines
        $lines = array_slice($lines, 0, 4);
        
        // Calculate starting Y position to center text vertically
        $lineHeight = 70;
        $totalHeight = count($lines) * $lineHeight;
        $startY = ($this->height - $totalHeight) / 2 + 150;
        
        if (function_exists('imagettftext') && file_exists($this->fontPath)) {
            foreach ($lines as $index => $line) {
                $bbox = \imagettfbbox($fontSize, 0, $this->fontPath, $line);
                $textWidth = abs($bbox[4] - $bbox[0]);
                $x = ($this->width - $textWidth) / 2;
                $y = $startY + ($index * $lineHeight);
                
                \imagettftext($image, $fontSize, 0, (int)$x, (int)$y, $white, $this->fontPath, $line);
            }
        } else {
            // Fallback to built-in font
            foreach ($lines as $index => $line) {
                $x = ($this->width - (strlen($line) * 12)) / 2;
                $y = $startY + ($index * 40);
                \imagestring($image, 5, (int)$x, (int)$y, $line, $white);
            }
        }
    }

    /**
     * Wrap text to fit width
     */
    private function wrapText(string $text, int $maxWidth, int $fontSize): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        
        foreach ($words as $word) {
            $testLine = $currentLine . ($currentLine ? ' ' : '') . $word;
            
            if (function_exists('imagettfbbox') && file_exists($this->fontPath)) {
                $bbox = \imagettfbbox($fontSize, 0, $this->fontPath, $testLine);
                $width = abs($bbox[4] - $bbox[0]);
            } else {
                $width = strlen($testLine) * 12; // Approximate width
            }
            
            if ($width > $maxWidth && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }
        
        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }
        
        return $lines;
    }

    /**
     * Get system font path
     */
    private function getSystemFont(): string
    {
        $possiblePaths = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf', // Linux
            '/System/Library/Fonts/Helvetica.ttc', // macOS
            'C:\Windows\Fonts\arial.ttf', // Windows
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Return empty string if no font found (will use built-in font)
        return '';
    }

    /**
     * Delete OG image
     */
    public function deleteImage(string $slug): bool
    {
        $path = 'og-images/' . $slug . '.jpg';
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}
