<?php
namespace Anexia\Monitoring\Service;

interface UpCheckServiceInterface
{
    /**
     * Check certain db aspects, return true on success,
     * return falls on failure (optionally: add explaining error messages
     * to $errors array)
     *
     * @param array $errors
     * @return bool
     */
    public function check(&$errors = array());
}