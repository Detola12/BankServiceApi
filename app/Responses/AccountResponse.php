<?php

namespace App\Responses;

class AccountResponse extends BaseResponse
{
    private int $code;

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    public function compose()
    {
        if (isset($code) && $code == 404){
            return response()->json([
                'success' => $this->isSuccess(),
                'message' => $this->getMessage(),
            ], $code);
        }

        return parent::compose();
    }
}
