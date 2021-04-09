<?php

declare(strict_types=1);

namespace App\Service\User;

final class Delete extends Base
{
    public function delete(int $userId): void
    {
        $this->getUserFromDb($userId);
        // todo delete related data
        $this->userRepository->delete($userId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($userId);
        }
    }
}
