<?php

class BaseController
{

    /**
     * Get Data post method
     *
     * @Response = (Object Of Parameter input)
     */
    public function getData()
    {
        $data = file_get_contents('php://input');
        return json_decode($data);
    }

    /**
     * Get Data post method
     *
     * @Response = (Object Of Parameter input)
     */
    public function getDataQuery()
    {
        $data = $_GET;
        return (object) $data;
    }


    /**
     * Error method
     *
     * Throw error with bad request code
     * @Parameter = ("msg", type="string", required=true, description="content information why throw error")
     * @Response = ({
     *      message: "message",
     *      status_code: 400,
     *      debug: array of stack trace
     * })
     */
    public function badRequest($msg)
    {
        return $this->baseResult($msg, 400);
    }

    /**
     * Error method
     *
     * Throw error with not found code
     * @Parameter = ("msg", type="string", required=true, description="content information why throw error")
     * @Response = ({
     *      message: "message",
     *      status_code: 404,
     *      debug: array of stack trace
     * })
     */
    public function notFound($msg)
    {
        return $this->baseResult($msg, 404);
    }

    /**
     * Error method
     *
     * Throw error with forbidden access code
     * @Parameter = ("msg", type="string", required=true, description="content information why throw error")
     * @Response = ({
     *      message: "message",
     *      status_code: 403,
     *      debug: array of stack trace
     * })
     */
    public function forbidden($msg)
    {
        return $this->baseResult($msg, 403);
    }

    /**
     * Error method
     *
     * Throw error with internal server error code
     * @Parameter = ("msg", type="string", required=true, description="content information why throw error")
     * @Response = ({
     *      message: "message",
     *      status_code: 500,
     *      debug: array of stack trace
     * })
     */
    public function internalServerError($msg)
    {
        return $this->baseResult($msg, 500);
    }

    /**
     * Error method
     *
     * Throw error with not authorize code
     * @Parameters = ({
     *      @Parameter("msg", description="message information"),
     *      @Parameter("code", description="meta for show information to client")
     * })
     * @Response = ({
     *      message: "message",
     *      status_code: 401,
     *      debug: array of stack trace
     * })
     */
    public function notAuthorize($msg)
    {
        return $this->baseResult($msg, 401);
    }

    /**
     * Success method
     *
     * return code 204 for updated / deleted successfull without content
     * @Response = ()
     */
    public function noContent()
    {
        return $this->baseResult([], 204);
    }

    /**
     * Success method
     *
     * return code 200 with content
     * @Parameters = ({
     *      @Parameter("msg", description="message information", default="SUCCESS"),
     *      @Parameter("data", description="data for show information to client", default="[]")]
     * })
     * @Response = ()
     */
    public function result($data = [], $msg = "SUCCESS")
    {
        return $this->baseResult(["message" => $msg, "data" => $data], 200);
    }

    /**
     * Success method
     *
     * return code 200 with content
     * @Parameters = ({
     *      @Parameter("msg", description="message information", default="SUCCESS"),
     *      @Parameter("data", description="data for show information to client", default="[]")
     *      @Parameter("meta", description="meta for show information to client")
     * })
     * @Response = ()
     */
    public function resultWithMeta($data = [], $meta = [], $msg = "SUCCESS")
    {
        return $this->baseResult(["message" => $msg, "data" => $data, "meta" => $meta], 200);
    }



    /*
    * Base Result for success or not with data and http code
    */
    private function baseResult($data, $code)
    {
        if (is_string($data)) {
            $message = $data;
            $data = [];
            $data['message'] = $message;
        }
        $data['timestamp'] = date("d-m-Y H:i:s");
        http_response_code($code);
        echo json_encode($data);
    }
}
