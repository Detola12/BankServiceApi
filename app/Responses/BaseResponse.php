<?php

namespace App\Responses;

use Illuminate\Support\Collection;

class BaseResponse
{
    protected bool $success;
    protected string $message;
    protected array|null $data;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param array|null $data
     */
    public function setData(array|null $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array|null
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function compose()
    {
        if(!$this->isSuccess()){
            return response()->json([
                'success' => $this->isSuccess(),
                'message' => $this->getMessage(),
            ]);
        }

        if (!isset($this->data)){
            return response()->json([
                'success' => $this->isSuccess(),
                'message' => $this->getMessage(),
            ]);
        }

        return response()->json([
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'data' => $this->getData()
        ]);
    }


}
