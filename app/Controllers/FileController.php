<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class FileController extends BaseController
{
    /**
     * Serve files securely from the writable/uploads directory
     */
    public function serve(...$segments)
    {
        $path = implode('/', $segments);

        // Add basic security against directory traversal
        if (strpos($path, '..') !== false) {
            throw PageNotFoundException::forPageNotFound();
        }

        $fullPath = WRITEPATH . 'uploads/' . $path;

        if (!is_file($fullPath)) {
            throw PageNotFoundException::forPageNotFound("Cannot find file: " . $path);
        }

        // Guess the mime type natively
        $mime = mime_content_type($fullPath);
        if (!$mime) {
            $mime = 'application/octet-stream';
        }

        // Output explicitly inline
        $this->response->setContentType($mime)
                       ->setHeader('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"')
                       ->setHeader('Cache-Control', 'max-age=86400, public');

        // Note: Using setBody with file_get_contents works well for images/PDFs
        $this->response->setBody(file_get_contents($fullPath));
        
        return $this->response;
    }
}
