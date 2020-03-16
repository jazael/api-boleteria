<?php
/**
 * Created by PhpStorm.
 * User: jazael.faubla
 * Date: 14/03/20
 * Time: 22:50
 */
namespace App\Modules\Ticket\Model;

use App\Core\Utilities\Response,
    DateTimeZone, DateTime,
    \Exception;

class Ticket {
    private $db;
    private $logger;
    private $timeZone;
    private $currentDate;
    private $response;
    private $result;
    private $status;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
        $this->response = new Response();
        $this->timeZone = new DateTimeZone("America/Guayaquil");
        $this->currentDate = new DateTime("now", $this->timeZone);
    }

}