<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RateLimiter implements FilterInterface
{
    /**
     * This filter applies rate limiting to incoming requests.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // Limit to 10 requests per minute per IP address
        // Using the client's IP as the throttle key
        if ($throttler->check($request->getIPAddress(), 10, MINUTE) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setJSON(['error' => 'Too Many Requests', 'message' => 'Please wait a minute before trying again.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
