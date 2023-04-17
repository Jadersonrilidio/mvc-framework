<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Controller\Traits;

trait FileStorageHandler
{
    /**
     * 
     */
    public function storeFile(array $fileData): bool
    {
        $from = $fileData['tmp_name'];
        $to = UPLOAD_DIR . $fileData['hashname'];

        $result = copy($from, $to);

        // symlink(UPLOAD_DIR . $fileData['hashname'], '.' . SLASH . 'public' . SLASH . 'uploads' . SLASH . $fileData['hashname']);

        unlink($from);

        return $result;
    }

    /**
     * 
     */
    public function deleteFile(?string $file = null): bool
    {
        if (!is_null($file)) {

            // unlink(ROOT_DIR . 'public' . SLASH . 'uploads' . SLASH . $file);

            return unlink(UPLOAD_DIR . $file);
        }

        return false;
    }
}
