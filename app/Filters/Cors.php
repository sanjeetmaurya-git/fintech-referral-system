public function before(RequestInterface $request, $arguments = null)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

    if ($request->getMethod() === 'options') {
        exit(0);
    }
}
