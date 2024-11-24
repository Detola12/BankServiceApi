<?php

namespace App\Responses;

use Illuminate\Support\Collection;

class BaseResponse
{
    protected bool $success;
    protected string $message;
    protected array|null|Collection $data;

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
     * @param array|null|Collection $data
     */
    public function setData(array|null|Collection $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array|null|Collection
     */
    public function getData(): array|null|Collection
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
            ], 400);
        }

        if (!isset($this->data)){
            return response()->json([
                'success' => $this->isSuccess(),
                'message' => $this->getMessage(),
            ], 200);
        }

        return response()->json([
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'data' => $this->getData()
        ], 200);
    }


}
