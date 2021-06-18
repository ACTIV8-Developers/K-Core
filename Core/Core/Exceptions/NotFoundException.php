<?php
namespace Core\Core\Exceptions;

/**
 * Class NotFoundException.
 *
 * @author <milos@activ8.rs>
 */
class NotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $notFoundMessage = '<h1>404 Not Found</h1>The page that you have requested could not be found.';

    /**
     * @param null $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null) {
        if ($message === null) {
            $message = $this->notFoundMessage;
        }

        parent::__construct($message, $code, $previous);
    }
}